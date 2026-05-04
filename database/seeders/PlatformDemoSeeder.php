<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Database\Seeders\Data\DemoCourses;
use Database\Seeders\Data\DemoUsers;
use Database\Seeders\Support\LessonContentFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PlatformDemoSeeder extends Seeder
{
    private const PERMISSIONS = [
        'courses.create',
        'courses.update.own',
        'courses.restore.own',
        'courses.view.enrollments',
        'lessons.manage',
        'enrollments.manage.own',
        'enrollments.manage.all',
        'teacher-requests.review',
    ];

    private const STUDENT_PERMISSIONS = [
        'enrollments.manage.own',
    ];

    private const TEACHER_PERMISSIONS = [
        'courses.create',
        'courses.update.own',
        'courses.restore.own',
        'courses.view.enrollments',
        'lessons.manage',
        'enrollments.manage.own',
    ];

    public function run(): void
    {
        [$studentRole, $teacherRole, $adminRole] = $this->seedRolesAndPermissions();

        $admin = $this->createAdmin($adminRole);
        $professors = $this->createProfessors($teacherRole);
        $students = $this->createStudents();
        $showcaseStudents = $this->createShowcaseStudents();
        $this->createPendingTeacherApplications();

        $allStudents = $students->concat($showcaseStudents);
        $professorDirectory = $professors->keyBy('email');

        $courses = $this->seedCourses($professorDirectory);
        $this->seedEnrollments($courses, $allStudents, $showcaseStudents);
        $this->seedAdminCourse($admin);
    }

    /**
     * @return array{0: Role, 1: Role, 2: Role}
     */
    private function seedRolesAndPermissions(): array
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $studentRole = Role::findOrCreate('student', 'web');
        $teacherRole = Role::findOrCreate('teacher', 'web');
        $adminRole = Role::findOrCreate('admin', 'web');

        $studentRole->syncPermissions(
            Permission::query()->whereIn('name', self::STUDENT_PERMISSIONS)->get()
        );

        $teacherRole->syncPermissions(
            Permission::query()->whereIn('name', self::TEACHER_PERMISSIONS)->get()
        );

        $adminRole->syncPermissions(Permission::query()->get());

        return [$studentRole, $teacherRole, $adminRole];
    }

    private function createAdmin(Role $adminRole): User
    {
        $admin = User::factory()->admin()->create(DemoUsers::admin());
        $admin->syncRoles([$adminRole]);

        return $admin;
    }

    /**
     * @return Collection<int, User>
     */
    private function createProfessors(Role $teacherRole): Collection
    {
        return collect(DemoUsers::professors())
            ->map(function (array $attributes, int $index) use ($teacherRole): User {
                $user = User::factory()->student()->create($attributes + [
                    'teacher_application_status' => 'approved',
                    'teacher_requested_at' => now()->subWeeks($index + 3),
                    'teacher_reviewed_at' => now()->subWeeks($index + 2),
                ]);

                $user->syncRoles([$teacherRole]);

                return $user;
            });
    }

    /**
     * @return Collection<int, User>
     */
    private function createStudents(): Collection
    {
        return collect(DemoUsers::students())
            ->map(fn (array $attributes): User => User::factory()->student()->create($attributes));
    }

    /**
     * @return Collection<int, User>
     */
    private function createShowcaseStudents(): Collection
    {
        return collect(DemoUsers::showcaseStudents())
            ->map(fn (array $attributes): User => User::factory()->student()->create($attributes));
    }

    private function createPendingTeacherApplications(): void
    {
        collect(DemoUsers::pendingTeacherApplications())
            ->each(function (array $attributes): void {
                User::factory()->student()->create($attributes + [
                    'teacher_application_status' => 'pending',
                    'teacher_requested_at' => now()->subDays(2),
                ]);
            });
    }

    /**
     * @param Collection<string, User> $professorDirectory
     * @return Collection<int, Course>
     */
    private function seedCourses(Collection $professorDirectory): Collection
    {
        return collect(DemoCourses::courseBlueprints())
            ->map(function (array $blueprint) use ($professorDirectory): Course {
                $teacher = $professorDirectory->get($blueprint['teacher_email']);
                $lessons = $blueprint['lessons'];

                unset($blueprint['teacher_email'], $blueprint['lessons']);

                $course = Course::factory()->create($blueprint + [
                    'user_id' => $teacher->id,
                ]);

                $this->seedLessons($course, $lessons);

                return $course;
            });
    }

    /**
     * @param array<int, array{titol: string, contingut: string}> $lessons
     */
    private function seedLessons(Course $course, array $lessons): void
    {
        foreach ($lessons as $index => $lesson) {
            Lesson::create([
                'course_id' => $course->id,
                'ordre' => $index + 1,
                'titol' => $lesson['titol'],
                'contingut' => LessonContentFactory::make($course->titol, $lesson['titol'], $lesson['contingut']),
            ]);
        }
    }

    /**
     * @param Collection<int, Course> $courses
     * @param Collection<int, User> $allStudents
     * @param Collection<int, User> $showcaseStudents
     */
    private function seedEnrollments(Collection $courses, Collection $allStudents, Collection $showcaseStudents): void
    {
        $courses->each(function (Course $course, int $index) use ($allStudents): void {
            $selectedStudents = $allStudents->shuffle()->take(4 + ($index % 4));

            foreach ($selectedStudents as $student) {
                Enrollment::firstOrCreate(
                    [
                        'user_id' => $student->id,
                        'course_id' => $course->id,
                    ],
                    [
                        'data_inscripcio' => fake()->dateTimeBetween('-3 months', 'now'),
                    ]
                );
            }
        });

        foreach ($showcaseStudents as $index => $student) {
            Enrollment::firstOrCreate(
                [
                    'user_id' => $student->id,
                    'course_id' => $courses[$index]->id,
                ],
                [
                    'data_inscripcio' => now()->subDays((7 - ($index * 3))),
                ]
            );
        }
    }

    private function seedAdminCourse(User $admin): void
    {
        $course = $admin->courses()->save(
            Course::factory()->make(DemoCourses::adminCourse())
        );

        $this->seedLessons($course, DemoCourses::adminLessons());
    }
}
