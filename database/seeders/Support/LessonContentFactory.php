<?php

namespace Database\Seeders\Support;

use Database\Seeders\Content\AdminCourseContent;
use Database\Seeders\Content\ArchitectureCourseContent;
use Database\Seeders\Content\BladeTailwindCourseContent;
use Database\Seeders\Content\DatabaseCourseContent;
use Database\Seeders\Content\GoodPracticesCourseContent;
use Database\Seeders\Content\LaravelCourseContent;
use Database\Seeders\Content\PatternsCourseContent;

final class LessonContentFactory
{
    public static function make(string $courseTitle, string $lessonTitle, string $fallbackSummary): string
    {
        $content = match ($courseTitle) {
            'Introducció a Laravel' => LaravelCourseContent::forLesson($lessonTitle),
            'Disseny de bases de dades per aplicacions web' => DatabaseCourseContent::forLesson($lessonTitle),
            'Bones pràctiques de desenvolupament web' => GoodPracticesCourseContent::forLesson($lessonTitle),
            'Patrons de disseny per a PHP' => PatternsCourseContent::forLesson($lessonTitle),
            'Aplicacions web amb Blade i Tailwind' => BladeTailwindCourseContent::forLesson($lessonTitle),
            'Arquitectura d\'aplicacions Laravel' => ArchitectureCourseContent::forLesson($lessonTitle),
            default => AdminCourseContent::forLesson($lessonTitle),
        };

        return $content ?: $fallbackSummary;
    }
}
