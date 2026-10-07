<div class="modal-body"><div class="form-grid">
    <div class="field full"><label>ชื่อสินค้า *</label><input name="name" value="{{ old('name',$product?->name) }}" required></div>
    <div class="field full"><label>รูปสินค้า</label><input name="image_url" value="{{ old('image_url',$product?->image_url) }}" placeholder="เช่น images/products/running-nike-pegasus-42.jpg หรือ https://..."><span class="hint">ใส่พาธรูปใน public หรือ URL รูปภายนอก</span></div>
    <div class="field full"><label>ลิงก์แหล่งอ้างอิง</label><input type="url" name="source_url" value="{{ old('source_url',$product?->source_url) }}" placeholder="https://www.supersports.co.th/..."></div>
    <div class="field"><label>รหัส SKU *</label><input name="sku" value="{{ old('sku',$product?->sku) }}" placeholder="เช่น SHO-NK-013" required></div>
    <div class="field"><label>บาร์โค้ด</label><input name="barcode" value="{{ old('barcode',$product?->barcode) }}"></div>
    <div class="field full"><label>หมวดหมู่ *</label><select name="category_id" required><option value="">เลือกหมวดหมู่</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id',$product?->category_id)==$category->id)>{{ $category->name }}</option>@endforeach</select></div>
    <div class="field"><label>ราคาขาย (บาท) *</label><input type="number" min="0" step="0.01" name="price" value="{{ old('price',$product?->price) }}" required></div>
    <div class="field"><label>ราคาทุน (บาท) *</label><input type="number" min="0" step="0.01" name="cost" value="{{ old('cost',$product?->cost) }}" required></div>
    <div class="field"><label>จำนวนสต็อก *</label><input type="number" min="0" name="stock" value="{{ old('stock',$product?->stock ?? 0) }}" required></div>
    <div class="field"><label>แจ้งเตือนเมื่อเหลือ *</label><input type="number" min="0" name="minimum_stock" value="{{ old('minimum_stock',$product?->minimum_stock ?? 5) }}" required></div>
    <div class="field full"><label class="check-row"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$product?->is_active ?? true))> เปิดขายสินค้านี้</label></div>
</div></div>
