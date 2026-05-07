<?php

namespace App\Services\Lessons;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class LessonService
{
    /**
     * @return array{lesson: Lesson, courses: Collection<int, Course>, selectedCourse: ?Course, isEditing: bool, backRoute: string, backRouteParams: array<string, mixed>}
     */
    public function formData(User $user, Lesson $lesson, bool $isEditing): array
    {
        $backRouteParams = $lesson->course_id ? ['course' => $lesson->course_id] : [];
        $selectedCourse = $lesson->course_id
            ? Course::query()->select(['id', 'titol'])->find($lesson->course_id)
            : null;

        return [
            'lesson' => $lesson,
            'courses' => $this->manageableCourses($user),
            'selectedCourse' => $selectedCourse,
            'isEditing' => $isEditing,
            'backRoute' => $lesson->course_id ? 'courses.show' : 'courses.mine',
            'backRouteParams' => $backRouteParams,
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
        $course = $this->courseForPayload((int) $payload['course_id']);

        return Lesson::create($payload + [
            'ordre' => ((int) $course->lessons()->max('ordre')) + 1,
        ]);
    }

    public function update(Lesson $lesson, array $payload): void
    {
        $lesson->update([
            'course_id' => $payload['course_id'],
            'titol' => $payload['titol'],
            'contingut' => $payload['contingut'],
        ]);
    }

    public function archive(Lesson $lesson): void
    {
        $lesson->delete();
    }

    public function restore(Lesson $lesson): void
    {
        $lesson->restore();
    }

    public function move(Lesson $lesson, string $direction): void
    {
        $operator = $direction === 'up' ? '<' : '>';
        $sortDirection = $direction === 'up' ? 'desc' : 'asc';

        $swapLesson = Lesson::query()
            ->where('course_id', $lesson->course_id)
            ->where('id', '!=', $lesson->id)
            ->where('ordre', $operator, $lesson->ordre)
            ->orderBy('ordre', $sortDirection)
            ->first();

        if (! $swapLesson) {
            return;
        }

        DB::transaction(function () use ($lesson, $swapLesson): void {
            $currentOrder = $lesson->ordre;

            $lesson->update(['ordre' => $swapLesson->ordre]);
            $swapLesson->update(['ordre' => $currentOrder]);
        });
    }

    /**
     * @param array<int, int> $positions
     * @param array<int, int> $lessonIds
     */
    public function reorder(Course $course, array $positions, array $lessonIds): void
    {
        $lessons = $course->lessons()
            ->whereIn('id', $lessonIds)
            ->get(['id', 'ordre']);

        abort_unless($lessons->count() === count($lessonIds), 422);

        $sorted = $lessons
            ->map(fn (Lesson $lesson) => [
                'id' => $lesson->id,
                'desired' => (int) ($positions[$lesson->id] ?? $lesson->ordre),
                'current' => (int) $lesson->ordre,
            ])
            ->sortBy(['desired', 'current'])
            ->values();

        DB::transaction(function () use ($sorted): void {
            foreach ($sorted as $index => $item) {
                Lesson::query()
                    ->whereKey($item['id'])
                    ->update(['ordre' => $index + 1]);
            }
        });
    }
}
