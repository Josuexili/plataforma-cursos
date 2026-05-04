<?php

namespace Database\Seeders\Content;

final class PatternsCourseContent
{
    public static function forLesson(string $lessonTitle): ?string
    {
        return match ($lessonTitle) {
            'Quan té sentit parlar de patrons i quan no' => "Un patró de disseny no és un objectiu en si mateix. És una solució recurrent a un problema que apareix sovint. Si no tens el problema, aplicar el patró només complica el projecte.\n\nAbans d'introduir cap patró, has de saber quin dolor vols resoldre: codi duplicat, dependències difícils de substituir, comportaments variables o creació d'objectes massa dispersa.\n\nLa primera lliçó del curs és aquesta: no busquis patrons per quedar bé, usa'ls només quan facin el codi més clar o més fàcil de mantenir.",
            'Repository i Service en context Laravel' => "El patró Repository encapsula l'accés a dades. El patró Service concentra casos d'ús o processos de negoci. En Laravel no sempre són imprescindibles, però poden ajudar quan la lògica creix.\n\nPer exemple, un `CourseRepository` podria reunir consultes habituals de cursos, i un `EnrollmentService` podria centralitzar la inscripció d'un alumne validant rols, duplicitats i dates.\n\nSi el projecte és petit, afegir aquestes capes massa aviat pot ser soroll. Si el projecte creix i la lògica es repeteix, passen a tenir molt més sentit.",
            'Strategy per variar comportaments sense duplicar lògica' => "El patró Strategy consisteix a encapsular diferents variants d'un comportament sota una mateixa interfície. En lloc de tenir molts `if` repartits, delegues la decisió a una estratègia concreta.\n\nExemple proper: una plataforma pot tenir diferents maneres de publicar un curs, validar una sol·licitud o notificar una inscripció. Cada variant viu a una classe diferent, però totes comparteixen una mateixa idea d'ús.\n\nAixò fa que afegir un nou comportament sigui més segur, perquè no toques tota la cadena de condicionals anterior.",
            'Factory i construcció d\'objectes amb context' => "Una factory centralitza la creació d'objectes quan aquesta creació té criteris o configuració. Això evita repartir inicialitzacions complexes per moltes parts del codi.\n\nA Laravel, les factories s'utilitzen molt per generar dades de prova o de demo. Per exemple, pots tenir una `UserFactory` amb estats com `admin()` o `student()` i així sembrar la base de dades amb intenció clara.\n\nLa idea important és que construir objectes també és una responsabilitat que es pot organitzar bé.",
            'Antipatrons habituals en projectes PHP' => "Un antipatró és una solució que sembla útil però acaba generant problemes. Alguns exemples clars són els controladors enormes, la lògica de negoci dins de Blade o els models que fan de tot.\n\nTambé és un mal senyal repetir la mateixa consulta a molts llocs o prendre decisions d'autorització només a la interfície i no al backend.\n\nAprendre a detectar aquests problemes és tan important com conèixer patrons bons, perquè molts projectes fallen més pels mals hàbits que per falta de teoria.",
            'Aplicació pràctica sobre un mòdul de cursos' => "Quan apliques patrons sobre un cas real, has de mirar si de veritat milloren el mòdul. En una plataforma de cursos pots revisar el flux de creació de cursos, la gestió d'inscripcions i la validació de rols.\n\nL'objectiu no és convertir el projecte en una arquitectura enorme, sinó identificar dos o tres punts on una millor separació de responsabilitats fa la vida més fàcil.\n\nUna bona aplicació pràctica sempre acaba amb aquesta pregunta: què ha quedat més clar, més reusable o més segur després del canvi?",
            default => null,
        };
    }
}
