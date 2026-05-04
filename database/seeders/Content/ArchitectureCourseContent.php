<?php

namespace Database\Seeders\Content;

final class ArchitectureCourseContent
{
    public static function forLesson(string $lessonTitle): ?string
    {
        return match ($lessonTitle) {
            'Del projecte petit al projecte que ha de créixer' => "Un projecte petit acostuma a funcionar amb pocs fitxers i decisions simples. El problema arriba quan hi afegeixes rols, permisos, filtres, noves pantalles i dades més riques.\n\nEn aquest punt, el més important és detectar senyals de creixement: controladors llargs, consultes repetides, vistes amb massa lògica o dificultat per tocar una funcionalitat sense trencar-ne una altra.\n\nArquitectura no vol dir complicar, sinó preparar l'app perquè pugui créixer sense desordenar-se.",
            'Separació de capes i casos d\'ús' => "Separar capes vol dir distingir entre la petició HTTP, la lògica de negoci i l'accés a dades. El controlador rep la petició, però no hauria de fer tota la feina de negoci.\n\nUn cas d'ús podria ser 'inscriure usuari a curs'. Això pot implicar comprovar rol, evitar duplicats, registrar la data i decidir la resposta. Si tot això viu barrejat dins del controlador, el codi es fa més rígid.\n\nLa idea és que cada capa tingui una responsabilitat principal i sigui més fàcil d'entendre i provar.",
            'Autorització, rols i decisions d\'accés' => "Una aplicació amb diferents rols necessita regles d'accés clares. En aquest projecte tenim alumnat, professorat i administració, i cadascun veu i gestiona parts diferents.\n\nLa lògica d'autorització s'ha de validar al backend, no només ocultant botons a la vista. Per això s'utilitzen gates, policies o permisos gestionats amb paquets com Spatie.\n\nQuan defineixis una regla, pregunta't sempre: qui pot fer aquesta acció, sobre quin recurs i en quines condicions concretes.",
            'Consultes eficients i càrrega de relacions' => "Quan una pantalla llista cursos i per a cada curs necessita responsable, nombre de lliçons i estat d'inscripció, és fàcil caure en consultes repetides. Aquí apareix el problema N+1.\n\nLaravel ofereix eines com `with()`, `withCount()` i paginació per carregar relacions de manera eficient. Per exemple:\nCourse::with('creator')->withCount('lessons')->paginate(9);\n\nL'objectiu no és només que la consulta funcioni, sinó que funcioni bé quan el projecte tingui més dades.",
            'Preparació per a manteniment i nous mòduls' => "Pensar en manteniment vol dir preparar el projecte per als canvis futurs. Si demà vols afegir categories, progressos o certificats, la base actual ho hauria de permetre sense reescriure-ho tot.\n\nAixò implica bons noms, mòduls delimitats, dades coherents i punts clars on ampliar el sistema. No es tracta de predir-ho tot, sinó d'evitar decisions que bloquegin el següent pas.\n\nUna arquitectura sana no és la més complexa: és la que et deixa evolucionar el projecte sense perdre control.",
            default => null,
        };
    }
}
