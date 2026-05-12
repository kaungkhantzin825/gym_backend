<?php

namespace App\Models;

use Database\Factories\TutorialVideoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TutorialVideo extends Model
{
    /** @use HasFactory<TutorialVideoFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'video_url',
        'thumbnail_url',
        'gender_target',
        'muscle_group',
    ];

    public function resolvedVideoUrl(): ?string
    {
        return $this->resolveMediaUrl($this->video_url);
    }

    public function resolvedThumbnailUrl(): ?string
    {
        return $this->resolveMediaUrl($this->thumbnail_url);
    }

    private function resolveMediaUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, '/storage/')) {
            return url($path);
        }

        return Storage::disk('public')->url($path);
    }
}
