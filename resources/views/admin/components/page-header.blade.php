<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ $title ?? 'Dashboard' }}</h1>
        @if(!empty($subtitle))
            <p class="text-xs text-slate-500 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    @if(!empty($breadcrumbs))
        <nav class="flex items-center gap-2 text-xs text-slate-500">
            @foreach($breadcrumbs as $label => $url)
                @if(!$loop->first)
                    <span class="text-slate-300">/</span>
                @endif
                @if($url && $url !== '#')
                    <a href="{{ $url }}" class="hover:text-indigo-600 font-medium transition-colors">{{ $label }}</a>
                @else
                    <span class="text-slate-400">{{ $label }}</span>
                @endif
            @endforeach
        </nav>
    @endif
</div>
