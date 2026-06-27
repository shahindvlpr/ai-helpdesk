<!-- resources/views/components/alert.blade.php -->
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
     class="fixed top-4 right-4 z-50 max-w-sm w-full">
    <div class="bg-{{ $type ?? 'blue' }}-100 border border-{{ $type ?? 'blue' }}-400 text-{{ $type ?? 'blue' }}-700 px-4 py-3 rounded shadow-lg">
        {{ $slot }}
        <button @click="show = false" class="float-right">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>