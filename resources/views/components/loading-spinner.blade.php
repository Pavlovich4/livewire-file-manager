<div
    x-show="isUploading"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm"
>
    <div class="relative">
        <div class="w-16 h-16">
            <div class="absolute w-16 h-16 border-4 border-solid border-gray-200 rounded-full"></div>
            <div class="absolute w-16 h-16 border-4 border-solid border-indigo-600 rounded-full animate-spin border-t-transparent"></div>
        </div>
        <div class="mt-4 text-center">
            <span class="text-sm font-medium text-white">{{ __('filemanager.uploading') }}</span>
        </div>
    </div>
</div>
