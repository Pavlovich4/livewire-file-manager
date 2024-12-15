

<div
    x-data="{
        showModal: @entangle('showModal'),
        showNewFolderModal: @entangle('showNewFolderModal'),
        showFilePreview: @entangle('showFilePreview'),
        dragOver: false,
        isGrid: @entangle('isGrid'),
    }"
    @keydown.escape.window="showModal = false"
    class="relative dark-theme dark-mode-transition"
    @open-filemanager.window="showModal = true"
>


    {{-- Modal --}}
    <div
        x-show="showModal"
        x-cloak
        class="fixed inset-0 z-40 overflow-y-auto bg-opacity-75 bg-gray-400"
    >
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-[var(--bg-primary)] rounded-xl shadow-2xl w-full max-w-6xl max-h-[80vh] overflow-hidden border border-[var(--border-color)]">
                {{-- Modal Header --}}
                <div class="px-6 py-4 border-b border-[var(--border-color)] flex justify-between items-center bg-[var(--bg-secondary)]">
                    <h3 class="text-lg font-medium text-[var(--text-primary)]">File Manager</h3>
                    <button
                        @click="showModal = false"
                        class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-colors shadow-inner hover:shadow-gray-900 rounded-full p-1"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Content --}}
                <div class="flex h-[calc(80vh-4rem)]">
                    {{-- Sidebar --}}
                    <div class="w-72 bg-white border-r border-gray-200 overflow-y-auto">
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-gray-900">Folders</h3>
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
                                                        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        New Folder
                                    </button>

                                    <button
                                        wire:click="toggleView"
                                        class="p-2 text-gray-400 hover:text-gray-500 rounded-md shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),inset_0_-1px_1px_rgba(0,0,0,0.05)] hover:shadow-[inset_0_1px_1px_rgba(255,255,255,0.15),inset_0_-1px_1px_rgba(0,0,0,0.1)] bg-white transition-all duration-200"
                                    >
                                        <template x-if="isGrid">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                            </svg>
                                        </template>
                                        <template x-if="!isGrid">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
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
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">
                                        <span class="font-medium text-indigo-600 hover:text-indigo-500">
                                            Click to upload
                                        </span>
                                        or drag and drop
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Up to 100MB per file
                                    </p>
                                </label>
                            </div>

                            {{-- Files Grid/List --}}
                            {{-- In file-manager.blade.php, update the files section --}}
{{-- Files Grid/List --}}
<div :class="{ 'space-y-2': !isGrid, 'grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4': isGrid }">
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
                            <x-livewire-filemanager::file-icon :type="$this->getFileIcon($file->mime_type)" />
                        </div>
                    @endif
                </div>
            @else
                <div class="flex-shrink-0 w-12 h-12 mr-4">
                    @if(Str::startsWith($file->mime_type, 'image/'))
                        <img src="{{ $file->thumbnail_url }}" class="w-full h-full object-cover rounded">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100 rounded">
                            <x-livewire-filemanager::file-icon :type="$this->getFileIcon($file->mime_type)" />
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
<div
    x-show="showFilePreview"
    class="fixed z-[70] inset-0 overflow-y-auto"
    x-transition
>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            @click="showFilePreview = false"
        ></div>

        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-3xl">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">File Details</h3>
                <button
                    @click="showFilePreview = false"
                    class="text-gray-400 hover:text-gray-500"
                >
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @if($selectedFile)
            <div class="p-6">
                <div class="flex space-x-6">
                    <div class="w-1/3">
                        @if(Str::startsWith($selectedFile->mime_type, 'image/'))
                            <img
                                src="{{ $selectedFile->getFirstMediaUrl() }}"
                                class="w-full rounded-lg shadow-lg"
                                alt="{{ $selectedFile->name }}"
                            >
                        @else
                            <div class="w-full aspect-square flex items-center justify-center bg-gray-100 rounded-lg">
                                <x-livewire-filemanager::file-icon
                                    :type="$this->getFileIcon($selectedFile->mime_type)"
                                    class="w-20 h-20"
                                />
                            </div>
                        @endif
                    </div>

                    <div class="w-2/3 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedFile->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedFile->mime_type }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Size</label>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($selectedFile->size / 1024, 2) }} KB</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Created</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedFile->created_at->format('M d, Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Share Link</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input
                                    type="text"
                                    value="{{ $selectedFile->getFirstMediaUrl() }}"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border-gray-300 text-sm"
                                    readonly
                                >
                                <button
                                    type="button"
                                    @click="navigator.clipboard.writeText('{{ $selectedFile->getFirstMediaUrl() }}')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Copy
                                </button>
                            </div>
                        </div>

                        <div class="flex space-x-3 pt-4">
                            <button
                                wire:click="downloadFile({{ $selectedFile->id }})"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 shadow-inner"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </button>

                            <button
                                wire:click="deleteFile({{ $selectedFile->id }})"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 shadow-inner"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- New Folder Modal --}}
    <div
        x-show="showNewFolderModal"
        class="fixed z-[60] inset-0 overflow-y-auto"
        x-transition
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div
                x-show="showNewFolderModal"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="showNewFolderModal = false"
            ></div>

            <div
                x-show="showNewFolderModal"
                class="relative inline-block bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
            >
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">New Folder</h3>
                        <div class="mt-2">
                            <input
                                type="text"
                                wire:model="newFolderName"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md text-gray-900"
                                placeholder="Folder name"
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
                        Create
                    </button>
                    <button
                        @click="showNewFolderModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-inner px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div
        x-show="$wire.showDeleteConfirmation"
        class="fixed z-[70] inset-0 overflow-y-auto"
        x-transition
    >
        <div class="flex items-center justify-center min-h-screen p-4">
            <div
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="$wire.cancelDelete()"
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
                                Delete Folder
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    @if($folderToDelete)
                                        Are you sure you want to delete "{{ $folderToDelete['name'] }}"? This folder contains:
                                        <ul class="list-disc list-inside mt-2">
                                            @if($folderToDelete['filesCount'] > 0)
                                                <li>{{ $folderToDelete['filesCount'] }} file(s)</li>
                                            @endif
                                            @if($folderToDelete['foldersCount'] > 0)
                                                <li>{{ $folderToDelete['foldersCount'] }} subfolder(s)</li>
                                            @endif
                                        </ul>
                                        <p class="mt-2 font-medium">This action cannot be undone.</p>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button
                            wire:click="confirmDelete"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Delete
                        </button>
                        <button
                            wire:click="cancelDelete"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
