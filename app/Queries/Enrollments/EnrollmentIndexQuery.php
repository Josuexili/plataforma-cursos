<?php

namespace App\Queries\Enrollments;

use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class EnrollmentIndexQuery
{
    public function paginate(User $user, string $status): LengthAwarePaginator
    {
        $query = Enrollment::query()
            ->select(['id', 'user_id', 'course_id', 'data_inscripcio', 'deleted_at', 'created_at'])
            ->with([
                'user:id,name,email',
                'course:id,titol,deleted_at',
            ]);

        if (! $user->can('enrollments.manage.all')) {
            $query->where('user_id', $user->id);
        }

        $this->applyStatusFilter($query, $status);

        return $query->latest()->paginate(12)->withQueryString();
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
