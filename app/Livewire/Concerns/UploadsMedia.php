<?php

namespace App\Livewire\Concerns;

use App\Models\Media;
use Illuminate\Http\UploadedFile;

trait UploadsMedia
{
    protected function storeMedia(UploadedFile $file, string $folder, string $disk = 'public'): string
    {
        $path = $file->store($folder, $disk);

        Media::create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'disk' => $disk,
        ]);

        return $path;
    }
}
