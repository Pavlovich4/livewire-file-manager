<div>
    <div class="bg-[var(--bg-primary)] rounded-lg overflow-hidden border border-[var(--border-color)]">
        <div class="px-6 py-4 bg-[var(--bg-secondary)]">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-[var(--text-primary)]">
                    {{ $file->name }}
                </h3>
                <div class="flex space-x-3"></div>
                    <button
                        class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--accent-color)] hover:bg-[var(--accent-hover)] text-white font-medium text-sm transition-colors duration-200"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                        Share
                    </button>
                    <button
                        wire:click="$emit('downloadFile', {{ $file->id }})"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 shadow-inner"
                    >
                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download
                    </button>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Size</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ number_format($file->size / 1024, 2) }} KB
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $file->mime_type }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $file->created_at->format('M d, Y H:i') }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Last modified</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $file->updated_at->format('M d, Y H:i') }}
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Preview Section --}}
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            @if(Str::startsWith($file->mime_type, 'image/'))
                <img src="{{ $file->url }}" alt="{{ $file->name }}" class="max-w-full h-auto">
            @elseif(Str::startsWith($file->mime_type, 'video/'))
                <video controls class="w-full">
                    <source src="{{ $file->url }}" type="{{ $file->mime_type }}">
                    Your browser does not support the video tag.
                </video>
            @elseif(Str::startsWith($file->mime_type, 'audio/'))
                <audio controls class="w-full">
                    <source src="{{ $file->url }}" type="{{ $file->mime_type }}">
                    Your browser does not support the audio tag.
                </audio>
            @elseif($file->mime_type === 'application/pdf')
                <iframe src="{{ $file->url }}" class="w-full h-screen"></iframe>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Preview not available for this file type</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Share Modal --}}
    <div
        x-show="showShareModal"
        class="fixed z-50 inset-0 overflow-y-auto"
        x-transition
    >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Share File</h3>

                    @if($shareLink)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Share Link</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input
                                    type="text"
                                    readonly
                                    value="{{ $shareLink }}"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 text-gray-900"
                                >
                                <button
                                    type="button"
                                    onclick="navigator.clipboard.writeText('{{ $shareLink }}')"
                                    class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Copy
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Expiry Date (Optional)</label>
                                <input
                                    type="datetime-local"
                                    wire:model="shareExpiry"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Download Limit (Optional)</label>
                                <input
                                    type="number"
                                    wire:model="shareDownloadLimit"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    min="1"
                                >
                            </div>
                            <button
                                wire:click="createShareLink"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
                            >
                                Generate Share Link
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
