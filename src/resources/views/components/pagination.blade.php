@props([
    'paginationRoute',
    'paginationRouteParams' => [],
    'meta' => [],
    'currentPerPage' => 10,
    'allowedPerPage' => [10, 20, 30, 50],
])

@php
    $currentPage = (int) ($meta['current_page'] ?? 1);
    $lastPage = (int) ($meta['last_page'] ?? 1);
    $total = (int) ($meta['total'] ?? 0);
    $baseUrl = route($paginationRoute, $paginationRouteParams);
@endphp

@if($total > 0)
    <div class="mt-6 pt-6 border-t border-gray-200 space-y-4">
        {{-- Per page selector --}}
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-sm text-gray-600">{{ __('general.per_page') }}</span>
            @foreach($allowedPerPage as $num)
                @php
                    $url = $baseUrl . '?' . http_build_query(['per_page' => $num, 'page' => 1]);
                @endphp
                <a href="{{ $url }}" class="inline-flex items-center px-3 py-1.5 rounded text-sm font-medium {{ $num === $currentPerPage ? 'bg-sky-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-sky-100 hover:text-sky-800' }}">
                    {{ $num }}
                </a>
            @endforeach
        </div>

        {{-- Page list --}}
        @if($lastPage > 1)
            <nav class="flex flex-wrap items-center gap-1" aria-label="{{ __('general.pagination') }}">
                @for($p = 1; $p <= $lastPage; $p++)
                    @php
                        $pageUrl = $baseUrl . '?' . http_build_query(['per_page' => $currentPerPage, 'page' => $p]);
                    @endphp
                    <a href="{{ $pageUrl }}" class="inline-flex items-center justify-center min-w-[2.25rem] px-2 py-1.5 rounded text-sm font-medium {{ $p === $currentPage ? 'bg-sky-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-sky-100 hover:text-sky-800' }}">
                        {{ $p }}
                    </a>
                @endfor
            </nav>
        @endif
    </div>
@endif
