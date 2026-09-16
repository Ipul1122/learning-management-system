@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex gap-2 items-center justify-between py-2">
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-xl text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed opacity-50 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-500">
                &larr; {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-xl text-slate-700 bg-white border border-slate-300 hover:border-orange-500 hover:text-orange-500 transition shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700 dark:hover:text-orange-400">
                &larr; {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-xl text-slate-700 bg-white border border-slate-300 hover:border-orange-500 hover:text-orange-500 transition shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700 dark:hover:text-orange-400">
                {!! __('pagination.next') !!} &rarr;
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-xl text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed opacity-50 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-500">
                {!! __('pagination.next') !!} &rarr;
            </span>
        @endif
    </nav>
@endif
