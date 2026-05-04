<?php

namespace Database\Seeders\Data;

final class DemoUsers
{
    /**
     * @return array{name: string, email: string}
     */
    public static function admin(): array
    {
        return [
            'name' => 'Administradora Demo',
            'email' => 'admin@plataforma-cursos.test',
        ];
    }

    /**
     * @return array<int, array{name: string, email: string}>
     */
    public static function professors(): array
    {
        return [
            ['name' => 'Laia Ferrer', 'email' => 'laia.professor@plataforma-cursos.test'],
            ['name' => 'Joan Puig', 'email' => 'joan.professor@plataforma-cursos.test'],
            ['name' => 'Marta Soler', 'email' => 'marta.professor@plataforma-cursos.test'],
        ];
    }

    /**
     * @return array<int, array{name: string, email: string}>
     */
    public static function students(): array
    {
        return [
            ['name' => 'David Rius', 'email' => 'david.rius@plataforma-cursos.test'],
            ['name' => 'Carla Mateu', 'email' => 'carla.mateu@plataforma-cursos.test'],
            ['name' => 'Eric Navarro', 'email' => 'eric.navarro@plataforma-cursos.test'],
            ['name' => 'Mireia Pons', 'email' => 'mireia.pons@plataforma-cursos.test'],
            ['name' => 'Sergi Bosch', 'email' => 'sergi.bosch@plataforma-cursos.test'],
            ['name' => 'Helena Roig', 'email' => 'helena.roig@plataforma-cursos.test'],
            ['name' => 'Guillem Duran', 'email' => 'guillem.duran@plataforma-cursos.test'],
            ['name' => 'Paula Mir', 'email' => 'paula.mir@plataforma-cursos.test'],
            ['name' => 'Arnau Llobet', 'email' => 'arnau.llobet@plataforma-cursos.test'],
            ['name' => 'Ivet Riera', 'email' => 'ivet.riera@plataforma-cursos.test'],
            ['name' => 'Xavi Beltran', 'email' => 'xavi.beltran@plataforma-cursos.test'],
            ['name' => 'Lucia Esteve', 'email' => 'lucia.esteve@plataforma-cursos.test'],
            ['name' => 'Martí Canals', 'email' => 'marti.canals@plataforma-cursos.test'],
            ['name' => 'Noa Caballé', 'email' => 'noa.caballe@plataforma-cursos.test'],
            ['name' => 'Adrià Sala', 'email' => 'adria.sala@plataforma-cursos.test'],
            ['name' => 'Elisabet Grau', 'email' => 'elisabet.grau@plataforma-cursos.test'],
            ['name' => 'Pol Quintana', 'email' => 'pol.quintana@plataforma-cursos.test'],
            ['name' => 'Neus Cervera', 'email' => 'neus.cervera@plataforma-cursos.test'],
        ];
    }

    /**
     * @return array<int, array{name: string, email: string}>
     */
    public static function showcaseStudents(): array
    {
        return [
            ['name' => 'Anna Serra', 'email' => 'anna@plataforma-cursos.test'],
            ['name' => 'Marc Vidal', 'email' => 'marc@plataforma-cursos.test'],
            ['name' => 'Nora Casas', 'email' => 'nora@plataforma-cursos.test'],
        ];
    }

    /**
     * @return array<int, array{name: string, email: string}>
     */
    public static function pendingTeacherApplications(): array
    {
        return [
            ['name' => 'Roc Pascual', 'email' => 'roc.pascual@plataforma-cursos.test'],
            ['name' => 'Irene Alcaraz', 'email' => 'irene.alcaraz@plataforma-cursos.test'],
            ['name' => 'Víctor Font', 'email' => 'victor.font@plataforma-cursos.test'],
        ];
    }
}
