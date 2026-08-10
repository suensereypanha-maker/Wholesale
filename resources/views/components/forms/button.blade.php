@props([
    'type' => 'submit',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'disabled' => false,
    'href' => null,
    'permission' => null,
    'role' => null,
    'permissionBehavior' => 'hide',
])

@php
    $user = auth()->user();
    $hasAccess = true;

    if ($permission) {
        if (!$user) {
            $hasAccess = false;
        } else {
            $isSuperAdmin = method_exists($user, 'hasRole') && $user->hasRole('Super Admin');
            $hasAccess = $isSuperAdmin || (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo($permission));
        }
    }

    if ($hasAccess && $role) {
        if (!$user) {
            $hasAccess = false;
        } else {
            $isSuperAdmin = method_exists($user, 'hasRole') && $user->hasRole('Super Admin');
            $hasAccess = $isSuperAdmin || (method_exists($user, 'hasRole') && $user->hasRole($role));
        }
    }

    if (!$hasAccess && $permissionBehavior === 'disable') {
        $disabled = true;
    }

    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-[11px] gap-1.5 rounded-lg',
        'lg' => 'px-6 py-3 text-sm gap-2.5 rounded-xl font-bold',
        default => 'px-4 py-2.5 text-xs gap-2 rounded-xl font-bold',
    };

    $variantClasses = match($variant) {
        'secondary' => 'bg-slate-100 hover:bg-slate-200 text-slate-700 shadow-2xs',
        'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs focus:ring-emerald-500/20',
        'danger' => 'bg-rose-600 hover:bg-rose-700 text-white shadow-xs focus:ring-rose-500/20',
        'warning' => 'bg-amber-500 hover:bg-amber-600 text-white shadow-xs focus:ring-amber-500/20',
        'dark' => 'bg-slate-900 hover:bg-slate-800 text-white shadow-xs',
        'outline' => 'bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 shadow-2xs',
        'ghost' => 'bg-transparent hover:bg-slate-100 text-slate-600',
        default => 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-xs focus:ring-indigo-500/20',
    };

    $classes = "inline-flex items-center justify-center font-bold transition-all duration-150 active:scale-95 focus:outline-none focus:ring-2 disabled:opacity-50 disabled:pointer-events-none cursor-pointer {$sizeClasses} {$variantClasses}";
@endphp

@if($hasAccess || $permissionBehavior === 'disable')
    @if($href)
        <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
            @if($icon && $iconPosition === 'left')
                <i class="{{ $icon }}"></i>
            @endif
            
            <span>{{ $slot }}</span>

            @if($icon && $iconPosition === 'right')
                <i class="{{ $icon }}"></i>
            @endif
        </a>
    @else
        <button 
            type="{{ $type }}" 
            @if($disabled) disabled @endif 
            {{ $attributes->merge(['class' => $classes]) }}
        >
            @if($icon && $iconPosition === 'left')
                <i class="{{ $icon }}"></i>
            @endif
            
            <span>{{ $slot }}</span>

            @if($icon && $iconPosition === 'right')
                <i class="{{ $icon }}"></i>
            @endif
        </button>
    @endif
@endif
