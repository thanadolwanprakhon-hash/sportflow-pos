<div class="modal-body"><div class="form-grid">
    <div class="field full"><label>ชื่อ–นามสกุล *</label><input name="name" value="{{ old('name',$employee?->name) }}" required></div>
    <div class="field"><label>อีเมล *</label><input type="email" name="email" value="{{ old('email',$employee?->email) }}" required></div>
    <div class="field"><label>เบอร์โทรศัพท์</label><input name="phone" value="{{ old('phone',$employee?->phone) }}"></div>
    <div class="field"><label>ตำแหน่ง *</label><select name="role" required>@foreach(['manager'=>'ผู้จัดการร้าน','cashier'=>'พนักงานขาย','stock'=>'พนักงานคลัง','admin'=>'ผู้ดูแลระบบ'] as $value=>$label)<option value="{{ $value }}" @selected(old('role',$employee?->role ?? 'cashier')==$value)>{{ $label }}</option>@endforeach</select></div>
    <div class="field"><label>สถานะ *</label><select name="status" required>@foreach(['active'=>'ทำงาน','on_leave'=>'ลา','suspended'=>'ระงับ'] as $value=>$label)<option value="{{ $value }}" @selected(old('status',$employee?->status ?? 'active')==$value)>{{ $label }}</option>@endforeach</select></div>
    <div class="field full"><label>รหัสผ่าน {{ $employee ? '(เว้นว่างหากไม่เปลี่ยน)' : '*' }}</label><input type="password" name="password" {{ $employee ? '' : 'required' }} minlength="6"></div>
</div></div>
