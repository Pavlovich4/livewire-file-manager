<div
x-show="$wire.showFileDeleteConfirmation"
x-cloak
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0 backdrop-blur-none"
x-transition:enter-end="opacity-100 backdrop-blur-sm"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100 backdrop-blur-sm"
x-transition:leave-end="opacity-0 backdrop-blur-none"
class="fixed inset-0 z-[70] overflow-y-auto bg-gray-500/50 backdrop-blur-sm"
>
<div class="flex items-center justify-center min-h-screen p-4">
    <div
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        @click="$wire.cancelFileDelete()"
    ></div>

    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="p-6">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ __('filemanager.delete_file') }}
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            @if($fileToDelete)
                                {{ __('filemanager.delete_file_confirm', ['name' => $fileToDelete->name]) }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button
                    wire:click="confirmFileDelete"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    {{ __('filemanager.delete') }}
                </button>
                <button
                    wire:click="cancelFileDelete"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                >
                    {{ __('filemanager.cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>
</div>
