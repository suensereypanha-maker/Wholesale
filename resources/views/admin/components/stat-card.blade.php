<div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
    <div>
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $title ?? '' }}</p>
        <h3 class="text-xl font-bold text-slate-900 mt-1">{{ $value ?? 0 }}</h3>
        @if(isset($change))
            <div class="flex items-center gap-1 mt-1 text-xs">
                <span class="{{ ($isPositive ?? true) ? 'text-emerald-600' : 'text-rose-600' }} font-bold flex items-center gap-0.5">
                    <i class="fas fa-arrow-{{ ($isPositive ?? true) ? 'up' : 'down' }} text-[10px]"></i>
                    {{ $change }}
                </span>
                <span class="text-slate-400 text-[11px]">{{ $period ?? 'vs last month' }}</span>
            </div>
        @endif
    </div>
    @if(!empty($icon))
        <div class="w-12 h-12 rounded-xl {{ $bg ?? 'bg-indigo-50 text-indigo-600' }} flex items-center justify-center text-lg font-bold">
            <i class="{{ $icon }}"></i>
        </div>
    @endif
</div>
