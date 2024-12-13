# Laravel Livewire File Manager

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pavlovich4/livewire-filemanager.svg?style=flat-square)](https://packagist.org/packages/pavlovich4/livewire-filemanager)
[![Total Downloads](https://img.shields.io/packagist/dt/pavlovich4/livewire-filemanager.svg?style=flat-square)](https://packagist.org/packages/pavlovich4/livewire-filemanager)
![GitHub Actions](https://github.com/pavlovich4/livewire-filemanager/actions/workflows/main.yml/badge.svg)

A modern, responsive file manager for Laravel using Livewire and Alpine.js. Features include:
- Drag and drop file uploads
- Folder management
- Grid and list views
- File previews
- Image thumbnails
- Copy sharing links
- File downloads

## Important Requirements

- PHP 8.2 or higher
- Laravel 10.0 or higher
- Livewire 3.0 or higher
- spatie/laravel-medialibrary 10.0 or higher

## Installation

1. Install the package via composer:

```bash
composer require pavlovich4/livewire-filemanager
```

2. Publish and run the migrations:

```bash
php artisan vendor:publish --tag="livewire-filemanager-migrations"
php artisan migrate
```

3. Publish the config file:

```bash
php artisan vendor:publish --tag="livewire-filemanager-config"
```

4. Publish and configure the media library:

```bash
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="config"
```

Update `config/media-library.php`:
```php
'path_generator' => Pavlovich4\LivewireFilemanager\Support\CustomPathGenerator::class,

// Configure the disk (if you want to use a different one than public)
'disk_name' => 'public',

// Configure the conversions
'image_optimizers' => [
    Spatie\ImageOptimizer\Optimizers\Jpegoptim::class => [
        '-m85', // set maximum quality to 85%
        '--strip-all', // this strips out all text information such as comments and EXIF data
        '--all-progressive', // this will make sure the resulting image is a progressive one
    ],
],
```

5. Create the symbolic link for public storage:

```bash
php artisan storage:link
```

## Usage

1. Add the file manager component to your layout:

```blade
{{-- Using the component --}}
<x-filemanager />

{{-- Or using the Livewire directive --}}
@livewire('file-manager')
```

2. Include the scripts component at the end of your body:

```blade
<x-filemanager-scripts />
```

3. Trigger the file manager from any element using the `data-trigger="filemanager"` attribute:

```html
<!-- Basic trigger -->
<button data-trigger="filemanager">
    Open File Manager
</button>

<!-- With callback function -->
<button
    data-trigger="filemanager"
    data-callback="handleFileSelected"
>
    Select File
</button>

<script>
function handleFileSelected(file) {
    console.log('Selected file:', file);
    // Handle the selected file
}
</script>
```

## Configuration

The package configuration file (`config/livewire-filemanager.php`) allows you to:

```php
return [
    // Storage disk to use (default: 'public')
    'disk' => 'public',

    // Media library configuration
    'media' => [
        'path_generator' => Pavlovich4\LivewireFilemanager\Support\CustomPathGenerator::class,

        // Maximum upload size in MB (default: 100)
        'max_file_size' => 100,

        // Allowed file types (empty array means all types)
        'allowed_mimes' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/pdf',
            // etc...
        ],

        // Thumbnail sizes
        'thumb_sizes' => [
            'width' => 200,
            'height' => 200,
        ],
    ],
];
```

## Features

### File Upload
- Drag and drop support
- Multiple file upload
- Progress indicator
- File type validation
- Size limits (configurable)

### Folder Management
- Create folders
- Delete folders (with confirmation)
- Rename folders
- Nested folders
- Folder navigation

### File Operations
- Preview files
- Download files
- Delete files
- Rename files
- Copy share links
- File icons based on type

### UI Features
- Grid/List view toggle
- Breadcrumb navigation
- Responsive design
- Loading states
- Error handling

## Events

The package emits the following events:

- `filemanager:selected` - When a file is selected (includes file data)
- `open-filemanager` - To open the file manager modal

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email pavlovebiokou@gmail.com instead of using the issue tracker.

## Credits

- [Pavlove Biokou](https://github.com/pavlovich4)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
