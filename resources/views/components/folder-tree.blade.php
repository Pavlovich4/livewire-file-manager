<div class="space-y-1">
    @foreach($folders as $folder)
        <div
            wire:key="folder-{{ $folder->id }}"
            class="relative"
        >
            <div class="group flex items-center py-1 px-2 rounded-md hover:bg-gray-50">
                <button
                    type="button"
                    wire:click="toggleFolder({{ $folder->id }})"
                    class="mr-1 w-4 h-4 text-gray-400"
                >
                    <svg class="transform transition-transform" :class="{ 'rotate-90': @js(in_array($folder->id, $expandedFolders)) }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div class="flex-1 flex items-center cursor-pointer" wire:click="navigateToFolder({{ $folder->id }})">
                    <svg
                        class="w-5 h-5 text-gray-400 mr-2 transition-transform"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        @if(in_array($folder->id, $expandedFolders))
                            {{-- Open folder icon --}}
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2zm0 2v8a2 2 0 002 2h14a2 2 0 002-2V9H5a2 2 0 00-2 2z" />
                        @else
                            {{-- Closed folder icon --}}
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        @endif
                    </svg>

                    @if($editingName === $folder->id && $editingType === 'folder')
                        <input
                            type="text"
                            wire:model.defer="newFolderName"
                            wire:keydown.enter="renameFolder({{ $folder->id }})"
                            wire:blur="stopEditing"
                            class="flex-1 px-2 py-1 text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-gray-900"
                            @click.stop
                            @click.outside="$wire.stopEditing()"
                        >
                    @else
                        <span
                            class="flex-1 text-sm text-gray-700 cursor-pointer"
                            @dblclick.stop="$wire.startEditing({{ $folder->id }}, 'folder')"
                        >
                            {{ $folder->name }}
                        </span>
                    @endif
                </div>

                <button
                    wire:click.stop="deleteFolder({{ $folder->id }})"
                    class="p-1 opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-500"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>

            @if(in_array($folder->id, $expandedFolders))
                <div class="ml-6 mt-1">
                    @include('livewire-filemanager::components.folder-tree', ['folders' => $folder->children])
                </div>
            @endif
        </div>
    @endforeach
</div>
