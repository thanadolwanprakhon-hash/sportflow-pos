@extends('layouts.app')
@section('title', 'สินค้าและสต็อก')
@section('content')
@php($editing = request('edit') ? \App\Models\Product::find(request('edit')) : null)
<div class="page-heading">
    <div><h1>สินค้าและสต็อก</h1><p>เพิ่ม แก้ไข และตรวจสอบจำนวนสินค้า · รายการสาธิตอ้างอิงจาก <a class="source-link" href="https://www.supersports.co.th/" target="_blank" rel="noopener">Supersports</a></p></div>
    <button class="btn btn-primary" type="button" data-open-modal="productCreate">＋ เพิ่มสินค้า</button>
</div>
<div class="stats">
    <article class="card stat-card dark"><div class="stat-label">รายการสินค้าทั้งหมด</div><div class="stat-value">{{ number_format($products->total()) }}</div><div class="stat-note">SKU ในระบบ</div></article>
    <article class="card stat-card"><div class="stat-label">สินค้าคงเหลือ</div><div class="stat-value">{{ number_format($totalStock) }}</div><div class="stat-note">ชิ้นในคลัง</div></article>
    <article class="card stat-card"><div class="stat-label">มูลค่าคลัง (ราคาทุน)</div><div class="stat-value">฿{{ number_format($inventoryValue, 0) }}</div><div class="stat-note">ประมาณการจากสต็อกปัจจุบัน</div></article>
    <article class="card stat-card"><div class="stat-label">ใกล้หมด</div><div class="stat-value">{{ number_format($lowStockCount) }}</div><div class="stat-note">ถึงจุดแจ้งเตือน</div></article>
</div>
<form class="card toolbar" method="GET">
    <div class="search"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อสินค้า หรือ SKU"></div>
    <select name="category"><option value="">ทุกหมวดหมู่</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>@endforeach</select>
    <button class="btn btn-soft">ค้นหา</button>@if(request()->hasAny(['search','category']))<a class="btn btn-outline" href="{{ route('products.index') }}">ล้าง</a>@endif
</form>
<div class="card">
    <div class="table-wrap"><table><thead><tr><th>สินค้า</th><th>SKU</th><th>หมวดหมู่</th><th class="text-right">ราคาขาย</th><th>สต็อก</th><th>สถานะ</th><th class="text-right">จัดการ</th></tr></thead><tbody>
    @forelse($products as $product)
    @php($imageSrc = $product->image_url ? (\Illuminate\Support\Str::startsWith($product->image_url, ['http://', 'https://']) ? $product->image_url : asset($product->image_url)) : null)
    <tr>
        <td><div class="product-name"><span class="product-thumb">@if($imageSrc)<img src="{{ $imageSrc }}" alt="{{ $product->name }}" loading="lazy">@else{{ mb_substr($product->name,0,1) }}@endif</span><span><strong>{{ $product->name }}</strong><small class="muted">ทุน ฿{{ number_format($product->cost,2) }}@if($product->source_url) · <a class="source-link" href="{{ $product->source_url }}" target="_blank" rel="noopener">แหล่งอ้างอิง</a>@endif</small></span></div></td>
        <td>{{ $product->sku }}</td><td><span class="badge">{{ $product->category->name }}</span></td><td class="text-right"><strong>฿{{ number_format($product->price,2) }}</strong></td>
        <td><span class="badge {{ $product->stock <= $product->minimum_stock ? 'orange' : 'green' }}">{{ $product->stock }} ชิ้น</span></td><td><span class="badge {{ $product->is_active ? 'green' : 'red' }}">{{ $product->is_active ? 'พร้อมขาย' : 'ปิดขาย' }}</span></td>
        <td class="text-right"><div class="row" style="justify-content:flex-end"><a class="btn btn-sm btn-outline" href="{{ route('products.edit',$product) }}">แก้ไข</a><form method="POST" action="{{ route('products.destroy',$product) }}" data-confirm="ยืนยันการลบสินค้านี้?">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">ลบ</button></form></div></td>
    </tr>
    @empty<tr><td colspan="7" class="empty">ไม่พบสินค้าที่ค้นหา</td></tr>@endforelse
    </tbody></table></div>
    {{ $products->links('partials.pagination') }}
</div>

<dialog class="modal" id="productCreate">
<form method="POST" action="{{ route('products.store') }}">@csrf
    <div class="modal-head"><h2>เพิ่มสินค้าใหม่</h2><button class="modal-close" type="button" data-close-modal>✕</button></div>
    @include('products.partials.form', ['product' => null])
    <div class="modal-actions"><button class="btn btn-outline" type="button" data-close-modal>ยกเลิก</button><button class="btn btn-primary">บันทึกสินค้า</button></div>
</form></dialog>
@if($editing)
<dialog class="modal" id="productEdit">
<form method="POST" action="{{ route('products.update',$editing) }}">@csrf @method('PUT')
    <div class="modal-head"><h2>แก้ไขสินค้า</h2><a class="modal-close" href="{{ route('products.index') }}">✕</a></div>
    @include('products.partials.form', ['product' => $editing])
    <div class="modal-actions"><a class="btn btn-outline" href="{{ route('products.index') }}">ยกเลิก</a><button class="btn btn-primary">บันทึกการแก้ไข</button></div>
</form></dialog>
@push('scripts')<script>document.getElementById('productEdit').showModal()</script>@endpush
@endif
@endsection
