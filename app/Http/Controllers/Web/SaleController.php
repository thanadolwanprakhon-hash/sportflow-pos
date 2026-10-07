<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $sales = Sale::with(['member', 'employee', 'payment'])
            ->when($request->search, fn ($query, $search) => $query->where(function ($sub) use ($search) {
                $sub->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('member', fn ($member) => $member->where('name', 'like', "%{$search}%"));
            }))
            ->when($request->method, fn ($query, $method) => $query->whereHas('payment', fn ($payment) => $payment->where('method', $method)))
            ->latest('sold_at')
            ->paginate(15)
            ->withQueryString();

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        return view('sales.create', [
            'products' => Product::with('category')->where('is_active', true)->where('stock', '>', 0)->orderBy('name')->get(),
            'members' => Member::orderBy('name')->get(),
        ]);
    }

    public function show(Sale $sale)
    {
        return view('sales.show', ['sale' => $sale->load(['member', 'employee', 'payment', 'items.product'])]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id' => ['nullable', 'exists:members,id'],
            'payment_method' => ['required', Rule::in(['cash', 'qr', 'card'])],
            'received_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_reference' => ['nullable', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'distinct', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $sale = DB::transaction(function () use ($data) {
            $lines = [];
            $subtotal = 0;

            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                if (! $product->is_active || $product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => "สินค้า {$product->name} มีสต็อกไม่เพียงพอ",
                    ]);
                }

                $lineTotal = (float) $product->price * $item['quantity'];
                $subtotal += $lineTotal;
                $lines[] = compact('product', 'item', 'lineTotal');
            }

            $member = isset($data['member_id']) ? Member::lockForUpdate()->find($data['member_id']) : null;
            $discount = $member ? round($subtotal * 0.05, 2) : 0;
            $total = $subtotal - $discount;
            $received = (float) ($data['received_amount'] ?? $total);

            if ($data['payment_method'] === 'cash' && $received < $total) {
                throw ValidationException::withMessages([
                    'received_amount' => 'จำนวนเงินที่รับมาต้องไม่น้อยกว่ายอดสุทธิ',
                ]);
            }

            $todaySequence = Sale::whereDate('sold_at', today())->lockForUpdate()->count() + 1;
            $sale = Sale::create([
                'invoice_number' => 'INV-'.now()->format('Ymd').'-'.str_pad((string) $todaySequence, 3, '0', STR_PAD_LEFT),
                'member_id' => $member?->id,
                'employee_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => 'completed',
                'sold_at' => now(),
            ]);

            foreach ($lines as $line) {
                $product = $line['product'];
                $quantity = $line['item']['quantity'];
                $product->decrement('stock', $quantity);
                $product->refresh();

                $sale->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'unit_cost' => $product->cost,
                    'quantity' => $quantity,
                    'line_total' => $line['lineTotal'],
                ]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'sale_id' => $sale->id,
                    'user_id' => auth()->id(),
                    'type' => 'sale',
                    'quantity' => -$quantity,
                    'balance_after' => $product->stock,
                    'note' => $sale->invoice_number,
                ]);
            }

            $sale->payment()->create([
                'method' => $data['payment_method'],
                'amount' => $total,
                'received_amount' => $received,
                'change_amount' => max($received - $total, 0),
                'reference' => $data['payment_reference'] ?? null,
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            if ($member) {
                $member->increment('points', (int) floor($total / 100));
                $member->increment('total_spent', $total);
                $member->refresh();
                $member->update(['tier' => match (true) {
                    (float) $member->total_spent >= 30000 => 'gold',
                    (float) $member->total_spent >= 10000 => 'silver',
                    default => 'member',
                }]);
            }

            return $sale;
        });

        return redirect()->route('sales.show', $sale)->with('success', 'บันทึกการขายและชำระเงินเรียบร้อยแล้ว');
    }
}
