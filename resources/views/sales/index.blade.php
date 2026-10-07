@extends('layouts.app')
@section('title', 'ประวัติการขาย')
@section('content')
<div class="page-heading"><div><h1>ประวัติการขาย</h1><p>ค้นหาและตรวจสอบรายการขายกับการชำระเงิน</p></div><a class="btn btn-lime" href="{{ route('pos') }}">＋ สร้างบิลใหม่</a></div>
<form class="card toolbar" method="GET"><div class="search"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input name="search" value="{{ request('search') }}" placeholder="ค้นหาเลขที่บิล หรือชื่อลูกค้า"></div><select name="method"><option value="">ทุกช่องทาง</option>@foreach(['cash'=>'เงินสด','qr'=>'QR Code','card'=>'บัตร'] as $value=>$label)<option value="{{ $value }}" @selected(request('method')==$value)>{{ $label }}</option>@endforeach</select><button class="btn btn-soft">ค้นหา</button>@if(request()->hasAny(['search','method']))<a class="btn btn-outline" href="{{ route('sales.index') }}">ล้าง</a>@endif</form>
<div class="card"><div class="table-wrap"><table><thead><tr><th>เลขที่บิล</th><th>วันเวลา</th><th>ลูกค้า</th><th>พนักงาน</th><th>ชำระเงิน</th><th class="text-right">ส่วนลด</th><th class="text-right">ยอดสุทธิ</th><th></th></tr></thead><tbody>
@forelse($sales as $sale)
<tr><td><strong>{{ $sale->invoice_number }}</strong></td><td>{{ $sale->sold_at->locale('th')->translatedFormat('j M Y') }}<br><small class="muted">{{ $sale->sold_at->format('H:i') }} น.</small></td><td>{{ $sale->member?->name ?? 'ลูกค้าทั่วไป' }}</td><td>{{ $sale->employee->name }}</td><td><span class="badge green">{{ ['cash'=>'เงินสด','qr'=>'QR Code','card'=>'บัตร'][$sale->payment?->method] ?? '-' }}</span></td><td class="text-right">฿{{ number_format($sale->discount,2) }}</td><td class="text-right"><strong>฿{{ number_format($sale->total,2) }}</strong></td><td><a class="btn btn-sm btn-outline" href="{{ route('sales.show',$sale) }}">ดูใบเสร็จ</a></td></tr>
@empty<tr><td colspan="8" class="empty">ยังไม่มีรายการขาย</td></tr>@endforelse
</tbody></table></div>{{ $sales->links('partials.pagination') }}</div>
@endsection
