<div
    x-data="{
        dragOver: false,
        multiple: @js($multiple),
        files: [],
        updatePreview(files) {
            this.files = Array.from(files).map(file => ({
                name: file.name,
                size: file.size,
                type: file.type,
                preview: file.type.startsWith('image/') ? URL.createObjectURL(file) : null
            }));
        }
    }"
    class="relative"
>
    <div
        @dragover.prevent="dragOver = true"
        @dragleave.prevent="dragOver = false"
        @drop.prevent="dragOver = false; updatePreview($event.dataTransfer.files); $wire.uploadMultiple('files', $event.dataTransfer.files)"
        :class="{ 'border-indigo-500 bg-indigo-50': dragOver }"
        class="border-2 border-dashed rounded-lg p-6 text-center"
    >
        <input
            type="file"
            class="sr-only"
            wire:model="files"
            :multiple="multiple"
            :accept="@js($accept)"
            @change="updatePreview($event.target.files)"
        >

        <div class="space-y-2">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="text-sm text-gray-600">
                <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                    <span>Upload files</span>
                </label>
                <p class="pl-1">or drag and drop</p>
            </div>
        </div>
    </div>

    {{-- Preview Grid --}}
    <div class="mt-4 grid grid-cols-4 gap-4">
        <template x-for="file in files" :key="file.name">
            <div class="relative group">
                <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden bg-gray-100">
                    <template x-if="file.preview">
                        <img :src="file.preview" class="object-cover">
                    </template>
                    <template x-if="!file.preview">
                        <div class="flex items-center justify-center h-full">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </template>
                </div>
                <div class="mt-2 text-sm text-gray-500 truncate" x-text="file.name"></div>
            </div>
        </template>
    </div>
</div>
