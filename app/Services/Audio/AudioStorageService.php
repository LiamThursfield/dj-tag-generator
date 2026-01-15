<?php

namespace App\Services\Audio;

use App\Models\DjTagVersion;
use Illuminate\Support\Facades\Storage;

class AudioStorageService
{
    public function getUrl(DjTagVersion $version): string
    {
        $disk = $this->getDiskName();

        // If using R2, try to use the custom CDN URL
        if ($disk === 'r2') {
            $cdnBaseUrl = config('filesystems.disks.r2.url');

            if ($cdnBaseUrl) {
                return rtrim($cdnBaseUrl, '/').'/'.ltrim($version->audio_path, '/');
            }
        }

        // Fallback or Standard Disk behavior
        return Storage::disk($disk)->url($version->audio_path);
    }

    public function store(string $path, string $content, string $visibility = 'public'): bool
    {
        return $this->disk()->put($path, $content, $visibility);
    }

    public function get(string $path): ?string
    {
        return $this->disk()->get($path);
    }

    public function exists(string $path): bool
    {
        return $this->disk()->exists($path);
    }

    public function disk()
    {
        return Storage::disk($this->getDiskName());
    }

    protected function getDiskName(): string
    {
        return config('audio.storage_disk', 'public');
    }
}
