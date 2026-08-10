@props([
    'action' => '',
    'method' => 'POST',
    'hasFiles' => false,
    'csrf' => true,
    'permission' => null,
    'role' => null,
    'footer' => null,
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

    $methodUpper = strtoupper($method);
    $realMethod = in_array($methodUpper, ['GET', 'POST']) ? $methodUpper : 'POST';
    $spoofMethod = !in_array($methodUpper, ['GET', 'POST']) ? $methodUpper : null;
@endphp

@if($hasAccess)
    <form 
        action="{{ $action }}" 
        method="{{ $realMethod }}"
        @if($hasFiles) enctype="multipart/form-data" @endif
        {{ $attributes->merge(['class' => 'space-y-5']) }}
    >
        @if($csrf && $realMethod !== 'GET')
            @csrf
        @endif

        @if($spoofMethod)
            @method($spoofMethod)
        @endif

        {{ $slot }}

        @if(isset($footer) && $footer->isNotEmpty())
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                {{ $footer }}
            </div>
        @endif
    </form>
@endif
