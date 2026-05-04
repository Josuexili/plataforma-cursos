<?php

namespace App\Services\Enrollments;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class EnrollmentService
{
    /**
     * @return array{enrollment: Enrollment, courses: Collection<int, Course>, users: Collection<int, User>, isEditing: bool}
     */
    public function formData(User $user, Enrollment $enrollment, bool $isEditing, bool $canManageAll): array
    {
        return [
            'enrollment' => $enrollment,
            'courses' => Course::query()->orderBy('titol')->get(['id', 'titol']),
            'users' => $canManageAll
                ? User::query()->orderBy('name')->get(['id', 'name', 'email'])
                : User::query()->whereKey($user->id)->get(['id', 'name', 'email']),
            'isEditing' => $isEditing,
        ];
    }

    /**
     * @return array{enrollment: Enrollment, reactivated: bool}
     */
    public function store(array $payload, User $user, bool $canManageAll): array
    {
        $payload = $this->normalizedPayload($payload, $user, $canManageAll);
        $enrollment = $this->existingEnrollment($payload['user_id'], $payload['course_id']);

        if ($enrollment !== null) {
            $enrollment->fill($payload);
            $reactivated = $enrollment->trashed();

            if ($reactivated) {
                $enrollment->restore();
            }

            $enrollment->save();

            return ['enrollment' => $enrollment, 'reactivated' => true];
        }

        return [
            'enrollment' => Enrollment::create($payload),
            'reactivated' => false,
        ];
    }

    public function update(Enrollment $enrollment, array $payload, User $user, bool $canManageAll): void
    {
        $payload = $this->normalizedPayload($payload, $user, $canManageAll);
        $conflict = $this->existingEnrollment($payload['user_id'], $payload['course_id'], $enrollment->id);

        if ($conflict) {
            throw ValidationException::withMessages([
                'course_id' => 'Ja existeix una inscripció per aquest usuari i curs.',
            ]);
        }

        $enrollment->update($payload);
    }

    public function archive(Enrollment $enrollment): void
    {
        $enrollment->delete();
    }

    public function restore(Enrollment $enrollment): void
    {
        $existingActive = Enrollment::query()
            ->where('user_id', $enrollment->user_id)
            ->where('course_id', $enrollment->course_id)
            ->exists();

        if ($existingActive) {
            throw ValidationException::withMessages([
                'enrollment' => 'Ja existeix una inscripció activa equivalent i no es pot restaurar.',
            ]);
        }

        $enrollment->restore();
    }

    private function normalizedPayload(array $payload, User $user, bool $canManageAll): array
    {
        if (! $canManageAll) {
            $payload['user_id'] = $user->id;
        }

        $payload['data_inscripcio'] ??= now();

        return $payload;
    }

    private function existingEnrollment(int $userId, int $courseId, ?int $ignoreId = null): ?Enrollment
    {
        $query = Enrollment::withTrashed()
            ->where('user_id', $userId)
            ->where('course_id', $courseId);

        if ($ignoreId !== null) {
            $query->whereKeyNot($ignoreId);
        }

        return $query->first();
    }
}
