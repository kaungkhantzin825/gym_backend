<?php

namespace App\Models;

use Database\Factories\ExerciseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Exercise extends Model
{
    /** @use HasFactory<ExerciseFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'exercise_name',
        'exercise_type',
        'exercise_tutorial_url',
        'duration_minutes',
        'calories_burned',
        'sets',
        'reps',
        'weight',
        'exercise_time',
    ];

    protected function casts(): array
    {
        return [
            'calories_burned' => 'decimal:2',
            'weight' => 'decimal:2',
            'exercise_time' => 'datetime',
        ];
    }

    /**
     * @return Collection<int, self>
     */
    public static function adminCatalog(): Collection
    {
        return static::query()
            ->select([
                'exercise_name',
                'exercise_type',
                'exercise_tutorial_url',
            ])
            ->whereNotNull('exercise_tutorial_url')
            ->whereHas('user', fn (Builder $query) => $query->where('role', 'admin'))
            ->orderBy('exercise_name')
            ->get()
            ->unique(fn (self $exercise) => mb_strtolower($exercise->exercise_name))
            ->values();
    }

    public function resolvedTutorialUrl(): ?string
    {
        return $this->resolveTutorialMediaUrl($this->exercise_tutorial_url);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workoutSets(): HasMany
    {
        return $this->hasMany(WorkoutSet::class);
    }

    private function resolveTutorialMediaUrl(?string $path): ?string
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
