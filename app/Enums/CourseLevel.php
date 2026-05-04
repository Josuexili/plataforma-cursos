<?php

namespace App\Enums;

enum CourseLevel: string
{
    case Inicial = 'inicial';
    case Intermedi = 'intermedi';
    case Avancat = 'avancat';

    public function label(): string
    {
        return match ($this) {
            self::Inicial => 'Inicial',
            self::Intermedi => 'Intermedi',
            self::Avancat => 'Avançat',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $level): array => [$level->value => $level->label()])
            ->all();
    }
}
