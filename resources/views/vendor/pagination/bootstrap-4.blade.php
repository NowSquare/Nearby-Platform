@if ($paginator->hasPages())
    <ul class="pagination mt-4 mb-0">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link rounded-0 text-secondary">&laquo;</span></li>
        @else
            <li class="page-item"><a class="page-link rounded-0 text-secondary" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link rounded-0">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-link rounded-0 bg-secondary border-secondary">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link rounded-0 text-secondary" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link rounded-0 text-secondary" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
        @else
            <li class="page-item disabled"><span class="page-link rounded-0 text-secondary">&raquo;</span></li>
        @endif
    </ul>
@endif
