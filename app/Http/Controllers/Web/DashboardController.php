<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $todaySales = Sale::whereDate('sold_at', today())->where('status', 'completed');
        $dailySales = Sale::where('status', 'completed')
            ->where('sold_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('date(sold_at) as sale_date, sum(total) as revenue')
            ->groupBy(DB::raw('date(sold_at)'))
            ->pluck('revenue', 'sale_date');

        $chart = collect(range(6, 0))->map(function ($daysAgo) use ($dailySales) {
            $date = now()->subDays($daysAgo);
            return [
                'label' => $daysAgo === 0 ? 'วันนี้' : $date->locale('th')->isoFormat('dd'),
                'revenue' => (float) ($dailySales[$date->toDateString()] ?? 0),
            ];
        });
        $maxRevenue = max($chart->max('revenue'), 1);

        return view('dashboard', [
            'todayRevenue' => (float) (clone $todaySales)->sum('total'),
            'todayOrders' => (clone $todaySales)->count(),
            'memberCount' => Member::count(),
            'lowStockCount' => Product::lowStock()->count(),
            'recentSales' => Sale::with(['member', 'payment'])->latest('sold_at')->limit(5)->get(),
            'lowStockProducts' => Product::with('category')->lowStock()->orderBy('stock')->limit(5)->get(),
            'chart' => $chart,
            'maxRevenue' => $maxRevenue,
        ]);
    }
}
