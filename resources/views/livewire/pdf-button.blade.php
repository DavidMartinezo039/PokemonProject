<div style="text-align: center">
    <form action="{{ route('generar-pdf', ['userSet' => $userSet]) }}" method="GET">
        <button
            type="submit"
            class="btn {{ $isPdfReady ? 'btn-danger' : 'btn-secondary' }}"
            @disabled(!$isPdfReady)
        >
            {{ $isPdfReady ? __('Generate') : __('not ready') }} PDF
        </button>
    </form>
</div>
