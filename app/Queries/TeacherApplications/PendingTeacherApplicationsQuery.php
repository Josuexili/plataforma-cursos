<?php

namespace App\Queries\TeacherApplications;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PendingTeacherApplicationsQuery
{
    public function paginate(): LengthAwarePaginator
    {
        return User::query()
            ->role('student')
            ->where('teacher_application_status', 'pending')
            ->latest('teacher_requested_at')
            ->paginate(12);
    }
}
