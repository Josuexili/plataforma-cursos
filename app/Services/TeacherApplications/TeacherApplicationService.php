<?php

namespace App\Services\TeacherApplications;

use App\Models\User;

class TeacherApplicationService
{
    public function submit(User $user): string
    {
        if (! $user->hasRole('student')) {
            return 'Aquest compte ja no necessita una sol·licitud de professor.';
        }

        if ($user->teacher_application_status === 'pending') {
            return 'Ja tens una sol·licitud pendent de revisió.';
        }

        $user->update([
            'teacher_application_status' => 'pending',
            'teacher_requested_at' => now(),
            'teacher_reviewed_at' => null,
        ]);

        return 'La teva sol·licitud per ser professor s\'ha enviat correctament.';
    }

    public function approve(User $user): string
    {
        $user->syncRoles(['teacher']);
        $user->update([
            'teacher_application_status' => 'approved',
            'teacher_reviewed_at' => now(),
        ]);

        return 'La sol·licitud s\'ha aprovat i l\'usuari ja és professor.';
    }

    public function reject(User $user): string
    {
        $user->syncRoles(['student']);
        $user->update([
            'teacher_application_status' => 'rejected',
            'teacher_reviewed_at' => now(),
        ]);

        return 'La sol·licitud ha estat rebutjada.';
    }
}
