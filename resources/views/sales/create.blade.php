@extends('layouts.app')
@section('title', 'จุดขายสินค้า')
@section('content')
<div class="page-heading"><div><h1>จุดขายสินค้า</h1><p>เลือกสินค้า ตรวจสอบรายการ และรับชำระเงิน</p></div><span class="badge green">● เครื่องขายพร้อมใช้งาน</span></div>

<div class="pos-layout">
    <section>
        <div class="card toolbar">
            <div class="search"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input id="productSearch" placeholder="ค้นหาชื่อสินค้า หรือ SKU"></div>
        </div>
        <div class="category-tabs" id="categoryTabs"><button class="tab active" type="button" data-category="all">ทั้งหมด</button>@foreach($products->pluck('category.name')->unique() as $category)<button class="tab" type="button" data-category="{{ $category }}">{{ $category }}</button>@endforeach</div>
        <div class="product-grid" id="productGrid">
            @forelse($products as $product)
            @php($imageSrc = $product->image_url ? (\Illuminate\Support\Str::startsWith($product->image_url, ['http://', 'https://']) ? $product->image_url : asset($product->image_url)) : null)
            <button class="product-card" type="button" data-product-id="{{ $product->id }}" data-name="{{ $product->name }}" data-sku="{{ $product->sku }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}" data-category="{{ $product->category->name }}">
                <span class="stock-dot">เหลือ {{ $product->stock }}</span><span class="visual">@if($imageSrc)<img src="{{ $imageSrc }}" alt="{{ $product->name }}" loading="lazy">@else{{ mb_substr($product->name,0,1) }}@endif</span><strong>{{ $product->name }}</strong><small class="muted">{{ $product->sku }}</small><span class="price">฿{{ number_format($product->price,2) }}</span>
            </button>
            @empty<div class="card empty" style="grid-column:1/-1">ไม่มีสินค้าพร้อมขาย กรุณาเพิ่มสต็อกก่อน</div>@endforelse
        </div>
        <div class="card empty hidden" id="productEmpty">ไม่พบสินค้าที่ค้นหา</div>
    </section>

    <aside class="card cart">
        <div class="card-head"><div><h2>รายการขาย</h2><span class="muted" id="cartCount">0 รายการ</span></div><button class="btn btn-sm btn-outline" type="button" id="clearCart">ล้าง</button></div>
        <button class="member-select" id="chooseMember" type="button"><strong id="memberName">＋ เลือกสมาชิก</strong><br><small id="memberHint">สมาชิกได้รับส่วนลด 5%</small></button>
        <div class="cart-lines" id="cartLines"><div class="empty">ยังไม่มีสินค้า<br><small>กดที่สินค้าเพื่อเพิ่มลงในบิล</small></div></div>
        <div class="cart-summary"><div class="summary-row"><span>ยอดรวม</span><strong id="subtotal">฿0.00</strong></div><div class="summary-row"><span>ส่วนลดสมาชิก</span><strong id="discount">− ฿0.00</strong></div><div class="summary-row total"><span>ยอดสุทธิ</span><span id="grandTotal">฿0.00</span></div></div>
        <button class="btn btn-lime checkout" id="checkoutButton" type="button" disabled>รับชำระเงิน</button>
    </aside>
</div>

<dialog class="modal" id="memberDialog">
    <div class="modal-head"><h2>เลือกสมาชิก</h2><button class="modal-close" type="button" data-close-modal>✕</button></div>
    <div class="modal-body">
        <div class="field"><label>ค้นหาสมาชิก</label><input id="memberSearch" placeholder="ชื่อ เบอร์โทร หรือรหัสสมาชิก"></div>
        <div id="memberList" style="margin-top:12px">
            <button class="member-select" style="width:100%;margin:0 0 8px;text-align:left" type="button" data-member-id="" data-member-name="ลูกค้าทั่วไป"><strong>ลูกค้าทั่วไป</strong><br><small>ไม่ใช้สิทธิ์ส่วนลดสมาชิก</small></button>
            @foreach($members as $member)
            <button class="member-select member-option" style="width:100%;margin:0 0 8px;text-align:left" type="button" data-member-id="{{ $member->id }}" data-member-name="{{ $member->name }}" data-search="{{ mb_strtolower($member->name.' '.$member->phone.' '.$member->member_code) }}"><strong>{{ $member->name }}</strong><br><small>{{ $member->member_code }} · {{ $member->phone }} · {{ number_format($member->points) }} คะแนน</small></button>
            @endforeach
        </div>
    </div>
</dialog>

<form id="saleForm" method="POST" action="{{ route('sales.store') }}">@csrf
    <input type="hidden" name="member_id" id="memberId">
    <div id="itemInputs"></div>
    <dialog class="modal" id="paymentDialog">
        <div class="modal-head"><div><h2>รับชำระเงิน</h2><span class="muted">ตรวจสอบยอดก่อนยืนยัน</span></div><button class="modal-close" type="button" data-close-modal>✕</button></div>
        <div class="modal-body stack">
            <div class="card" style="padding:18px;text-align:center;background:#f4f8f4"><span class="muted">ยอดที่ต้องชำระ</span><div id="payTotal" style="font-size:35px;font-weight:900;color:var(--green);margin-top:4px">฿0.00</div></div>
            <div class="field"><label>ช่องทางชำระเงิน *</label><div class="payment-methods">
                <label class="pay-option"><input type="radio" name="payment_method" value="cash" checked><span>💵<br>เงินสด</span></label>
                <label class="pay-option"><input type="radio" name="payment_method" value="qr"><span>▦<br>QR Code</span></label>
                <label class="pay-option"><input type="radio" name="payment_method" value="card"><span>▭<br>บัตร</span></label>
            </div></div>
            <div class="field" id="cashField"><label>จำนวนเงินที่รับมา *</label><input id="receivedAmount" type="number" min="0" step="0.01" name="received_amount"><span class="hint" id="changeHint">เงินทอน ฿0.00</span></div>
            <div class="field"><label>เลขอ้างอิง (ถ้ามี)</label><input name="payment_reference" placeholder="เช่น เลขรายการโอน/เลขอนุมัติ"></div>
        </div>
        <div class="modal-actions"><button class="btn btn-outline" type="button" data-close-modal>ย้อนกลับ</button><button class="btn btn-primary" type="submit">ยืนยันและออกใบเสร็จ</button></div>
    </dialog>
</form>
@endsection
@push('scripts')<script src="{{ asset('js/pos.js') }}"></script>@endpush
