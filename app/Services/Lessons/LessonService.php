<?php

namespace App\Services\Lessons;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class LessonService
{
    /**
     * @return array{lesson: Lesson, courses: Collection<int, Course>, isEditing: bool}
     */
    public function formData(User $user, Lesson $lesson, bool $isEditing): array
    {
        return [
            'lesson' => $lesson,
            'courses' => $this->manageableCourses($user),
            'isEditing' => $isEditing,
        ];
    }

    /**
     * @return Collection<int, Course>
     */
    public function manageableCourses(User $user): Collection
    {
        return Course::query()
            ->when($user->isTeacher(), fn ($query) => $query->where('user_id', $user->id))
            ->orderBy('titol')
            ->get(['id', 'titol']);
    }

    public function courseForPayload(int $courseId): Course
    {
        return Course::findOrFail($courseId);
    }

    public function create(array $payload): Lesson
    {
        return Lesson::create($payload);
    }

    public function update(Lesson $lesson, array $payload): void
    {
        $lesson->update($payload);
    }

    public function archive(Lesson $lesson): void
    {
        $lesson->delete();
    }

    public function restore(Lesson $lesson): void
    {
        $lesson->restore();
    }
}
