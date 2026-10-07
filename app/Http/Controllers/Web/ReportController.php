<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __invoke(Request $request)
    {
        $from = Carbon::parse($request->input('from', now()->startOfMonth()->toDateString()))->startOfDay();
        $to = Carbon::parse($request->input('to', now()->toDateString()))->endOfDay();
        if ($from->gt($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        $base = Sale::where('status', 'completed')->whereBetween('sold_at', [$from, $to]);
        $summary = [
            'revenue' => (float) (clone $base)->sum('total'),
            'orders' => (clone $base)->count(),
            'discount' => (float) (clone $base)->sum('discount'),
            'average' => (float) ((clone $base)->avg('total') ?? 0),
        ];

        $dailySales = (clone $base)
            ->selectRaw('date(sold_at) as sale_date, sum(total) as revenue, count(*) as orders')
            ->groupBy(DB::raw('date(sold_at)'))
            ->orderBy('sale_date')
            ->get();

        $topProducts = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->where('sales.status', 'completed')
            ->whereBetween('sales.sold_at', [$from, $to])
            ->selectRaw('sale_items.product_name, sum(sale_items.quantity) as quantity, sum(sale_items.line_total) as revenue')
            ->groupBy('sale_items.product_name')
            ->orderByDesc('revenue')
            ->limit(6)
            ->get();

        $payments = DB::table('payments')
            ->join('sales', 'sales.id', '=', 'payments.sale_id')
            ->where('payments.status', 'paid')
            ->whereBetween('sales.sold_at', [$from, $to])
            ->selectRaw('payments.method, count(*) as orders, sum(payments.amount) as total')
            ->groupBy('payments.method')
            ->orderByDesc('total')
            ->get();

        return view('reports.index', compact('from', 'to', 'summary', 'dailySales', 'topProducts', 'payments'));
    }
}
