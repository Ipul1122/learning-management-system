@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between py-3">
        {{-- Tampilan Ringkas untuk Layar Kecil (Mobile) --}}
        <div class="flex justify-between flex-1 sm:hidden items-center gap-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold rounded-xl text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed opacity-50 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-500">
                    &larr; {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold rounded-xl text-slate-700 bg-white border border-slate-300 hover:border-orange-500 hover:text-orange-500 transition shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700 dark:hover:text-orange-400">
                    &larr; {!! __('pagination.previous') !!}
                </a>
            @endif

            <span class="text-xs font-medium text-slate-500 dark:text-slate-400 font-mono">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold rounded-xl text-slate-700 bg-white border border-slate-300 hover:border-orange-500 hover:text-orange-500 transition shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700 dark:hover:text-orange-400">
                    {!! __('pagination.next') !!} &rarr;
                </a>
            @else
                <span class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold rounded-xl text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed opacity-50 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-500">
                    {!! __('pagination.next') !!} &rarr;
                </span>
            @endif
        </div>

        {{-- Tampilan Lengkap untuk Layar Desktop --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">
                    {!! __('Showing') !!}
                    <span class="font-bold text-slate-700 dark:text-slate-200">{{ $paginator->firstItem() ?? 0 }}</span>
                    {!! __('to') !!}
                    <span class="font-bold text-slate-700 dark:text-slate-200">{{ $paginator->lastItem() ?? 0 }}</span>
                    {!! __('of') !!}
                    <span class="font-bold text-slate-700 dark:text-slate-200">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <ul class="inline-flex items-center gap-1.5 list-none p-0 m-0">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-semibold rounded-xl text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed opacity-50 dark:bg-slate-800/60 dark:border-slate-700/60 dark:text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-8 h-8 text-xs font-semibold rounded-xl text-slate-700 bg-white border border-slate-300 hover:border-orange-500 hover:text-orange-500 transition shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-orange-400" aria-label="{{ __('pagination.previous') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li aria-disabled="true">
                                <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-medium text-slate-400 dark:text-slate-500">
                                    {{ $element }}
                                </span>
                            </li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li aria-current="page">
                                        <span class="inline-flex items-center justify-center min-w-[2rem] h-8 px-2.5 text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 rounded-xl shadow-md shadow-orange-500/20 border border-orange-500">
                                            {{ $page }}
                                        </span>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $url }}" class="inline-flex items-center justify-center min-w-[2rem] h-8 px-2.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:border-orange-500 hover:text-orange-500 rounded-xl transition shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-orange-400" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                            {{ $page }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li>
                            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-8 h-8 text-xs font-semibold rounded-xl text-slate-700 bg-white border border-slate-300 hover:border-orange-500 hover:text-orange-500 transition shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-orange-400" aria-label="{{ __('pagination.next') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        </li>
                    @else
                        <li aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-semibold rounded-xl text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed opacity-50 dark:bg-slate-800/60 dark:border-slate-700/60 dark:text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
