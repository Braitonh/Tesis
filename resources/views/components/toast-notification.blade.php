{{-- Toast Notification Container --}}
<div id="toast-container" class="fixed top-20 right-4 z-50 space-y-3 w-96 max-w-full px-4 sm:px-0" role="alert" aria-live="polite">
    {{-- Los toasts se insertarán aquí dinámicamente vía JavaScript --}}
</div>

<style>
    /* Animaciones para toasts */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }

    .toast-enter {
        animation: slideInRight 0.3s ease-out forwards;
    }

    .toast-exit {
        animation: slideOutRight 0.3s ease-in forwards;
    }

    /* Estilos base para toasts */
    .toast {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .toast:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }

    .toast::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, rgba(255,255,255,0.5) 0%, rgba(255,255,255,0.8) 100%);
    }

    /* Tipos de toast */
    .toast-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .toast-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .toast-error {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .toast-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .toast-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .toast-content {
        flex: 1;
        font-size: 0.95rem;
        font-weight: 500;
        line-height: 1.4;
    }

    .toast-close {
        flex-shrink: 0;
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .toast-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    /* Responsive */
    @media (max-width: 640px) {
        #toast-container {
            width: calc(100vw - 2rem);
            right: 1rem;
            top: 5rem;
        }

        .toast {
            padding: 0.875rem 1rem;
        }

        .toast-content {
            font-size: 0.875rem;
        }

        .toast-icon {
            font-size: 1.25rem;
        }
    }

    /* Animación de progreso */
    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: rgba(255, 255, 255, 0.4);
        animation: progressBar var(--duration) linear forwards;
    }

    @keyframes progressBar {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }
</style>


