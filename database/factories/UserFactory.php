<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstNames = ['Pau', 'Clàudia', 'Júlia', 'Oriol', 'Laia', 'Nil', 'Marina', 'Aina', 'Roger', 'Berta'];
        $lastNames = ['Serra', 'Vidal', 'Roca', 'Pujol', 'Casas', 'Costa', 'Ferrer', 'Soler', 'Prat', 'Miret'];

        $name = fake()->randomElement($firstNames).' '.fake()->randomElement($lastNames);

        return [
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'is_admin' => false,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_admin' => true,
        ])->afterCreating(function (User $user): void {
            if (Schema::hasTable('roles')) {
                $role = Role::query()->firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
                foreach ([
                    'courses.create',
                    'courses.update.own',
                    'courses.restore.own',
                    'courses.view.enrollments',
                    'lessons.manage',
                    'enrollments.manage.own',
                    'enrollments.manage.all',
                    'teacher-requests.review',
                ] as $permission) {
                    Permission::findOrCreate($permission, 'web');
                }

                $role->syncPermissions(Permission::query()->get());
                $user->syncRoles([$role]);
            }
        });
    }

    public function student(): static
    {
        return $this->afterCreating(function (User $user): void {
            if (Schema::hasTable('roles')) {
                $role = Role::query()->firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
                Permission::findOrCreate('enrollments.manage.own', 'web');
                $role->syncPermissions(
                    Permission::query()->where('name', 'enrollments.manage.own')->get()
                );
                $user->syncRoles([$role]);
            }
        });
    }
}
