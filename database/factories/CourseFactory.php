<?php

namespace Database\Factories;

use App\Enums\CourseLevel;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $courses = [
            [
                'titol' => 'Fonaments de Laravel per a projectes web',
                'descripcio' => 'Introducció pràctica a l\'estructura d\'un projecte Laravel: rutes, controladors, vistes, migracions i organització general del codi.',
            ],
            [
                'titol' => 'Modelatge de dades per a plataformes educatives',
                'descripcio' => 'Curs centrat en dissenyar taules, claus foranes, relacions i restriccions habituals en aplicacions de gestió acadèmica.',
            ],
            [
                'titol' => 'Interfícies Blade i Tailwind amb criteri',
                'descripcio' => 'Recorregut per crear pantalles clares, consistents i preparades per créixer mantenint una base visual ordenada.',
            ],
            [
                'titol' => 'Arquitectura Laravel per a aplicacions reals',
                'descripcio' => 'Guia per separar responsabilitats, definir fluxos de treball i mantenir el projecte llegible quan augmenta la complexitat.',
            ],
        ];

        $course = fake()->randomElement($courses);

        return [
            'titol' => $course['titol'].' '.fake()->unique()->numberBetween(100, 999),
            'descripcio' => $course['descripcio'],
            'nivell' => fake()->randomElement(array_map(fn (CourseLevel $level) => $level->value, CourseLevel::cases())),
            'durada_hores' => fake()->numberBetween(6, 30),
            'user_id' => User::factory(),
        ];
    }
}
