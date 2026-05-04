<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lesson>
 */
class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lessons = [
            [
                'titol' => 'Presentació de la unitat',
                'contingut' => 'En aquesta lliçó es presenta l\'objectiu de la unitat, el vocabulari bàsic i el resultat que s\'espera assolir al final del bloc.',
            ],
            [
                'titol' => 'Preparació de l\'entorn de treball',
                'contingut' => 'Es revisen les eines necessàries, l\'estructura mínima del projecte i els passos previs abans de començar a implementar.',
            ],
            [
                'titol' => 'Aplicació guiada pas a pas',
                'contingut' => 'Desenvolupament progressiu d\'una part del sistema amb explicacions clares sobre les decisions que es prenen i els errors habituals.',
            ],
            [
                'titol' => 'Revisió final i bones pràctiques',
                'contingut' => 'Tancament de la unitat amb una síntesi dels conceptes, recomanacions de manteniment i propostes de millora.',
            ],
        ];

        $lesson = fake()->randomElement($lessons);

        return [
            'titol' => $lesson['titol'],
            'contingut' => $lesson['contingut'],
            'ordre' => fake()->numberBetween(1, 12),
            'course_id' => Course::factory(),
        ];
    }
}
