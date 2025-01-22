@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="pagination">
        {{-- Botón de "Previous" --}}
        <span class="pagination-prev">
            @if ($paginator->onFirstPage())
                <span class="disabled">«</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev">«</a>
            @endif
        </span>

        {{-- Mostrar los links de las páginas --}}
        <span class="pagination-links">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="disabled">{{ $element }}</span>
                @elseif (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="current">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </span>

        {{-- Botón de "Next" --}}
        <span class="pagination-next">
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next">»</a>
            @else
                <span class="disabled">»</span>
            @endif
        </span>
    </nav>

    {{-- Mostrar el rango actual de elementos
    <div class="pagination-info">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} cards
    </div> --}}
@endif
