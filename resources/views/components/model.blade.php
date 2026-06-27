<!-- resources/views/components/modal.blade.php -->
<div x-data="{ open: false }" x-on:open-modal.window="open = true" x-on:close-modal.window="open = false">
    <div x-show="open" x-transition class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="open = false"></div>
            
            <div x-show="open" x-transition.scale class="relative bg-white rounded-lg max-w-lg w-full">
                <div class="p-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>