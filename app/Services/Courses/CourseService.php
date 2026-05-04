<?php

namespace App\Services\Courses;

use App\Enums\CourseLevel;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseService
{
    /**
     * @return array{course: Course, isEditing: bool, levels: array<string, string>}
     */
    public function formData(Course $course, bool $isEditing): array
    {
        return [
            'course' => $course,
            'isEditing' => $isEditing,
            'levels' => CourseLevel::options(),
        ];
    }

    public function create(array $payload, User $user): Course
    {
        return Course::create($payload + [
            'user_id' => $user->id,
        ]);
    }

    public function update(Course $course, array $payload): void
    {
        $course->update($payload);
    }

    public function archive(Course $course): void
    {
        $course->delete();
    }

    public function restore(Course $course): void
    {
        $course->restore();
    }

    /**
     * @return array{
     *     course: Course,
     *     previewLessons: Collection<int, mixed>,
     *     fullLessons: Collection<int, mixed>,
     *     canViewContent: bool,
     *     canEnroll: bool,
     *     isGuest: bool,
     *     isStaff: bool,
     *     enrolledUsers: LengthAwarePaginator|null
     * }
     */
    public function showData(?User $user, Course $course): array
    {
        $isStaff = $this->isStaffForCourse($user, $course);
        $isEnrolled = $this->isUserEnrolled($user?->id, $course);
        $canViewContent = $isStaff || $isEnrolled;

        $course->load('creator:id,name');

        $lessons = $this->orderedCourseLessons($course);
        $previewLessons = (clone $lessons)->limit(2)->get();
        $fullLessons = $canViewContent ? $lessons->get() : collect();
        $enrolledUsers = ($isStaff && $user?->can('courses.view.enrollments'))
            ? $this->courseEnrollmentsPaginator($course)
            : null;

        return [
            'course' => $course,
            'previewLessons' => $previewLessons,
            'fullLessons' => $fullLessons,
            'canViewContent' => $canViewContent,
            'canEnroll' => (bool) $user && ! $isStaff && ! $isEnrolled,
            'isGuest' => ! $user,
            'isStaff' => $isStaff,
            'enrolledUsers' => $enrolledUsers,
        ];
    }

    public function enroll(User $user, Course $course): string
    {
        if ($this->isStaffForCourse($user, $course)) {
            return 'Ja tens accés complet a aquest curs.';
        }

        $enrollment = Enrollment::withTrashed()->firstOrNew([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);

        $enrollment->data_inscripcio = now();

        if ($enrollment->trashed()) {
            $enrollment->restore();
        }

        $enrollment->save();

        return 'T\'has inscrit correctament al curs.';
    }

    private function isStaffForCourse(?User $user, Course $course): bool
    {
        return (bool) $user && ($user->isAdmin() || $course->user_id === $user->id);
    }

    private function isUserEnrolled(?int $userId, Course $course): bool
    {
        if (! $userId) {
            return false;
        }

        return Enrollment::query()
            ->where('course_id', $course->id)
            ->where('user_id', $userId)
            ->exists();
    }

    private function orderedCourseLessons(Course $course): HasMany
    {
        return $course->lessons()
            ->select(['id', 'titol', 'contingut', 'ordre', 'course_id'])
            ->orderBy('ordre');
    }

    private function courseEnrollmentsPaginator(Course $course): LengthAwarePaginator
    {
        return Enrollment::query()
            ->where('course_id', $course->id)
            ->with('user:id,name,email')
            ->latest('data_inscripcio')
            ->paginate(10, ['*'], 'users_page')
            ->withQueryString();
    }
}
