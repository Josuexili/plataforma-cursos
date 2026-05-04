<?php

namespace Database\Seeders\Data;

final class DemoCourses
{
    /**
     * @return array<int, array{
     *     titol: string,
     *     descripcio: string,
     *     nivell: string,
     *     durada_hores: int,
     *     teacher_email: string,
     *     lessons: array<int, array{titol: string, contingut: string}>
     * }>
     */
    public static function courseBlueprints(): array
    {
        return [
            [
                'titol' => 'Introducció a Laravel',
                'descripcio' => 'Curs inicial pensat per entendre l\'estructura d\'un projecte Laravel, les rutes, els controladors, les migracions i les vistes Blade.',
                'nivell' => 'inicial',
                'durada_hores' => 12,
                'teacher_email' => 'laia.professor@plataforma-cursos.test',
                'lessons' => [
                    ['titol' => 'Què és Laravel i com s\'organitza un projecte', 'contingut' => 'Introducció general al framework i a l\'estructura base d\'un projecte real.'],
                    ['titol' => 'Rutes web i primer recorregut d\'una petició', 'contingut' => 'Primer contacte amb `routes/web.php`, mètodes HTTP, URL i recorregut de la petició.'],
                    ['titol' => 'Controladors i separació de responsabilitats', 'contingut' => 'Com moure la lògica fora de les rutes i organitzar millor el backend.'],
                    ['titol' => 'Migracions i modelatge inicial de dades', 'contingut' => 'Com definir taules, relacions i esquema de base de dades amb migracions.'],
                    ['titol' => 'Vistes Blade i dades compartides amb la interfície', 'contingut' => 'Introducció a Blade i a la manera de renderitzar dades dins de les vistes.'],
                ],
            ],
            [
                'titol' => 'Disseny de bases de dades per aplicacions web',
                'descripcio' => 'Curs orientat a definir taules, claus foranes i relacions habituals en projectes CRUD amb usuaris, recursos principals i taules pivot.',
                'nivell' => 'intermedi',
                'durada_hores' => 10,
                'teacher_email' => 'joan.professor@plataforma-cursos.test',
                'lessons' => [
                    ['titol' => 'Anàlisi funcional abans de crear taules', 'contingut' => 'Com passar dels requisits a les entitats i relacions principals.'],
                    ['titol' => 'Relacions 1:N i N:M en un projecte acadèmic', 'contingut' => 'Modelatge relacional aplicat a usuaris, cursos i lliçons.'],
                    ['titol' => 'Claus foranes, integritat i restriccions', 'contingut' => 'Foreign keys, consistència i decisions de cascada.'],
                    ['titol' => 'Taules pivot amb dades útils de negoci', 'contingut' => 'Com donar sentit funcional a una taula intermèdia com `course_user`.'],
                ],
            ],
            [
                'titol' => 'Bones pràctiques de desenvolupament web',
                'descripcio' => 'Recorregut per una manera ordenada de construir projectes acadèmics o professionals amb una estructura clara, codi net i documentació mínima útil.',
                'nivell' => 'intermedi',
                'durada_hores' => 8,
                'teacher_email' => 'marta.professor@plataforma-cursos.test',
                'lessons' => [
                    ['titol' => 'Començar bé un projecte: ordre abans de velocitat', 'contingut' => 'Posar criteri i estructura abans de començar a afegir funcionalitats.'],
                    ['titol' => 'Escriure codi llegible i fàcil de revisar', 'contingut' => 'Noms clars, mètodes curts i responsabilitats separades.'],
                    ['titol' => 'Validació, missatges d\'error i experiència d\'ús', 'contingut' => 'Com fer que una aplicació falli bé i guiï l\'usuari.'],
                    ['titol' => 'Control de canvis i preparació per a lliurar', 'contingut' => 'Com revisar el projecte abans d\'una entrega o demostració.'],
                    ['titol' => 'Documentació útil per a un projecte acadèmic', 'contingut' => 'Quina documentació mínima ajuda realment a entendre i arrencar el projecte.'],
                ],
            ],
            [
                'titol' => 'Patrons de disseny per a PHP',
                'descripcio' => 'Introducció pràctica a patrons útils per estructurar codi de backend de manera més clara i mantenible.',
                'nivell' => 'avancat',
                'durada_hores' => 14,
                'teacher_email' => 'laia.professor@plataforma-cursos.test',
                'lessons' => [
                    ['titol' => 'Quan té sentit parlar de patrons i quan no', 'contingut' => 'Quan un patró resol un problema real i quan només afegeix complexitat.'],
                    ['titol' => 'Repository i Service en context Laravel', 'contingut' => 'Aplicació pràctica de capes de servei i accés a dades.'],
                    ['titol' => 'Strategy per variar comportaments sense duplicar lògica', 'contingut' => 'Com encapsular variants de comportament sense omplir el codi de condicionals.'],
                    ['titol' => 'Factory i construcció d\'objectes amb context', 'contingut' => 'Factories per centralitzar la creació d\'objectes i dades de prova.'],
                    ['titol' => 'Antipatrons habituals en projectes PHP', 'contingut' => 'Errors estructurals habituals i com detectar-los aviat.'],
                    ['titol' => 'Aplicació pràctica sobre un mòdul de cursos', 'contingut' => 'Cas pràctic de disseny sobre cursos, lliçons i inscripcions.'],
                ],
            ],
            [
                'titol' => 'Aplicacions web amb Blade i Tailwind',
                'descripcio' => 'Curs centrat en construir interfícies netes i ràpides amb components Blade i utilitats de Tailwind.',
                'nivell' => 'inicial',
                'durada_hores' => 9,
                'teacher_email' => 'joan.professor@plataforma-cursos.test',
                'lessons' => [
                    ['titol' => 'Layouts base i estructura visual del projecte', 'contingut' => 'Com construir una base visual coherent per a tota l\'aplicació.'],
                    ['titol' => 'Components Blade per reutilitzar interfície', 'contingut' => 'Reutilització de peces de UI amb components Blade.'],
                    ['titol' => 'Tailwind per ordenar espais, tipografia i jerarquia', 'contingut' => 'Jerarquia visual, espais i consistència amb utilitats de Tailwind.'],
                    ['titol' => 'Formularis clars i pantalles de gestió', 'contingut' => 'Bones pràctiques per crear formularis i pantalles administratives.'],
                ],
            ],
            [
                'titol' => 'Arquitectura d\'aplicacions Laravel',
                'descripcio' => 'Revisió d\'organització de carpetes, serveis, casos d\'ús i separació de responsabilitats en un projecte més complet.',
                'nivell' => 'avancat',
                'durada_hores' => 16,
                'teacher_email' => 'marta.professor@plataforma-cursos.test',
                'lessons' => [
                    ['titol' => 'Del projecte petit al projecte que ha de créixer', 'contingut' => 'Què canvia quan un projecte simple comença a sumar mòduls, rols i pantalles.'],
                    ['titol' => 'Separació de capes i casos d\'ús', 'contingut' => 'Com repartir responsabilitats entre HTTP, negoci i dades.'],
                    ['titol' => 'Autorització, rols i decisions d\'accés', 'contingut' => 'Com modelar correctament permisos i regles d\'accés.'],
                    ['titol' => 'Consultes eficients i càrrega de relacions', 'contingut' => 'Rendiment, eager loading i llistats eficients.'],
                    ['titol' => 'Preparació per a manteniment i nous mòduls', 'contingut' => 'Com deixar una base prou sana perquè el projecte pugui créixer.'],
                ],
            ],
        ];
    }

    /**
     * @return array{titol: string, descripcio: string, nivell: string, durada_hores: int}
     */
    public static function adminCourse(): array
    {
        return [
            'titol' => 'Gestió acadèmica de la plataforma',
            'descripcio' => 'Curs intern per mostrar un exemple addicional creat directament per l\'administració.',
            'nivell' => 'intermedi',
            'durada_hores' => 6,
        ];
    }

    /**
     * @return array<int, array{titol: string, contingut: string}>
     */
    public static function adminLessons(): array
    {
        return [
            [
                'titol' => 'Panell d\'administració i supervisió general',
                'contingut' => 'Quines dades ha de veure l\'administració per tenir una visió global de la plataforma.',
            ],
            [
                'titol' => 'Revisió de sol·licituds de professor',
                'contingut' => 'Flux perquè un usuari registrat pugui demanar el rol de professor i l\'admin el pugui validar.',
            ],
            [
                'titol' => 'Preparació de dades demo i verificació final',
                'contingut' => 'Com deixar l\'aplicació presentable abans d\'una demostració o lliurament final.',
            ],
        ];
    }
}
