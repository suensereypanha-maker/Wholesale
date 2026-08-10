@props([
    'name',
    'label' => null,
    'value' => '1',
    'checked' => false,
    'description' => null,
    'disabled' => false,
    'required' => false,
    'id' => null,
    'color' => 'indigo',
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

    $fieldId = $id ?? ($name . '_switch');
    $oldVal = old($name);
    if ($oldVal !== null) {
        $isChecked = (bool)$oldVal;
    } else {
        $isChecked = (bool)$checked;
    }

    $colorClasses = match($color) {
        'emerald' => 'peer-checked:bg-emerald-600 focus-visible:ring-emerald-500',
        'blue' => 'peer-checked:bg-blue-600 focus-visible:ring-blue-500',
        'violet' => 'peer-checked:bg-violet-600 focus-visible:ring-violet-500',
        'rose' => 'peer-checked:bg-rose-600 focus-visible:ring-rose-500',
        'amber' => 'peer-checked:bg-amber-600 focus-visible:ring-amber-500',
        default => 'peer-checked:bg-indigo-600 focus-visible:ring-indigo-500',
    };
@endphp

@if($hasAccess || $permissionBehavior === 'disable')
    <div {{ $attributes->only('class')->merge(['class' => 'flex items-center justify-between py-1.5']) }}>
        @if($label || $description)
            <div class="flex-1 pr-4">
                @if($label)
                    <label for="{{ $fieldId }}" class="text-xs font-semibold text-slate-800 cursor-pointer block">
                        {{ $label }}
                        @if($required)
                            <span class="text-rose-500 font-bold ml-0.5">*</span>
                        @endif
                    </label>
                @endif
                @if($description)
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $description }}</p>
                @endif
            </div>
        @endif

        <label for="{{ $fieldId }}" class="relative inline-flex items-center cursor-pointer select-none">
            <input 
                type="checkbox" 
                name="{{ $name }}" 
                id="{{ $fieldId }}" 
                value="{{ $value }}"
                @if($isChecked) checked @endif
                @if($disabled) disabled @endif
                @if($required) required @endif
                {{ $attributes->except('class') }}
                class="sr-only peer"
            />
            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all {{ $colorClasses }} @if($disabled) opacity-50 cursor-not-allowed @endif"></div>
        </label>
    </div>
@endif
