<?php

namespace Pavlovich4\LivewireFilemanager\Components;

use Livewire\Component;
use Pavlovich4\LivewireFilemanager\Models\{File, FileShare};

class FilePreview extends Component
{
    public $file;
    public $showShareModal = false;
    public $shareExpiry = null;
    public $shareDownloadLimit = null;
    public $shareLink = null;

    public function mount(File $file)
    {
        $this->file = $file;
    }

    public function createShareLink()
    {
        $share = FileShare::create([
            'file_id' => $this->file->id,
            'expires_at' => $this->shareExpiry,
            'download_limit' => $this->shareDownloadLimit,
        ]);

        $this->shareLink = route('file-manager.shared', $share->token);
        $this->showShareModal = true;
    }

    public function render()
    {
        return view('livewire-filemanager::components.file-preview');
    }
}
