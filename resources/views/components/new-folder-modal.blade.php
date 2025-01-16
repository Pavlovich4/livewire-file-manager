<div
        x-show="showNewFolderModal"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 backdrop-blur-none"
        x-transition:enter-end="opacity-100 backdrop-blur-sm"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 backdrop-blur-sm"
        x-transition:leave-end="opacity-0 backdrop-blur-none"
        class="fixed z-[60] inset-0 overflow-y-auto bg-gray-500/50 backdrop-blur-sm"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div
                x-show="showNewFolderModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="showNewFolderModal = false"
            ></div>

            <div
                x-show="showNewFolderModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
            >
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('filemanager.new_folder') }}</h3>
                        <div class="mt-2">
                            <input
                                type="text"
                                wire:model="newFolderName"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md text-gray-900"
                                placeholder="{{ __('filemanager.folder_name') }}"
                                @keydown.enter="$wire.createFolder()"
                            >
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button
                        wire:click="createFolder"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-inner px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        {{ __('filemanager.create') }}
                    </button>
                    <button
                        @click="showNewFolderModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-inner px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm"
                    >
                        {{ __('filemanager.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
