<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;
use App\Support\Permissions\PlatformPermissions;

class EnrollmentPolicy
{
    public function create(User $user): bool
    {
        return $user->can(PlatformPermissions::ENROLLMENTS_MANAGE_ALL)
            || $user->can(PlatformPermissions::ENROLLMENTS_MANAGE_OWN);
    }

    public function view(User $user, Enrollment $enrollment): bool
    {
        return $this->update($user, $enrollment);
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        return $user->can(PlatformPermissions::ENROLLMENTS_MANAGE_ALL)
            || (
                $user->can(PlatformPermissions::ENROLLMENTS_MANAGE_OWN)
                && (int) $enrollment->user_id === (int) $user->id
            );
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
        return $this->update($user, $enrollment);
    }

    public function restore(User $user, Enrollment $enrollment): bool
    {
        return $this->update($user, $enrollment);
    }
}
