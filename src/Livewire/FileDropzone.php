<?php

namespace Pavlovich4\LivewireFilemanager\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class FileDropzone extends Component
{
    use WithFileUploads;

    public $files = [];
    public $multiple = true;
    public $accept = '';
    public $maxFiles = null;
    public $maxFileSize = null;

    public function mount($multiple = true, $accept = '', $maxFiles = null, $maxFileSize = null)
    {
        $this->multiple = $multiple;
        $this->accept = $accept;
        $this->maxFiles = $maxFiles;
        $this->maxFileSize = $maxFileSize;
    }

    public function updatedFiles()
    {
        $this->validate([
            'files.*' => 'file|max:' . ($this->maxFileSize ?? '12288'), // 12MB default
        ]);

        $this->dispatch('files-uploaded', files: $this->files);
    }

    public function render()
    {
        return view('livewire-filemanager::livewire.file-dropzone');
    }
}
