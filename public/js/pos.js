document.addEventListener('DOMContentLoaded', () => {
    const money = value => `฿${Number(value).toLocaleString('th-TH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    const escape = text => String(text).replace(/[&<>'"]/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[char]));
    const cart = new Map();
    let selectedMember = null;
    const cards = [...document.querySelectorAll('.product-card')];
    const lines = document.getElementById('cartLines');
    const checkout = document.getElementById('checkoutButton');
    const memberDialog = document.getElementById('memberDialog');
    const paymentDialog = document.getElementById('paymentDialog');

    function totals() {
        const subtotal = [...cart.values()].reduce((sum, item) => sum + item.price * item.quantity, 0);
        const discount = selectedMember ? subtotal * .05 : 0;
        return {subtotal, discount, total: subtotal - discount};
    }

    function render() {
        if (!cart.size) {
            lines.innerHTML = '<div class="empty">ยังไม่มีสินค้า<br><small>กดที่สินค้าเพื่อเพิ่มลงในบิล</small></div>';
        } else {
            lines.innerHTML = [...cart.values()].map(item => `<div class="cart-line"><div><strong>${escape(item.name)}</strong><small class="muted">${money(item.price)} / ชิ้น</small><div class="qty"><button type="button" data-qty="minus" data-id="${item.id}">−</button><strong>${item.quantity}</strong><button type="button" data-qty="plus" data-id="${item.id}">＋</button><button class="remove" type="button" data-qty="remove" data-id="${item.id}">✕</button></div></div><strong>${money(item.price * item.quantity)}</strong></div>`).join('');
        }
        const sum = totals();
        document.getElementById('subtotal').textContent = money(sum.subtotal);
        document.getElementById('discount').textContent = `− ${money(sum.discount)}`;
        document.getElementById('grandTotal').textContent = money(sum.total);
        document.getElementById('cartCount').textContent = `${[...cart.values()].reduce((s,i)=>s+i.quantity,0)} ชิ้น`;
        checkout.disabled = !cart.size;
    }

    cards.forEach(card => card.addEventListener('click', () => {
        const id = Number(card.dataset.productId);
        const current = cart.get(id);
        const stock = Number(card.dataset.stock);
        if (current && current.quantity >= stock) return alert('จำนวนสินค้าในตะกร้าถึงสต็อกคงเหลือแล้ว');
        cart.set(id, {id, name: card.dataset.name, price: Number(card.dataset.price), stock, quantity: (current?.quantity ?? 0) + 1});
        render();
    }));

    lines.addEventListener('click', event => {
        const button = event.target.closest('[data-qty]');
        if (!button) return;
        const id = Number(button.dataset.id);
        const item = cart.get(id);
        if (!item) return;
        if (button.dataset.qty === 'plus' && item.quantity < item.stock) item.quantity++;
        if (button.dataset.qty === 'minus') item.quantity--;
        if (button.dataset.qty === 'remove' || item.quantity <= 0) cart.delete(id);
        render();
    });

    document.getElementById('clearCart').addEventListener('click', () => { cart.clear(); render(); });
    document.getElementById('chooseMember').addEventListener('click', () => memberDialog.showModal());
    document.getElementById('memberList').addEventListener('click', event => {
        const option = event.target.closest('[data-member-id]');
        if (!option) return;
        selectedMember = option.dataset.memberId ? {id: option.dataset.memberId, name: option.dataset.memberName} : null;
        document.getElementById('memberId').value = selectedMember?.id ?? '';
        document.getElementById('memberName').textContent = selectedMember ? selectedMember.name : '＋ เลือกสมาชิก';
        document.getElementById('memberHint').textContent = selectedMember ? 'รับส่วนลดสมาชิก 5%' : 'สมาชิกได้รับส่วนลด 5%';
        memberDialog.close(); render();
    });

    document.getElementById('memberSearch').addEventListener('input', event => {
        const keyword = event.target.value.toLowerCase().trim();
        document.querySelectorAll('.member-option').forEach(option => option.classList.toggle('hidden', !option.dataset.search.includes(keyword)));
    });

    function filterProducts() {
        const keyword = document.getElementById('productSearch').value.toLowerCase().trim();
        const active = document.querySelector('.tab.active')?.dataset.category ?? 'all';
        let shown = 0;
        cards.forEach(card => {
            const matchText = `${card.dataset.name} ${card.dataset.sku}`.toLowerCase().includes(keyword);
            const matchCategory = active === 'all' || card.dataset.category === active;
            card.classList.toggle('hidden', !(matchText && matchCategory));
            if (matchText && matchCategory) shown++;
        });
        document.getElementById('productEmpty').classList.toggle('hidden', shown > 0);
    }
    document.getElementById('productSearch').addEventListener('input', filterProducts);
    document.getElementById('categoryTabs').addEventListener('click', event => {
        const tab = event.target.closest('.tab'); if (!tab) return;
        document.querySelectorAll('.tab').forEach(item => item.classList.remove('active')); tab.classList.add('active'); filterProducts();
    });

    checkout.addEventListener('click', () => {
        const sum = totals();
        document.getElementById('payTotal').textContent = money(sum.total);
        document.getElementById('receivedAmount').value = sum.total.toFixed(2);
        document.getElementById('itemInputs').innerHTML = [...cart.values()].map((item,index) => `<input type="hidden" name="items[${index}][product_id]" value="${item.id}"><input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">`).join('');
        document.getElementById('changeHint').textContent = 'เงินทอน ฿0.00';
        paymentDialog.showModal();
    });

    document.querySelectorAll('[name="payment_method"]').forEach(radio => radio.addEventListener('change', () => {
        const cash = radio.value === 'cash' && radio.checked;
        if (radio.checked) document.getElementById('cashField').classList.toggle('hidden', !cash);
        if (!cash && radio.checked) document.getElementById('receivedAmount').value = totals().total.toFixed(2);
    }));
    document.getElementById('receivedAmount').addEventListener('input', event => {
        document.getElementById('changeHint').textContent = `เงินทอน ${money(Math.max(Number(event.target.value || 0) - totals().total, 0))}`;
    });

    render();
});
