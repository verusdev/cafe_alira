<div id="confirm-modal" class="fixed inset-0 z-50 hidden transition-opacity duration-200">
    <div class="absolute inset-0 bg-black/50"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-xl p-6 w-80">
        <p class="text-gray-800 text-lg mb-6 text-center">Вы уверены?</p>
        <div class="flex gap-3 justify-center">
            <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Отмена</button>
            <button type="button" id="confirm-yes" class="px-4 py-2 rounded bg-red-500 text-white hover:bg-red-600">Удалить</button>
        </div>
    </div>
</div>
<script>
    let confirmForm = null;

    window.confirmModal = function (event, btn) {
        event.preventDefault();
        confirmForm = btn.closest('form');
        document.getElementById('confirm-modal').classList.remove('hidden');
        return false;
    };

    window.closeConfirmModal = function () {
        document.getElementById('confirm-modal').classList.add('hidden');
        confirmForm = null;
    };

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('confirm-yes').addEventListener('click', function () {
            if (confirmForm) {
                var form = confirmForm;
                closeConfirmModal();
                form.submit();
            }
        });
    });
</script>