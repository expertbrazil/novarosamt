<div id="sendEmailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Enviar pedido ao fornecedor</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Informe o e-mail do fornecedor para disparar o pedido de compra selecionado.
                </p>
            </div>
            <button type="button" id="closeEmailModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" id="sendEmailForm" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="supplier_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">E-mail do fornecedor</label>
                <input type="email"
                       name="supplier_email"
                       id="supplier_email"
                       required
                       class="mt-1 form-input"
                       placeholder="fornecedor@empresa.com">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelEmailModal" class="btn-secondary">Cancelar</button>
                <button type="submit" class="btn-primary">Enviar</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('sendEmailModal');
    const sendEmailForm = document.getElementById('sendEmailForm');
    const openButtons = document.querySelectorAll('[data-email-modal]');
    const closeButtons = [document.getElementById('closeEmailModal'), document.getElementById('cancelEmailModal')];

    function openModal(actionUrl) {
        sendEmailForm.action = actionUrl;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('supplier_email').focus();
    }

    function closeModal() {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        sendEmailForm.reset();
    }

    openButtons.forEach(button => {
        button.addEventListener('click', () => {
            const actionUrl = button.dataset.action;
            if (!actionUrl) return;
            openModal(actionUrl);
        });
    });

    closeButtons.forEach(btn => btn?.addEventListener('click', closeModal));

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
});
</script>

