<?php

namespace App\Queries\Courses;

use App\Enums\CourseLevel;
use App\Models\Course;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CourseIndexQuery
{
    public function paginate(?User $user, string $status, string $selectedLevel): LengthAwarePaginator
    {
        $query = Course::query()
            ->select(['id', 'titol', 'descripcio', 'nivell', 'durada_hores', 'user_id', 'created_at', 'deleted_at'])
            ->with(['creator:id,name'])
            ->withCount('lessons');

        if ($selectedLevel !== '' && CourseLevel::tryFrom($selectedLevel)) {
            $query->where('nivell', $selectedLevel);
        }

        $this->applyStatusFilter($query, (bool) $user?->isAdmin(), $status);

        return $query
            ->latest()
            ->paginate($user ? 9 : 6)
            ->withQueryString();
    }

    private function applyStatusFilter(Builder $query, bool $isAdmin, string $status): void
    {
        if (! $isAdmin) {
            return;
        }

        if ($status === 'archived') {
            $query->onlyTrashed();
        } elseif ($status === 'all') {
            $query->withTrashed();
        }
    }
}
