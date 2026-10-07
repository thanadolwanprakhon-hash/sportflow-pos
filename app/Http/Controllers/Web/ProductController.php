<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->search, fn ($query, $search) => $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%")))
            ->when($request->category, fn ($query, $category) => $query->where('category_id', $category))
            ->latest()->paginate(12)->withQueryString();

        return view('products.index', [
            'products' => $products,
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'inventoryValue' => Product::selectRaw('sum(cost * stock) as value')->value('value') ?? 0,
            'totalStock' => Product::sum('stock'),
            'lowStockCount' => Product::lowStock()->count(),
        ]);
    }

    public function store(Request $request)
    {
        $product = Product::create($this->validated($request));
        if ($product->stock > 0) {
            StockMovement::create(['product_id' => $product->id, 'user_id' => auth()->id(), 'type' => 'initial', 'quantity' => $product->stock, 'balance_after' => $product->stock, 'note' => 'Initial stock']);
        }
        return back()->with('success', 'เพิ่มสินค้าเรียบร้อยแล้ว');
    }

    public function edit(Product $product)
    {
        return redirect()->route('products.index', ['edit' => $product->id]);
    }

    public function update(Request $request, Product $product)
    {
        $oldStock = $product->stock;
        $product->update($this->validated($request, $product));
        if ($oldStock !== $product->stock) {
            StockMovement::create(['product_id' => $product->id, 'user_id' => auth()->id(), 'type' => 'adjustment', 'quantity' => $product->stock - $oldStock, 'balance_after' => $product->stock, 'note' => 'Manual adjustment']);
        }
        return back()->with('success', 'แก้ไขสินค้าเรียบร้อยแล้ว');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'ลบสินค้าเรียบร้อยแล้ว');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => ['required', 'max:50', Rule::unique('products')->ignore($product)],
            'barcode' => ['nullable', 'max:100', Rule::unique('products')->ignore($product)],
            'name' => ['required', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'source_url' => ['nullable', 'url', 'max:2048'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
