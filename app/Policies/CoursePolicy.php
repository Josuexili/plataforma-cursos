<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use App\Support\Permissions\PlatformPermissions;

class CoursePolicy
{
    public function create(User $user): bool
    {
        return $user->can(PlatformPermissions::COURSES_CREATE);
    }

    public function update(User $user, Course $course): bool
    {
        return $user->can(PlatformPermissions::COURSES_UPDATE_OWN)
            && ($user->isAdmin() || (int) $course->user_id === (int) $user->id);
    }

    public function delete(User $user, Course $course): bool
    {
        return $this->update($user, $course);
    }

    public function restore(User $user, Course $course): bool
    {
        return $user->can(PlatformPermissions::COURSES_RESTORE_OWN)
            && ($user->isAdmin() || (int) $course->user_id === (int) $user->id);
    }

    public function manageLessons(User $user, Course $course): bool
    {
        return $user->can(PlatformPermissions::LESSONS_MANAGE)
            && ($user->isAdmin() || (int) $course->user_id === (int) $user->id);
    }

    public function viewEnrollments(User $user, Course $course): bool
    {
        return $user->can(PlatformPermissions::COURSES_VIEW_ENROLLMENTS)
            && ($user->isAdmin() || (int) $course->user_id === (int) $user->id);
    }
}
