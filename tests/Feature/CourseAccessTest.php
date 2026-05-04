<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_only_sees_course_preview(): void
    {
        $teacher = User::factory()->create();
        $course = Course::factory()->create([
            'user_id' => $teacher->id,
            'titol' => 'Laravel avançat',
        ]);

        Lesson::factory()->create([
            'course_id' => $course->id,
            'ordre' => 1,
            'titol' => 'Sessió privada',
            'contingut' => 'Contingut llarg de prova per a usuaris inscrits.',
        ]);

        $response = $this->get(route('courses.show', $course));

        $response->assertOk();
        $response->assertSeeText('Vista prèvia del curs');
        $response->assertSeeText('Registrar-me');
        $response->assertDontSeeText('Tens accés complet al contingut d\'aquest curs.');
    }

    public function test_authenticated_user_can_enroll_and_view_full_content(): void
    {
        $teacher = User::factory()->create();
        $student = User::factory()->create();
        $course = Course::factory()->create([
            'user_id' => $teacher->id,
        ]);

        Lesson::factory()->create([
            'course_id' => $course->id,
            'ordre' => 1,
            'titol' => 'Contingut complet',
            'contingut' => 'Text complet de la lliçó.',
        ]);

        $response = $this->actingAs($student)
            ->post(route('courses.enroll', $course));

        $response->assertRedirect(route('courses.show', $course));
        $this->assertDatabaseHas('course_user', [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'deleted_at' => null,
        ]);

        $showResponse = $this->actingAs($student)
            ->get(route('courses.show', $course));

        $showResponse->assertOk();
        $showResponse->assertSeeText('Tens accés complet al contingut d\'aquest curs.');
        $showResponse->assertSeeText('Text complet de la lliçó.');
    }

    public function test_admin_can_soft_delete_and_restore_course(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create([
            'user_id' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->delete(route('courses.destroy', $course))
            ->assertRedirect(route('courses.index'));

        $this->assertSoftDeleted('courses', [
            'id' => $course->id,
        ]);

        $this->actingAs($admin)
            ->post(route('courses.restore', $course->id))
            ->assertRedirect(route('courses.index', ['status' => 'archived']));

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'deleted_at' => null,
        ]);
    }
}
