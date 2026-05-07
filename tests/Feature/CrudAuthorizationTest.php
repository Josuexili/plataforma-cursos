<?php

namespace Tests\Feature;

use App\Enums\CourseLevel;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_key_screens_render_for_expected_roles(): void
    {
        $admin = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->create();
        $course = Course::factory()->create(['user_id' => $teacher->id]);
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        $enrollment = Enrollment::factory()->create([
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $this->get(route('home'))->assertOk();
        $this->get(route('courses.index'))->assertOk();
        $this->get(route('courses.show', $course))->assertOk();
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();

        $this->actingAs($student)->get(route('dashboard'))->assertOk();
        $this->actingAs($student)->get(route('profile.edit'))->assertOk();
        $this->actingAs($student)->get(route('enrollments.index'))->assertOk();
        $this->actingAs($student)->get(route('enrollments.create'))->assertOk();
        $this->actingAs($student)->get(route('enrollments.edit', $enrollment))->assertOk();
        $this->actingAs($student)->get(route('teacher-applications.index'))->assertForbidden();

        $this->actingAs($teacher)->get(route('courses.create'))->assertOk();
        $this->actingAs($teacher)->get(route('courses.edit', $course))->assertOk();
        $this->actingAs($teacher)->get(route('lessons.index'))->assertOk();
        $this->actingAs($teacher)->get(route('lessons.create', ['course_id' => $course->id]))->assertOk();
        $this->actingAs($teacher)->get(route('lessons.edit', $lesson))->assertOk();

        $pendingStudent = User::factory()->student()->create([
            'teacher_application_status' => 'pending',
            'teacher_requested_at' => now()->subDay(),
        ]);

        $this->actingAs($admin)->get(route('teacher-applications.index'))->assertOk();
        $this->actingAs($admin)->post(route('teacher-applications.reject', $pendingStudent))->assertRedirect();
    }

    public function test_teacher_can_complete_course_crud_for_owned_course(): void
    {
        $teacher = User::factory()->teacher()->create();

        $storeResponse = $this->actingAs($teacher)->post(route('courses.store'), [
            'titol' => 'Laravel per a DAM',
            'descripcio' => 'Curs pràctic sobre estructura, rutes i vistes.',
            'nivell' => CourseLevel::Inicial->value,
            'durada_hores' => 18,
        ]);

        $course = Course::query()->where('titol', 'Laravel per a DAM')->firstOrFail();

        $storeResponse->assertRedirect(route('courses.show', $course));
        $this->assertSame($teacher->id, $course->user_id);

        $this->actingAs($teacher)->put(route('courses.update', $course), [
            'titol' => 'Laravel per a DAM actualitzat',
            'descripcio' => 'Curs pràctic amb estructura, rutes, vistes i migracions.',
            'nivell' => CourseLevel::Intermedi->value,
            'durada_hores' => 22,
        ])->assertRedirect(route('courses.show', $course));

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'titol' => 'Laravel per a DAM actualitzat',
            'durada_hores' => 22,
        ]);

        $this->actingAs($teacher)
            ->delete(route('courses.destroy', $course))
            ->assertRedirect(route('courses.index'));

        $this->assertSoftDeleted('courses', ['id' => $course->id]);

        $this->actingAs($teacher)
            ->post(route('courses.restore', $course->id))
            ->assertRedirect(route('courses.index', ['status' => 'archived']));

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'deleted_at' => null,
        ]);
    }

    public function test_teacher_can_complete_lesson_crud_for_owned_course(): void
    {
        $teacher = User::factory()->teacher()->create();
        $course = Course::factory()->create(['user_id' => $teacher->id]);

        $this->actingAs($teacher)->post(route('lessons.store'), [
            'course_id' => $course->id,
            'titol' => 'Primera ruta',
            'contingut' => 'Crearem una ruta a web.php i comprovarem la resposta al navegador.',
        ])->assertRedirect();

        $lesson = Lesson::query()->where('course_id', $course->id)->where('titol', 'Primera ruta')->firstOrFail();

        $this->actingAs($teacher)->put(route('lessons.update', $lesson), [
            'course_id' => $course->id,
            'titol' => 'Primera ruta i controlador',
            'contingut' => 'Afegirem una ruta i la connectarem amb un controlador simple.',
        ])->assertRedirect(route('courses.show', $course));

        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'titol' => 'Primera ruta i controlador',
            'ordre' => 1,
        ]);

        $secondLesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'ordre' => 2,
        ]);

        $this->actingAs($teacher)->post(route('courses.lessons.reorder', $course), [
            'lesson_ids' => [$lesson->id, $secondLesson->id],
            'positions' => [
                $lesson->id => 2,
                $secondLesson->id => 1,
            ],
        ])->assertRedirect(route('courses.show', $course));

        $lesson->refresh();
        $secondLesson->refresh();

        $this->assertSame(2, $lesson->ordre);
        $this->assertSame(1, $secondLesson->ordre);

        $this->actingAs($teacher)
            ->delete(route('lessons.destroy', $lesson))
            ->assertRedirect(route('courses.show', $course));

        $this->assertSoftDeleted('lessons', ['id' => $lesson->id]);

        $this->actingAs($teacher)
            ->post(route('lessons.restore', $lesson->id))
            ->assertRedirect(route('lessons.index', ['status' => 'archived']));

        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'deleted_at' => null,
        ]);
    }

    public function test_admin_can_complete_enrollment_crud_for_any_user_and_course(): void
    {
        $admin = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->create();
        $course = Course::factory()->create(['user_id' => $teacher->id]);

        $this->actingAs($admin)->post(route('enrollments.store'), [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'data_inscripcio' => now()->format('Y-m-d H:i:s'),
        ])->assertRedirect();

        $enrollment = Enrollment::query()
            ->where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $this->actingAs($admin)->get(route('enrollments.show', $enrollment))->assertOk();

        $this->actingAs($admin)->put(route('enrollments.update', $enrollment), [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'data_inscripcio' => now()->subDay()->format('Y-m-d H:i:s'),
        ])->assertRedirect(route('enrollments.show', $enrollment));

        $this->actingAs($admin)
            ->delete(route('enrollments.destroy', $enrollment))
            ->assertRedirect(route('enrollments.index'));

        $this->assertSoftDeleted('course_user', ['id' => $enrollment->id]);

        $this->actingAs($admin)
            ->post(route('enrollments.restore', $enrollment->id))
            ->assertRedirect(route('enrollments.index', ['status' => 'archived']));

        $this->assertDatabaseHas('course_user', [
            'id' => $enrollment->id,
            'deleted_at' => null,
        ]);
    }

    public function test_student_can_only_manage_own_enrollments(): void
    {
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->create();
        $otherStudent = User::factory()->student()->create();
        $course = Course::factory()->create(['user_id' => $teacher->id]);

        $this->actingAs($student)->post(route('enrollments.store'), [
            'user_id' => $otherStudent->id,
            'course_id' => $course->id,
            'data_inscripcio' => now()->format('Y-m-d H:i:s'),
        ])->assertRedirect();

        $ownEnrollment = Enrollment::query()
            ->where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $foreignEnrollment = Enrollment::factory()->create([
            'user_id' => $otherStudent->id,
            'course_id' => $course->id,
        ]);

        $this->actingAs($student)->get(route('enrollments.show', $ownEnrollment))->assertOk();
        $this->actingAs($student)->get(route('enrollments.show', $foreignEnrollment))->assertForbidden();
        $this->actingAs($student)->get(route('enrollments.edit', $foreignEnrollment))->assertForbidden();
        $this->actingAs($student)->put(route('enrollments.update', $foreignEnrollment), [
            'user_id' => $otherStudent->id,
            'course_id' => $course->id,
            'data_inscripcio' => now()->format('Y-m-d H:i:s'),
        ])->assertForbidden();
    }

    public function test_teacher_cannot_manage_foreign_courses_or_lessons(): void
    {
        $teacher = User::factory()->teacher()->create();
        $otherTeacher = User::factory()->teacher()->create();
        $foreignCourse = Course::factory()->create(['user_id' => $otherTeacher->id]);
        $foreignLesson = Lesson::factory()->create(['course_id' => $foreignCourse->id]);

        $this->actingAs($teacher)->get(route('courses.edit', $foreignCourse))->assertForbidden();
        $this->actingAs($teacher)->put(route('courses.update', $foreignCourse), [
            'titol' => 'No permès',
            'descripcio' => 'Intent no autoritzat',
            'nivell' => CourseLevel::Inicial->value,
            'durada_hores' => 12,
        ])->assertForbidden();

        $this->actingAs($teacher)->post(route('lessons.store'), [
            'course_id' => $foreignCourse->id,
            'titol' => 'Intent extern',
            'ordre' => 1,
            'contingut' => 'Aquesta creació no hauria de passar.',
        ])->assertForbidden();

        $this->actingAs($teacher)->get(route('lessons.edit', $foreignLesson))->assertForbidden();
        $this->actingAs($teacher)->delete(route('lessons.destroy', $foreignLesson))->assertForbidden();
    }
}
