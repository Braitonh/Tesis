@props([
    'id' => 'drag-drop-spinner',
    'title' => 'Actualizando orden',
    'message' => 'Por favor espera un momento...'
])

<!-- Spinner para drag and drop -->
<div
    id="{{ $id }}"
    style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 9999; display: none; align-items: center; justify-content: center; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"
>
    <div style="background: white; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); padding: 2rem; display: flex; flex-direction: column; align-items: center; gap: 1rem; max-width: 24rem; margin: 0 1rem; position: relative; z-index: 10000;">
        <!-- Spinner SVG -->
        <div style="position: relative;">
            <svg style="animation: dragDropSpin 1s linear infinite; height: 3rem; width: 3rem; color: #f97316;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <!-- Message -->
        <div style="text-align: center;">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 0.25rem;">{{ $title }}</h3>
            <p style="font-size: 0.875rem; color: #6b7280;">{{ $message }}</p>
        </div>
    </div>
</div>

<!-- CSS para la animación -->
<style>
@keyframes dragDropSpin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>