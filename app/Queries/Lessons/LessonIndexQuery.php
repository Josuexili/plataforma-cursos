<?php

namespace App\Queries\Lessons;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class LessonIndexQuery
{
    public function paginate(User $user, string $status): LengthAwarePaginator
    {
        $query = Lesson::query()
            ->select(['id', 'titol', 'ordre', 'course_id', 'deleted_at'])
            ->with(['course:id,titol,deleted_at']);

        if ($user->isTeacher()) {
            $query->whereHas('course', fn (Builder $courseQuery) => $courseQuery->where('user_id', $user->id));
        }

        $this->applyStatusFilter($query, $status);

        return $query
            ->orderBy('course_id')
            ->orderBy('ordre')
            ->paginate(12)
            ->withQueryString();
    }

    private function applyStatusFilter(Builder $query, string $status): void
    {
        if ($status === 'archived') {
            $query->onlyTrashed();
        } elseif ($status === 'all') {
            $query->withTrashed();
        }
    }
}
