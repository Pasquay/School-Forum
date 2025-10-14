@if (session()->has('success'))
<div class="toast-notification toast-success" id="success-toast">
    <div class="toast-content">
        <svg class="toast-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    <button class="toast-close" onclick="closeToast('success-toast')">&times;</button>
</div>
@endif

@if (session()->has('error'))
<div class="toast-notification toast-error" id="error-toast">
    <div class="toast-content">
        <svg class="toast-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    <button class="toast-close" onclick="closeToast('error-toast')">&times;</button>
</div>
@endif

@if ($errors->any())
<div class="toast-notification toast-error" id="validation-toast">
    <div class="toast-content">
        <svg class="toast-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        <div>
            @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
    </div>
    <button class="toast-close" onclick="closeToast('validation-toast')">&times;</button>
</div>
@endif

<style>
    .toast-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        min-width: 300px;
        max-width: 400px;
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        z-index: 9999;
        animation: slideIn 0.3s ease-out;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .toast-success {
        background-color: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .toast-error {
        background-color: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .toast-content {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        flex: 1;
    }

    .toast-icon {
        width: 24px;
        height: 24px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .toast-close {
        background: none;
        border: none;
        font-size: 24px;
        line-height: 1;
        color: inherit;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.7;
        transition: opacity 0.2s;
        flex-shrink: 0;
    }

    .toast-close:hover {
        opacity: 1;
    }

    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    .toast-notification.hiding {
        animation: slideOut 0.3s ease-out forwards;
    }
</style>

<script>
    // Auto-hide toast notifications after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const toasts = document.querySelectorAll('.toast-notification');
        toasts.forEach(toast => {
            setTimeout(() => {
                closeToast(toast.id);
            }, 5000);
        });
    });

    function closeToast(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.classList.add('hiding');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }
</script>