@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Nút về trang đầu --}}
            <li class="page-item first {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url(1) }}">
                    <span><<</span>
                </a>
            </li>

            {{-- Nút lùi 1 trang --}}
            <li class="page-item prev {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                    <span><</span>
                </a>
            </li>

            {{-- Các số trang --}}
            @php
                $start = $paginator->currentPage();
                $total = $paginator->lastPage();
                $end = min($start + 9, $total);
                if ($end - $start < 9) {
                    $start = max($end - 9, 1);
                }
            @endphp

            @for ($i = $start; $i <= $end; $i++)
                <li class="page-item number {{ $paginator->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            {{-- Nút tiến 1 trang --}}
            <li class="page-item next {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}">
                    <span>></span>
                </a>
            </li>

            {{-- Nút đến trang cuối --}}
            <li class="page-item last {{ $paginator->currentPage() == $paginator->lastPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">
                    <span>>></span>
                </a>
            </li>
        </ul>
    </nav>
@endif
