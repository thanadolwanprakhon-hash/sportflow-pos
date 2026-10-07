@extends('layouts.app')
@section('title', 'ตั้งค่าร้าน')
@section('content')
<div class="page-heading"><div><h1>ตั้งค่าร้าน</h1><p>ข้อมูลร้านสำหรับเอกสารและการติดต่อ</p></div></div>
<div class="settings-grid">
    <form class="card" method="POST" action="{{ route('settings.update') }}">@csrf @method('PUT')
        <div class="card-head"><div><h2>ข้อมูลร้านค้า</h2><span class="muted">แก้ไขรายละเอียดหลักของ SportFlow</span></div></div>
        <div class="card-body"><div class="form-grid">
            <div class="field full"><label>ชื่อร้าน *</label><input name="shop_name" value="{{ old('shop_name',$settings['shop_name'] ?? 'SportFlow Sports') }}" required></div>
            <div class="field"><label>เบอร์โทรศัพท์</label><input name="shop_phone" value="{{ old('shop_phone',$settings['shop_phone'] ?? '02-123-4567') }}"></div>
            <div class="field"><label>เลขประจำตัวผู้เสียภาษี</label><input name="tax_id" value="{{ old('tax_id',$settings['tax_id'] ?? '') }}"></div>
            <div class="field full"><label>ที่อยู่ร้าน</label><textarea name="shop_address">{{ old('shop_address',$settings['shop_address'] ?? 'กรุงเทพมหานคร ประเทศไทย') }}</textarea></div>
            <div class="field full"><label>ข้อความท้ายใบเสร็จ</label><input name="receipt_footer" value="{{ old('receipt_footer',$settings['receipt_footer'] ?? 'ขอบคุณที่เลือกซื้ออุปกรณ์กีฬากับ SportFlow') }}"></div>
        </div></div>
        <div class="modal-actions"><button class="btn btn-primary">บันทึกการตั้งค่า</button></div>
    </form>
    <aside class="info-panel"><img class="info-logo" src="{{ asset('images/sportflow-logo-transparent.png') }}" alt="SportFlow Smart Retail POS"><h3>SportFlow POS</h3><p>ระบบบริหารร้านขายอุปกรณ์กีฬาพัฒนาด้วย Laravel Framework</p><div class="list-item" style="border-color:rgba(255,255,255,.12)"><span class="item-main"><small>Framework</small><strong>Laravel {{ app()->version() }}</strong></span></div><div class="list-item" style="border-color:rgba(255,255,255,.12)"><span class="item-main"><small>หน้าจอ</small><strong>Blade Template</strong></span></div><div class="list-item" style="border:0"><span class="item-main"><small>ฐานข้อมูล</small><strong>MySQL / MariaDB</strong></span></div></aside>
</div>
@endsection
