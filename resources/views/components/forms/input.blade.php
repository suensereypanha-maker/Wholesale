@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'icon' => null,
    'rightIcon' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'helpText' => null,
    'errorName' => null,
    'id' => null,
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

    $fieldId = $id ?? $name;
    $errorKey = $errorName ?? $name;
    $hasError = isset($errors) && method_exists($errors, 'has') ? $errors->has($errorKey) : false;
    $inputValue = old($name, $value);
@endphp

@if($hasAccess || $permissionBehavior === 'disable')
    <div {{ $attributes->only('class')->merge(['class' => 'w-full space-y-1.5']) }}>
        @if($label)
            <label for="{{ $fieldId }}" class="block text-xs font-semibold text-slate-700 tracking-wide">
                {{ $label }}
                @if($required)
                    <span class="text-rose-500 font-bold ml-0.5">*</span>
                @endif
            </label>
        @endif

        <div class="relative rounded-xl shadow-2xs">
            @if($icon)
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="{{ $icon }} text-xs"></i>
                </div>
            @endif

            <input 
                type="{{ $type }}"
                name="{{ $name }}"
                id="{{ $fieldId }}"
                value="{{ $inputValue }}"
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                @if($required) required @endif
                @if($disabled) disabled @endif
                @if($readonly) readonly @endif
                {{ $attributes->except('class') }}
                class="w-full text-xs text-slate-800 rounded-xl transition-all duration-150 py-2.5 
                @if($icon) pl-9 @else pl-3.5 @endif 
                @if($rightIcon) pr-9 @else pr-3.5 @endif 
                @if($disabled) bg-slate-100 text-slate-400 cursor-not-allowed border-slate-300 @else bg-white hover:border-slate-400 focus:bg-white @endif 
                @if($hasError) 
                    border-2 border-rose-400 text-rose-900 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 bg-rose-50/30
                @else 
                    border border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs
                @endif"
            />

            @if($rightIcon)
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="{{ $rightIcon }} text-xs"></i>
                </div>
            @endif
        </div>

        @if($helpText && !$hasError)
            <p class="text-[11px] text-slate-400 mt-1">{{ $helpText }}</p>
        @endif

        @error($errorKey)
            <p class="text-[11px] font-medium text-rose-600 mt-1 flex items-center gap-1">
                <i class="fas fa-circle-exclamation text-[10px]"></i>
                <span>{{ $message }}</span>
            </p>
        @enderror
    </div>
@endif
