@props([
    'title' => null,
    'description' => null,
    'icon' => null,
    'footer' => null,
    'permission' => null,
    'role' => null,
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
@endphp

@if($hasAccess)
    <div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden w-full']) }}>
        @if($title || $description)
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/40 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if($icon)
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold shadow-2xs">
                            <i class="{{ $icon }}"></i>
                        </div>
                    @endif
                    <div>
                        @if($title)
                            <h3 class="text-sm font-bold text-slate-900 tracking-tight">{{ $title }}</h3>
                        @endif
                        @if($description)
                            <p class="text-xs text-slate-500 mt-0.5">{{ $description }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="p-6 space-y-5">
            {{ $slot }}
        </div>

        @if(isset($footer) && $footer->isNotEmpty())
            <div class="px-6 py-4 bg-slate-50/60 border-t border-slate-100 flex items-center justify-end gap-3">
                {{ $footer }}
            </div>
        @endif
    </div>
@endif
