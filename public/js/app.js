document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-open-modal]').forEach(button => {
        button.addEventListener('click', () => document.getElementById(button.dataset.openModal)?.showModal());
    });

    document.querySelectorAll('[data-close-modal]').forEach(button => {
        button.addEventListener('click', () => button.closest('dialog')?.close());
    });

    document.querySelectorAll('dialog.modal').forEach(dialog => {
        dialog.addEventListener('click', event => {
            if (event.target === dialog) dialog.close();
        });
    });

    document.querySelectorAll('[data-confirm]').forEach(form => {
        form.addEventListener('submit', event => {
            if (!window.confirm(form.dataset.confirm)) event.preventDefault();
        });
    });

    document.getElementById('mobileMenu')?.addEventListener('click', () => document.body.classList.toggle('menu-open'));

    const editData = document.getElementById('editRecordData');
    if (editData) {
        const dialog = document.getElementById(editData.dataset.dialog);
        if (dialog) {
            const values = JSON.parse(editData.textContent);
            Object.entries(values).forEach(([name, value]) => {
                const field = dialog.querySelector(`[name="${name}"]`);
                if (!field) return;
                if (field.type === 'checkbox') field.checked = Boolean(value);
                else field.value = value ?? '';
            });
            dialog.showModal();
        }
    }
});
