@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'icon' => null,
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'helpText' => null,
    'errorName' => null,
    'id' => null,
    'keyBy' => 'id',
    'labelBy' => 'name',
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
    $errorKey = $errorName ?? Str::replace('[]', '', $name);
    $hasError = $errors->has($errorKey);
    $selectedValue = old($errorKey, $selected);

    if ($multiple && is_string($selectedValue)) {
        $selectedValue = [$selectedValue];
    }
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
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i class="{{ $icon }} text-xs"></i>
                </div>
            @endif

            <select
                name="{{ $name }}"
                id="{{ $fieldId }}"
                @if($multiple) multiple @endif
                @if($required) required @endif
                @if($disabled) disabled @endif
                {{ $attributes->except('class') }}
                class="w-full text-xs text-slate-800 rounded-xl transition-all duration-150 py-2.5 appearance-none
                @if($icon) pl-9 @else pl-3.5 @endif pr-9 
                @if($disabled) bg-slate-100 text-slate-400 cursor-not-allowed border-slate-300 @else bg-white hover:border-slate-400 focus:bg-white @endif 
                @if($hasError) 
                    border-2 border-rose-400 text-rose-900 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 bg-rose-50/30
                @else 
                    border border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs
                @endif"
            >
                @if($placeholder)
                    <option value="" @if($selectedValue === null || $selectedValue === '') selected @endif disabled>
                        {{ $placeholder }}
                    </option>
                @endif

                @if($slot->isNotEmpty())
                    {{ $slot }}
                @else
                    @foreach($options as $optKey => $optVal)
                        @php
                            if (is_object($optVal)) {
                                $val = data_get($optVal, $keyBy, $optKey);
                                $lbl = data_get($optVal, $labelBy, (string)$optVal);
                            } elseif (is_array($optVal)) {
                                $val = data_get($optVal, $keyBy, $optKey);
                                $lbl = data_get($optVal, $labelBy, implode(' - ', $optVal));
                            } else {
                                $val = is_numeric($optKey) && !is_string($optKey) ? $optVal : $optKey;
                                $lbl = $optVal;
                            }

                            $isSelected = false;
                            if ($multiple && is_array($selectedValue)) {
                                $isSelected = in_array((string)$val, array_map('strval', $selectedValue));
                            } else {
                                $isSelected = (string)$selectedValue === (string)$val;
                            }
                        @endphp

                        <option value="{{ $val }}" @if($isSelected) selected @endif>
                            {{ $lbl }}
                        </option>
                    @endforeach
                @endif
            </select>

            @if(!$multiple)
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-chevron-down text-[10px]"></i>
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
