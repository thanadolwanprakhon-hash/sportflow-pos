@extends('layouts.app')
@section('title', 'แดชบอร์ด')
@section('content')
<div class="page-heading">
    <div><h1>ภาพรวมร้านวันนี้</h1><p>ติดตามยอดขาย คำสั่งซื้อ และสถานะคลังสินค้าแบบรวดเร็ว</p></div>
    <a class="btn btn-lime" href="{{ route('pos') }}">＋ เปิดการขายใหม่</a>
</div>

<div class="stats">
    <article class="card stat-card dark"><div class="stat-label">ยอดขายวันนี้</div><div class="stat-value">฿{{ number_format($todayRevenue, 2) }}</div><div class="stat-note">รายรับที่ชำระสำเร็จ</div></article>
    <article class="card stat-card"><div class="stat-label">บิลวันนี้</div><div class="stat-value">{{ number_format($todayOrders) }}</div><div class="stat-note">รายการขายทั้งหมด</div></article>
    <article class="card stat-card"><div class="stat-label">สมาชิกทั้งหมด</div><div class="stat-value">{{ number_format($memberCount) }}</div><div class="stat-note">ฐานลูกค้าของร้าน</div></article>
    <article class="card stat-card"><div class="stat-label">สินค้าใกล้หมด</div><div class="stat-value">{{ number_format($lowStockCount) }}</div><div class="stat-note">ควรวางแผนเติมสต็อก</div></article>
</div>

<div class="dashboard-grid">
    <div class="stack">
        <article class="card">
            <div class="card-head"><div><h2>ยอดขาย 7 วันล่าสุด</h2><span class="muted">รายได้จากบิลที่สำเร็จ</span></div><a class="btn btn-sm btn-outline" href="{{ route('reports') }}">ดูรายงาน</a></div>
            <div class="card-body">
                <div class="chart">
                    @foreach($chart as $point)
                    <div class="bar-wrap"><span class="bar-value">{{ $point['revenue'] ? number_format($point['revenue']/1000, 1).'k' : '' }}</span><span class="bar" style="height: {{ max(($point['revenue'] / $maxRevenue) * 175, 4) }}px"></span><span class="bar-label">{{ $point['label'] }}</span></div>
                    @endforeach
                </div>
            </div>
        </article>
        <article class="card">
            <div class="card-head"><h2>รายการขายล่าสุด</h2><a class="muted" href="{{ route('sales.index') }}">ดูทั้งหมด →</a></div>
            <div class="table-wrap">
                <table><thead><tr><th>เลขที่บิล</th><th>ลูกค้า</th><th>ช่องทาง</th><th>เวลา</th><th class="text-right">ยอดสุทธิ</th></tr></thead><tbody>
                @forelse($recentSales as $sale)
                    <tr><td><a href="{{ route('sales.show', $sale) }}"><strong>{{ $sale->invoice_number }}</strong></a></td><td>{{ $sale->member?->name ?? 'ลูกค้าทั่วไป' }}</td><td><span class="badge">{{ ['cash'=>'เงินสด','qr'=>'QR Code','card'=>'บัตร'][optional($sale->payment)->method] ?? '-' }}</span></td><td>{{ $sale->sold_at->format('H:i') }} น.</td><td class="text-right"><strong>฿{{ number_format($sale->total, 2) }}</strong></td></tr>
                @empty<tr><td colspan="5" class="empty">ยังไม่มีรายการขายวันนี้ เริ่มสร้างบิลแรกได้เลย</td></tr>@endforelse
                </tbody></table>
            </div>
        </article>
    </div>
    <aside class="card">
        <div class="card-head"><div><h2>สินค้าใกล้หมด</h2><span class="muted">สต็อกต่ำกว่าจุดแจ้งเตือน</span></div><span class="badge orange">{{ $lowStockCount }} รายการ</span></div>
        <div class="card-body">
            @forelse($lowStockProducts as $product)
            <div class="list-item"><span class="item-icon">{{ mb_substr($product->name, 0, 1) }}</span><span class="item-main"><strong>{{ $product->name }}</strong><small>{{ $product->category->name }} · {{ $product->sku }}</small></span><span class="badge {{ $product->stock === 0 ? 'red' : 'orange' }}">{{ $product->stock }} ชิ้น</span></div>
            @empty<div class="empty">สต็อกอยู่ในระดับปกติ</div>@endforelse
            <a class="btn btn-outline" style="width:100%;margin-top:12px" href="{{ route('products.index') }}">จัดการสต็อกสินค้า</a>
        </div>
    </aside>
</div>
@endsection
