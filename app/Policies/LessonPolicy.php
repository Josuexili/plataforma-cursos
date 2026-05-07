<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;
use App\Support\Permissions\PlatformPermissions;

class LessonPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(PlatformPermissions::LESSONS_MANAGE);
    }

    public function create(User $user): bool
    {
        return $user->can(PlatformPermissions::LESSONS_MANAGE);
    }

    public function view(User $user, Lesson $lesson): bool
    {
        return $this->update($user, $lesson);
    }

    public function update(User $user, Lesson $lesson): bool
    {
        return $user->can(PlatformPermissions::LESSONS_MANAGE)
            && ($user->isAdmin() || (int) $lesson->course->user_id === (int) $user->id);
    }

    public function delete(User $user, Lesson $lesson): bool
    {
        return $this->update($user, $lesson);
    }

    public function restore(User $user, Lesson $lesson): bool
    {
        return $this->update($user, $lesson);
    }
}
