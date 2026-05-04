<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TeacherApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('teacher', 'web');
    }

    public function test_student_can_request_teacher_role(): void
    {
        $student = User::factory()->student()->create([
            'teacher_application_status' => 'none',
        ]);

        $this->actingAs($student)
            ->post(route('teacher-applications.store'))
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $student->id,
            'teacher_application_status' => 'pending',
        ]);
    }

    public function test_admin_can_approve_pending_teacher_application(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create([
            'teacher_application_status' => 'pending',
            'teacher_requested_at' => now()->subDay(),
        ]);

        $this->actingAs($admin)
            ->post(route('teacher-applications.approve', $student))
            ->assertRedirect();

        $student->refresh();

        $this->assertTrue($student->hasRole('teacher'));
        $this->assertSame('approved', $student->teacher_application_status);
        $this->assertNotNull($student->teacher_reviewed_at);
    }

    public function test_admin_can_reject_pending_teacher_application(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create([
            'teacher_application_status' => 'pending',
            'teacher_requested_at' => now()->subDay(),
        ]);

        $this->actingAs($admin)
            ->post(route('teacher-applications.reject', $student))
            ->assertRedirect();

        $student->refresh();

        $this->assertTrue($student->hasRole('student'));
        $this->assertSame('rejected', $student->teacher_application_status);
        $this->assertNotNull($student->teacher_reviewed_at);
    }

    public function test_admin_cannot_approve_non_pending_account(): void
    {
        $admin = User::factory()->admin()->create();
        $teacher = User::factory()->student()->create([
            'teacher_application_status' => 'approved',
            'teacher_requested_at' => now()->subDays(2),
            'teacher_reviewed_at' => now()->subDay(),
        ]);
        $teacher->syncRoles(['teacher']);

        $this->actingAs($admin)
            ->post(route('teacher-applications.approve', $teacher))
            ->assertNotFound();

        $teacher->refresh();

        $this->assertTrue($teacher->hasRole('teacher'));
        $this->assertSame('approved', $teacher->teacher_application_status);
    }
}
