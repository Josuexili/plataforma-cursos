<?php

namespace App\Models;

use App\Enums\CourseLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titol',
        'descripcio',
        'nivell',
        'durada_hores',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'nivell' => CourseLevel::class,
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (self $course): void {
            if ($course->isForceDeleting()) {
                $course->lessons()->withTrashed()->forceDelete();
                $course->enrollments()->withTrashed()->forceDelete();

                return;
            }

            $course->lessons()->delete();
            $course->enrollments()->delete();
        });

        static::restoring(function (self $course): void {
            $course->lessons()->withTrashed()->restore();
            $course->enrollments()->withTrashed()->restore();
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->wherePivotNull('deleted_at')
            ->withPivot('data_inscripcio')
            ->withTimestamps();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function getNivellLabelAttribute(): string
    {
        return $this->nivell?->label() ?? '';
    }

    public function getCoverPathAttribute(): string
    {
        $availableCovers = [
            'introduccio-a-laravel',
            'disseny-de-bases-de-dades-per-aplicacions-web',
            'bones-practiques-de-desenvolupament-web',
            'patrons-de-disseny-per-a-php',
            'aplicacions-web-amb-blade-i-tailwind',
            'arquitectura-d-aplicacions-laravel',
            'gestio-academica-de-la-plataforma',
        ];

        $slug = Str::slug($this->titol);

        if (! in_array($slug, $availableCovers, true)) {
            $slug = 'default-course-cover';
        }

        return "course-covers/{$slug}.svg";
    }

    public function getCoverUrlAttribute(): string
    {
        return asset($this->cover_path);
    }
}
