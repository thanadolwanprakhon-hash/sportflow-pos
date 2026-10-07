@extends('layouts.app')
@section('title', 'ใบเสร็จรับเงิน')
@section('content')
<div class="page-heading no-print"><div><h1>ใบเสร็จรับเงิน</h1><p>{{ $sale->invoice_number }}</p></div><div class="row"><a class="btn btn-outline" href="{{ route('sales.index') }}">← ประวัติการขาย</a><button class="btn btn-primary" type="button" onclick="window.print()">พิมพ์ใบเสร็จ</button></div></div>
<article class="card receipt">
    <header class="receipt-hero"><img class="receipt-logo" src="{{ asset('images/sportflow-logo-transparent.png') }}" alt="SportFlow Smart Retail POS"><h1><span class="success-inline">✓</span> ชำระเงินสำเร็จ</h1></header>
    <div class="receipt-body">
        <div class="receipt-meta"><div><small class="muted">เลขที่ใบเสร็จ</small><br><strong>{{ $sale->invoice_number }}</strong></div><div><small class="muted">วันและเวลา</small><br><strong>{{ $sale->sold_at->locale('th')->translatedFormat('j F Y H:i') }} น.</strong></div><div><small class="muted">ลูกค้า</small><br><strong>{{ $sale->member?->name ?? 'ลูกค้าทั่วไป' }}</strong></div><div><small class="muted">พนักงานขาย</small><br><strong>{{ $sale->employee->name }}</strong></div></div>
        <div class="table-wrap"><table><thead><tr><th>รายการ</th><th class="text-right">ราคา</th><th class="text-center">จำนวน</th><th class="text-right">รวม</th></tr></thead><tbody>@foreach($sale->items as $item)<tr><td><strong>{{ $item->product_name }}</strong></td><td class="text-right">฿{{ number_format($item->unit_price,2) }}</td><td class="text-center">{{ $item->quantity }}</td><td class="text-right">฿{{ number_format($item->line_total,2) }}</td></tr>@endforeach</tbody></table></div>
        <div style="margin-left:auto;max-width:330px;margin-top:18px"><div class="summary-row"><span>ยอดรวม</span><strong>฿{{ number_format($sale->subtotal,2) }}</strong></div><div class="summary-row"><span>ส่วนลด</span><strong>− ฿{{ number_format($sale->discount,2) }}</strong></div><div class="receipt-total"><span>ยอดสุทธิ</span><span>฿{{ number_format($sale->total,2) }}</span></div><div class="summary-row"><span>ชำระโดย</span><strong>{{ ['cash'=>'เงินสด','qr'=>'QR Code','card'=>'บัตร'][$sale->payment->method] }}</strong></div>@if($sale->payment->method==='cash')<div class="summary-row"><span>รับเงิน</span><strong>฿{{ number_format($sale->payment->received_amount,2) }}</strong></div><div class="summary-row"><span>เงินทอน</span><strong>฿{{ number_format($sale->payment->change_amount,2) }}</strong></div>@endif</div>
        <p class="text-center muted" style="margin:28px 0 5px">ขอบคุณที่เลือกซื้ออุปกรณ์กีฬากับ SportFlow</p>
    </div>
</article>
@endsection
