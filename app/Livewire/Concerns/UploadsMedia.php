<?php

namespace App\Livewire\Concerns;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Store a new upload while removing the media (file + row) it replaces.
     */
    protected function replaceMedia(UploadedFile $file, string $folder, ?string $oldPath, string $disk = 'public'): string
    {
        $this->deleteMedia($oldPath, $disk);

        return $this->storeMedia($file, $folder, $disk);
    }

    protected function deleteMedia(?string $path, string $disk = 'public'): void
    {
        if (empty($path)) {
            return;
        }

        Storage::disk($disk)->delete($path);

        Media::where('path', $path)->where('disk', $disk)->delete();
    }
}
