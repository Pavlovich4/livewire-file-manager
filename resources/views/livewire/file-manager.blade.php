<div
    x-data="{
        showModal: @entangle('showModal'),
        showNewFolderModal: @entangle('showNewFolderModal'),
        showFilePreview: @entangle('showFilePreview'),
        dragOver: false,
        isGrid: @entangle('isGrid'),
        isUploading: @entangle('isUploading')
    }"
    @keydown.escape.window="showModal = false"
    class="relative dark-theme dark-mode-transition"
    @open-filemanager.window="showModal = true"
>
    {{-- Loading Spinner --}}
    <x-livewire-filemanager::loading-spinner/>

    {{-- Modal --}}
    <div
        x-show="showModal"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 backdrop-blur-none"
        x-transition:enter-end="opacity-100 backdrop-blur-sm"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 backdrop-blur-sm"
        x-transition:leave-end="opacity-0 backdrop-blur-none"
        class="fixed inset-0 z-40 overflow-y-auto bg-gray-400/50 backdrop-blur-sm"
    >
        <div class="flex items-center justify-center min-h-screen p-4">
            <div
                x-show="showModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-[var(--bg-primary)] rounded-xl shadow-2xl w-full max-w-6xl max-h-[80vh] overflow-hidden border border-[var(--border-color)]"
            >
                {{-- Modal Header --}}
                <div
                    class="px-6 py-4 border-b border-[var(--border-color)] flex justify-between items-center bg-[var(--bg-secondary)]">
                    <h3 class="text-lg font-medium text-[var(--text-primary)]">{{ __('filemanager.title') }}</h3>
                    <button
                        @click="showModal = false"
                        class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-colors shadow-inner hover:shadow-gray-900 rounded-full p-1"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Content --}}
                <div class="flex h-[calc(80vh-4rem)]">
                    {{-- Sidebar --}}
                    <div class="w-72 bg-white border-r border-gray-200 overflow-y-auto">
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('filemanager.folders') }}</h3>
                            <div class="mt-4">
                                @include('livewire-filemanager::components.folder-tree', ['folders' => $rootFolders])
                            </div>
                        </div>
                    </div>

                    {{-- Main Content --}}
                    <div class="flex-1 flex flex-col bg-gray-50 overflow-hidden">
                        {{-- Toolbar --}}
                        <div class="bg-white border-b border-gray-200 px-4 py-3">
                            <div class="flex justify-between items-center">
                                {{-- Breadcrumbs --}}
                                <nav class="flex" aria-label="Breadcrumb">
                                    <ol class="flex items-center space-x-4">
                                        <li>
                                            <button
                                                wire:click="navigateToFolder()"
                                                class="text-sm font-medium text-gray-500 hover:text-gray-700"
                                            >
                                                Root
                                            </button>
                                        </li>
                                        @foreach($breadcrumbs as $breadcrumb)
                                            @if($breadcrumb['id'] !== null)
                                                <li>
                                                    <div class="flex items-center">
                                                        <svg class="flex-shrink-0 h-5 w-5 text-gray-400"
                                                             fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                                  clip-rule="evenodd"/>
                                                        </svg>
                                                        <button
                                                            wire:click="navigateToFolder({{ $breadcrumb['id'] }})"
                                                            class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
                                                        >
                                                            {{ $breadcrumb['name'] }}
                                                        </button>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ol>
                                </nav>

                                {{-- Actions --}}
                                <div class="flex items-center space-x-4">
                                    <button
                                        wire:click="$set('showNewFolderModal', true)"
                                        class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm transition-colors duration-200 ease-in-out shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),inset_0_-1px_1px_rgba(0,0,0,0.1)] hover:shadow-[inset_0_1px_1px_rgba(255,255,255,0.15),inset_0_-1px_1px_rgba(0,0,0,0.15)]"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 4v16m8-8H4"/>
                                        </svg>
                                        {{ __('filemanager.new_folder') }}
                                    </button>

                                    <button
                                        wire:click="toggleView"
                                        class="p-2 text-gray-400 hover:text-gray-500 rounded-md shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),inset_0_-1px_1px_rgba(0,0,0,0.05)] hover:shadow-[inset_0_1px_1px_rgba(255,255,255,0.15),inset_0_-1px_1px_rgba(0,0,0,0.1)] bg-white transition-all duration-200"
                                    >
                                        <template x-if="isGrid">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                            </svg>
                                        </template>
                                        <template x-if="!isGrid">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                                            </svg>
                                        </template>
                                    </button>
                                </div>
                            </div>
                        </div>


                        {{-- Content Area --}}
                        <div class="flex-1 overflow-y-auto p-4">
                            {{-- Drop Zone --}}
                            <div
                                @dragover.prevent="dragOver = true"
                                @dragleave.prevent="dragOver = false"
                                @drop.prevent="dragOver = false; $wire.handleFileUpload($event.dataTransfer.files)"
                                :class="{ 'border-[var(--accent-color)] bg-opacity-10 bg-[var(--accent-color)]': dragOver }"
                                class="border-2 border-dashed border-[var(--border-color)] rounded-lg p-6 text-center mb-6 transition-colors duration-200 hover:border-[var(--accent-color)]"
                            >
                                <input
                                    type="file"
                                    wire:model="uploadedFiles"
                                    class="hidden"
                                    multiple
                                    id="file-upload"
                                    @change="$wire.handleFileUpload()"
                                >
                                <label
                                    for="file-upload"
                                    class="cursor-pointer"
                                >
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                         viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">
                                        <span class="font-medium text-indigo-600 hover:text-indigo-500">
                                            {{ __('filemanager.upload') }}
                                        </span>
                                        {{ __('filemanager.drag_drop') }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ __('filemanager.file_size_limit') }}
                                    </p>
                                </label>
                            </div>

                            {{-- Files Grid/List --}}
                            <div
                                :class="{ 'space-y-2': !isGrid, 'grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4': isGrid }">
                                @foreach($files as $file)
                                    <div
                                        wire:key="file-{{ $file->id }}"
                                        class="relative group"
                                        :class="{ 'flex items-center p-2 hover:bg-gray-50 rounded-lg': !isGrid }"
                                        @click="$wire.selectFile({{ $file->id }})"
                                    >
                                        @if($isGrid)
                                            <div class="aspect-w-10 aspect-h-7 rounded-lg overflow-hidden">
                                                @if(Str::startsWith($file->mime_type, 'image/'))
                                                    <img src="{{ $file->thumbnail_url }}" class="object-cover">
                                                @else
                                                    <div class="flex items-center justify-center bg-gray-100">
                                                        <x-livewire-filemanager::file-icon
                                                            :type="$this->getFileIcon($file->mime_type)"/>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="flex-shrink-0 w-12 h-12 mr-4">
                                                @if(Str::startsWith($file->mime_type, 'image/'))
                                                    <img src="{{ $file->thumbnail_url }}"
                                                         class="w-full h-full object-cover rounded">
                                                @else
                                                    <div
                                                        class="w-full h-full flex items-center justify-center bg-gray-100 rounded">
                                                        <x-livewire-filemanager::file-icon
                                                            :type="$this->getFileIcon($file->mime_type)"/>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="flex-1 min-w-0">
                                            @if($editingName === $file->id && $editingType === 'file')
                                                <input
                                                    type="text"
                                                    wire:model.defer="editingFileName"
                                                    wire:keydown.enter="renameFile({{ $file->id }}, $event.target.value)"
                                                    wire:blur="stopEditing"
                                                    class="block w-full px-2 py-1 text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                                    @click.stop
                                                >
                                            @else
                                                <p
                                                    class="text-sm font-medium text-gray-900 truncate"
                                                    @dblclick.stop="$wire.startEditing({{ $file->id }}, 'file')"
                                                >
                                                    {{ $file->name }}
                                                </p>
                                            @endif
                                            <p class="text-sm text-gray-500">
                                                {{ number_format($file->size / 1024, 2) }} KB
                                                · {{ $file->mime_type }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- File Preview Modal --}}
                            <x-livewire-filemanager::file-preview-modal :selectedFile="$selectedFile"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- New Folder Modal --}}
    <x-livewire-filemanager::new-folder-modal/>

    {{-- Delete Folder Confirmation Modal --}}
    <x-livewire-filemanager::folder-delete-confirmation-modal :folderToDelete="$folderToDelete"/>

    {{-- File Delete Confirmation Modal --}}
    <x-livewire-filemanager::file-delete-confirmation-modal :fileToDelete="$fileToDelete"/>
</div>
