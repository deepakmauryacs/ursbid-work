@props(['paginator'])

@php
    $onEachSide = 2;
    $window = $onEachSide * 2;

    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();

    $startPage = max($currentPage - $onEachSide, 1);
    $endPage = min($currentPage + $onEachSide, $lastPage);

    if ($currentPage <= $onEachSide) {
        $endPage = min($window + 1, $lastPage);
    }
    if ($currentPage >= $lastPage - $onEachSide) {
        $startPage = max($lastPage - $window, 1);
    }
@endphp

@if ($paginator->total() > 0)
<div class="ra-pagination pt-3 pt-sm-0" style="padding: 10px;">
    <div class="row gy-3 align-items-center justify-content-between" style="width: 100%;">
        {{-- Per Page Dropdown --}}
        <div class="col-12 col-sm-auto">
            <form method="GET" class="d-flex align-items-center gap-2">
                <label for="per_page" class="mb-0">Show</label>
                <select id="per_page" name="per_page" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    @foreach ([10, 25, 50, 75, 100] as $length)
                        <option value="{{ $length }}" {{ request('per_page', 10) == $length ? 'selected' : '' }}>
                            {{ $length }}
                        </option>
                    @endforeach
                </select>
                <span>entries</span>
            </form>
        </div>

        {{-- Pagination --}}
        <div class="col-12 col-sm-auto">
            <nav aria-label="Page navigation example">
                <ul class="pagination mb-0">
                    {{-- Previous --}}
                    <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link  px-3" href="{{ $paginator->previousPageUrl() }}">&laquo; Prev</a>
                    </li>

                    {{-- First page and dots --}}
                    @if ($startPage > 1)
                        <li class="page-item {{ $currentPage == 1 ? 'active' : '' }}">
                            <a class="page-link " href="{{ $paginator->url(1) }}">1</a>
                        </li>
                        @if ($startPage > 2)
                            <li class="page-item disabled"><span class="page-link ">...</span></li>
                        @endif
                    @endif

                    {{-- Main page numbers --}}
                    @for ($page = $startPage; $page <= $endPage; $page++)
                        <li class="page-item {{ $page == $currentPage ? 'active' : '' }}">
                            <a class="page-link " href="{{ $paginator->url($page) }}">{{ $page }}</a>
                        </li>
                    @endfor

                    {{-- Last page and dots --}}
                    @if ($endPage < $lastPage)
                        @if ($endPage < $lastPage - 1)
                            <li class="page-item disabled"><span class="page-link ">...</span></li>
                        @endif
                        <li class="page-item {{ $currentPage == $lastPage ? 'active' : '' }}">
                            <a class="page-link " href="{{ $paginator->url($lastPage) }}">{{ $lastPage }}</a>
                        </li>
                    @endif

                    {{-- Next --}}
                    <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link  px-3" href="{{ $paginator->nextPageUrl() }}">Next &raquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endif
