@props([
    'name',
    'label' => 'Product Image',
    'value' => null,
    'preview' => null,
    'required' => false,
    'disabled' => false,
    'helpText' => 'Upload PNG, JPG, WEBP or SVG up to 4MB',
    'id' => null,
])

@php
    $fieldId = $id ?? $name;
    $hasError = isset($errors) && method_exists($errors, 'has') ? $errors->has($name) : false;
    $existingUrl = $preview ?? ($value ? (str_starts_with($value, 'http') ? $value : asset('storage/' . $value)) : null);
@endphp

<div class="w-full space-y-1.5" x-data="{
    imageUrl: '{{ $existingUrl }}',
    fileChosen(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => { this.imageUrl = e.target.result; };
        reader.readAsDataURL(file);
    },
    removeImage() {
        this.imageUrl = null;
        if (this.$refs.fileInput) { this.$refs.fileInput.value = ''; }
    }
}">
    @if($label)
        <label for="{{ $fieldId }}" class="block text-xs font-semibold text-slate-700 tracking-wide">
            {{ $label }}
            @if($required)
                <span class="text-rose-500 font-bold ml-0.5">*</span>
            @endif
        </label>
    @endif

    <div class="flex items-center gap-4 bg-slate-50/60 p-3 rounded-xl border border-slate-300 shadow-2xs hover:border-slate-400 transition-all">
        <!-- Live Image Preview Thumbnail -->
        <div class="relative w-20 h-20 rounded-lg bg-slate-200 border border-slate-300 flex items-center justify-center overflow-hidden shrink-0 group">
            <template x-if="imageUrl">
                <img :src="imageUrl" class="w-full h-full object-cover rounded-lg" alt="Product Image Preview" />
            </template>
            <template x-if="!imageUrl">
                <div class="flex flex-col items-center justify-center text-slate-400">
                    <i class="fas fa-image text-xl mb-1"></i>
                    <span class="text-[9px] font-medium text-slate-400">No Image</span>
                </div>
            </template>
            <button type="button" x-show="imageUrl" @click="removeImage()" class="absolute top-1 right-1 bg-rose-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] opacity-0 group-hover:opacity-100 transition-opacity shadow-xs" title="Remove image">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <!-- File Upload Input Area -->
        <div class="flex-1 space-y-1">
            <input 
                type="file" 
                name="{{ $name }}" 
                id="{{ $fieldId }}" 
                accept="image/*"
                x-ref="fileInput"
                @change="fileChosen($event)"
                @if($required) required @endif
                @if($disabled) disabled @endif
                class="block w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
            />
            <p class="text-[11px] text-slate-400">{{ $helpText }}</p>
        </div>
    </div>

    @error($name)
        <p class="text-[11px] font-medium text-rose-600 mt-1 flex items-center gap-1">
            <i class="fas fa-circle-exclamation text-[10px]"></i>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
