@php
    $statusMap = [
        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'processing' => 'bg-amber-50 text-amber-700 border-amber-200',
        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
        'low_stock' => 'bg-rose-50 text-rose-700 border-rose-200',
        'critical' => 'bg-rose-50 text-rose-700 border-rose-200',
        'cancelled' => 'bg-slate-100 text-slate-700 border-slate-200',
    ];
    $badgeClass = $statusMap[strtolower($status ?? '')] ?? 'bg-slate-100 text-slate-700 border-slate-200';
@endphp
<span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border capitalize inline-block {{ $badgeClass }}">
    {{ str_replace('_', ' ', $status ?? 'unknown') }}
</span>
