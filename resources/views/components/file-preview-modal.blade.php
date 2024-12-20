@props(['selectedFile'])
<div
    x-show="showFilePreview"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 backdrop-blur-none"
    x-transition:enter-end="opacity-100 backdrop-blur-sm"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 backdrop-blur-sm"
    x-transition:leave-end="opacity-0 backdrop-blur-none"
    class="fixed z-[70] inset-0 overflow-y-auto bg-gray-500/50 backdrop-blur-sm"
>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div
            x-show="showFilePreview"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            @click="showFilePreview = false"
        ></div>

        <div
            x-show="showFilePreview"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative bg-white rounded-lg shadow-xl w-full max-w-3xl"
        >
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">{{ __('filemanager.file_details') }}</h3>
                <button
                    @click="showFilePreview = false"
                    class="text-gray-400 hover:text-gray-500"
                >
                    <span class="sr-only">{{ __('filemanager.close') }}</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Preview Section --}}

            @if($selectedFile)
            <div class="p-6">
                <div class="flex space-x-6">
                    <div class="w-2/3">
                        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                            @if($selectedFile)
                                @if(Str::startsWith($selectedFile->mime_type, 'image/'))
                                    <x-livewire-filemanager::image-loupe
                                        :src="$selectedFile->getFirstMediaUrl()"
                                        :alt="$selectedFile->name"
                                    />
                                @elseif(Str::startsWith($selectedFile->mime_type, 'video/'))
                                    <video controls class="w-full">
                                        <source src="{{ $selectedFile->getFirstMediaUrl() }}" type="{{ $selectedFile->mime_type }}">
                                        Your browser does not support the video tag.
                                    </video>
                                @elseif(Str::startsWith($selectedFile->mime_type, 'audio/'))
                                    <audio controls class="w-full">
                                        <source src="{{ $selectedFile->getFirstMediaUrl() }}" type="{{ $selectedFile->mime_type }}">
                                        Your browser does not support the audio tag.
                                    </audio>
                                @elseif($selectedFile->mime_type === 'application/pdf')
                                    <iframe src="{{ $selectedFile->getFirstMediaUrl() }}" class="w-full h-screen"></iframe>
                                @else
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">{{ __('filemanager.preview_unavailable') }}</p>
                                    </div>
                                @endif
                            @endif
                        </div>

                    </div>
                    <div class="w-1/3 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">{{ __('filemanager.file_name') }}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedFile->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">{{ __('filemanager.type') }}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedFile->mime_type }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">{{ __('filemanager.size') }}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($selectedFile->size / 1024, 2) }} KB</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">{{ __('filemanager.created') }}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedFile->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">{{ __('filemanager.share_link') }}</label>
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
                                {{ __('filemanager.copy') }}
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
                            {{ __('filemanager.download') }}
                        </button>

                        <button
                            wire:click="deleteFile({{ $selectedFile->id }})"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 shadow-inner"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            {{ __('filemanager.delete') }}
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
