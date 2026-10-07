@if ($paginator->hasPages())
<div class="pagination">
    <span class="muted">รายการ {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} จาก {{ $paginator->total() }}</span>
    <div class="pagination-links">
        @if ($paginator->onFirstPage())<span class="page disabled">‹</span>@else<a href="{{ $paginator->previousPageUrl() }}">‹</a>@endif
        @foreach ($elements as $element)
            @if (is_string($element))<span class="page disabled">…</span>@endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())<span class="page active">{{ $page }}</span>@else<a href="{{ $url }}">{{ $page }}</a>@endif
                @endforeach
            @endif
        @endforeach
        @if ($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}">›</a>@else<span class="page disabled">›</span>@endif
    </div>
</div>
@endif
