<?php

namespace Pavlovich4\LivewireFilemanager\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Pavlovich4\LivewireFilemanager\Actions\UploadFileAction;
use Pavlovich4\LivewireFilemanager\Actions\CreateFolderAction;
use Pavlovich4\LivewireFilemanager\Models\{File, Folder};

class FileManager extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $currentFolder = null;
    public $files = [];
    public $uploadedFiles = [];
    public $search = '';
    public $newFolderName = '';
    public $showNewFolderModal = false;
    public $isGrid = true;
    public $isUploading = false;
    public $expandedFolders = [];
    public $editingName = null;
    public $editingType = null;
    public $editingFileName = null;
    public $globalSearch = '';
    public $selectedFile = null;
    public $showFilePreview = false;
    public $showDeleteConfirmation = false;
    public $folderToDelete = null;

    protected $listeners = [
        'fileUpload' => 'handleFileUpload',
        'uploadProgress' => 'handleUploadProgress',
    ];

    public function mount()
    {
        $this->loadFiles();
    }

    public function loadFiles()
    {
        $query = File::query()
            ->with('media')
            ->where('folder_id', $this->currentFolder);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('mime_type', 'like', "%{$this->search}%");
            });
        }

        $this->files = $query->latest()->get();
    }

    public function handleFileUpload($fileData = null)
    {
        $this->isUploading = true;

        if ($fileData) {
            // Handle drag and drop files
            foreach ($fileData as $file) {
                $this->uploadedFiles[] = $file;
            }
        }

        if (empty($this->uploadedFiles)) {
            $this->isUploading = false;
            return;
        }

        $this->validate([
            'uploadedFiles.*' => 'required|file|max:102400', // 100MB max
        ]);

        $folder = $this->currentFolder ? Folder::find($this->currentFolder) : null;

        foreach ($this->uploadedFiles as $file) {
            try {
                app(UploadFileAction::class)->execute($file, $folder);
            } catch (\Exception $e) {
                \Log::error('Upload failed: ' . $e->getMessage());
            }
        }

        $this->uploadedFiles = [];
        $this->isUploading = false;
        $this->loadFiles();
    }

    public function updatedUploadedFiles()
    {
        $this->handleFileUpload();
    }

    public function handleUploadProgress($filename, $progress)
    {
        if (isset($this->uploadProgress[$filename])) {
            $this->uploadProgress[$filename]['progress'] = $progress;
        }
    }

    public function toggleView()
    {
        $this->isGrid = !$this->isGrid;
    }

    public function createFolder()
    {
        $this->validate([
            'newFolderName' => 'required|min:1|max:255'
        ]);

        $parent = $this->currentFolder ? Folder::find($this->currentFolder) : null;

        app(CreateFolderAction::class)->execute($this->newFolderName, $parent);

        $this->newFolderName = '';
        $this->showNewFolderModal = false;
        $this->loadFiles();
    }

    public function navigateToFolder($folderId = null)
    {
        $this->currentFolder = $folderId;
        $this->loadFiles();
    }

    public function render()
    {
        $query = Folder::query();

        if ($this->globalSearch) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->globalSearch}%");
            });

            $fileQuery = File::query()
                ->where('name', 'like', "%{$this->globalSearch}%")
                ->orWhere('mime_type', 'like', "%{$this->globalSearch}%");

            $this->files = $fileQuery->get();
        } else {
            $query->where('parent_id', $this->currentFolder);
            $this->loadFiles();
        }

        return view('livewire-filemanager::livewire.file-manager', [
            'rootFolders' => Folder::whereNull('parent_id')
                ->orderBy('order')
                ->with('children')
                ->get(),
            'breadcrumbs' => $this->getBreadcrumbs(),
        ]);
    }

    protected function getBreadcrumbs()
    {
        $breadcrumbs = collect([['id' => null, 'name' => 'Root']]);

        if ($this->currentFolder) {
            $folder = Folder::find($this->currentFolder);
            $parents = collect();

            while ($folder) {
                $parents->push(['id' => $folder->id, 'name' => $folder->name]);
                $folder = $folder->parent;
            }

            $breadcrumbs = $breadcrumbs->concat($parents->reverse());
        }

        return $breadcrumbs;
    }

    public function downloadFile($fileId)
    {
        $file = File::findOrFail($fileId);
        return response()->download($file->getFirstMediaPath());
    }

    public function deleteFile($fileId)
    {
        try {
            $file = File::findOrFail($fileId);
            $file->delete();
            $this->closePreview();
        } catch (\Exception $e) {
            \Log::error('Error deleting file: ' . $e->getMessage());
        }
        $this->loadFiles();
    }

    public function deleteFolder($folderId)
    {
        try {
            $folder = Folder::findOrFail($folderId);
            $filesCount = $folder->allFiles()->count();
            $foldersCount = $folder->allChildren()->count();

            if ($filesCount > 0 || $foldersCount > 0) {
                $this->folderToDelete = [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'filesCount' => $filesCount,
                    'foldersCount' => $foldersCount
                ];
                $this->showDeleteConfirmation = true;
                return;
            }

            $folder->delete();
        } catch (\Exception $e) {
        }
        $this->loadFiles();
    }

    public function confirmDelete()
    {
        try {
            $folder = Folder::findOrFail($this->folderToDelete['id']);
            $folder->delete();
        } catch (\Exception $e) {
            \Log::error('Error deleting folder: ' . $e->getMessage());
        }

        $this->showDeleteConfirmation = false;
        $this->folderToDelete = null;
        $this->loadFiles();
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirmation = false;
        $this->folderToDelete = null;
    }

    public function renameFile($fileId, $newName)
    {
        $file = File::findOrFail($fileId);
        $file->update(['name' => $newName]);
        $this->loadFiles();
    }

    public function renameFolder($folderId)
    {
        $this->validate([
            'newFolderName' => 'required|min:1|max:255'
        ]);

        try {
            $folder = Folder::findOrFail($folderId);
            $oldPath = $folder->path;
            $newPath = $folder->parent ? $folder->parent->full_path . '/' . $this->newFolderName : $this->newFolderName;

            // Update folder name and path
            $folder->update([
                'name' => $this->newFolderName,
                'path' => $newPath
            ]);

            // Update physical folder name
            if (Storage::disk(config('livewire-filemanager.disk', 'public'))->exists($oldPath)) {
                Storage::disk(config('livewire-filemanager.disk', 'public'))->move($oldPath, $newPath);
            }
        } catch (\Exception $e) {
        }

        $this->stopEditing();
        $this->loadFiles();
    }

    public function toggleFolder($folderId)
    {
        if (in_array($folderId, $this->expandedFolders)) {
            $this->expandedFolders = array_diff($this->expandedFolders, [$folderId]);
        } else {
            $this->expandedFolders[] = $folderId;
        }
    }

    public function startEditing($id, $type)
    {
        $this->editingName = $id;
        $this->editingType = $type;
        if ($type === 'folder') {
            $folder = Folder::find($id);
            $this->newFolderName = $folder->name;
        } else {
            $file = File::find($id);
            $this->editingFileName = $file->name;
        }
    }

    public function stopEditing()
    {
        $this->editingName = null;
        $this->editingType = null;
    }

    public function selectFile($fileId)
    {
        $this->selectedFile = File::with('media')->find($fileId);
        $this->showFilePreview = true;
    }

    public function closePreview()
    {
        $this->showFilePreview = false;
        $this->selectedFile = null;
    }

    public function getFileIcon($mimeType)
    {
        $icons = [
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'doc',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xls',
            'application/zip' => 'zip',
            'application/x-rar-compressed' => 'zip',
            'text/plain' => 'txt',
            'text/html' => 'html',
            'text/css' => 'code',
            'text/javascript' => 'code',
            'application/json' => 'code',
            'video/' => 'video',
            'audio/' => 'audio',
        ];

        foreach ($icons as $type => $icon) {
            if (str_starts_with($mimeType, $type)) {
                return $icon;
            }
        }

        return 'file';
    }
}
