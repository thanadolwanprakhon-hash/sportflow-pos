<div class="modal-body"><div class="form-grid">
    <div class="field full"><label>ชื่อ–นามสกุล *</label><input name="name" value="{{ old('name',$member?->name) }}" required></div>
    <div class="field"><label>เบอร์โทรศัพท์ *</label><input name="phone" value="{{ old('phone',$member?->phone) }}" required></div>
    <div class="field"><label>อีเมล</label><input type="email" name="email" value="{{ old('email',$member?->email) }}"></div>
    <div class="field"><label>ระดับสมาชิก *</label><select name="tier" required>@foreach(['member'=>'Member','silver'=>'Silver','gold'=>'Gold'] as $value=>$label)<option value="{{ $value }}" @selected(old('tier',$member?->tier ?? 'member')==$value)>{{ $label }}</option>@endforeach</select></div>
    <div class="field"><label>คะแนนสะสม *</label><input type="number" min="0" name="points" value="{{ old('points',$member?->points ?? 0) }}" required></div>
</div></div>
