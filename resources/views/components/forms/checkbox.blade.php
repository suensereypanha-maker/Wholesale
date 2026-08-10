@props([
    'name',
    'label' => null,
    'value' => '1',
    'checked' => false,
    'description' => null,
    'required' => false,
    'disabled' => false,
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

    $fieldId = $id ?? ($name . '_' . Str::slug($value));
    $errorKey = $errorName ?? Str::replace('[]', '', $name);
    $hasError = $errors->has($errorKey);
    
    $oldVal = old($errorKey);
    if ($oldVal !== null) {
        $isChecked = is_array($oldVal) ? in_array((string)$value, array_map('strval', $oldVal)) : (bool)$oldVal;
    } else {
        $isChecked = (bool)$checked;
    }
@endphp

@if($hasAccess || $permissionBehavior === 'disable')
    <div {{ $attributes->only('class')->merge(['class' => 'relative flex items-start py-1']) }}>
        <div class="flex items-center h-5">
            <input
                type="checkbox"
                name="{{ $name }}"
                id="{{ $fieldId }}"
                value="{{ $value }}"
                @if($isChecked) checked @endif
                @if($required) required @endif
                @if($disabled) disabled @endif
                {{ $attributes->except('class') }}
                class="w-4 h-4 text-indigo-600 rounded-md border-slate-300 focus:ring-indigo-500 focus:ring-2 transition-all cursor-pointer disabled:cursor-not-allowed disabled:opacity-50"
            />
        </div>

        @if($label || $description)
            <div class="ml-3 text-xs leading-normal select-none">
                @if($label)
                    <label for="{{ $fieldId }}" class="font-medium text-slate-800 cursor-pointer flex items-center gap-1">
                        {{ $label }}
                        @if($required)
                            <span class="text-rose-500 font-bold">*</span>
                        @endif
                    </label>
                @endif

                @if($description)
                    <p class="text-[11px] text-slate-400 font-normal mt-0.5">{{ $description }}</p>
                @endif
            </div>
        @endif

        @error($errorKey)
            <p class="text-[11px] font-medium text-rose-600 mt-1 block w-full">
                <i class="fas fa-circle-exclamation text-[10px]"></i>
                <span>{{ $message }}</span>
            </p>
        @enderror
    </div>
@endif
