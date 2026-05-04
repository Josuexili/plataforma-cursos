<?php

namespace Database\Seeders\Content;

final class DatabaseCourseContent
{
    public static function forLesson(string $lessonTitle): ?string
    {
        return match ($lessonTitle) {
            'Anàlisi funcional abans de crear taules' => "Abans de crear cap taula, cal saber quines dades necessita de veritat l'aplicació. Si el projecte és una plataforma de cursos, primer has d'identificar actors i accions: usuaris, cursos, lliçons, inscripcions i rols.\n\nUna manera útil de començar és escriure frases del tipus: 'un usuari crea cursos', 'un curs té moltes lliçons', 'un usuari es pot inscriure a molts cursos'. Aquestes frases gairebé et donen directament les entitats i les relacions.\n\nNomés després d'aquesta anàlisi té sentit passar al model relacional. Si no ho fas així, és molt fàcil acabar amb camps sobrants o amb una estructura poc coherent.",
            'Relacions 1:N i N:M en un projecte acadèmic' => "Una relació 1:N vol dir que un registre pare pot tenir molts fills. Per exemple, un curs pot tenir moltes lliçons. Aquí la clau forana va a la taula filla: `lessons.course_id`.\n\nUna relació N:M s'utilitza quan dos elements es poden relacionar moltes vegades entre si. Un usuari pot inscriure's a molts cursos i cada curs pot tenir molts usuaris. Per això es crea una taula pivot com `course_user`.\n\nQuan dubtis entre 1:N i N:M, formula la pregunta en tots dos sentits. Si la resposta és 'molts' a banda i banda, necessites una pivot.",
            'Claus foranes, integritat i restriccions' => "La clau forana serveix per impedir que existeixin relacions trencades. Si una lliçó apunta a un curs que no existeix, el sistema està inconsistent. Per això les foreign keys són tan importants.\n\nA Laravel, una definició habitual és:\n\$table->foreignId('course_id')->constrained()->cascadeOnDelete();\n\n`constrained()` crea la relació amb la taula esperada i `cascadeOnDelete()` indica que si s'elimina el curs, també s'eliminaran les lliçons relacionades. No sempre convé fer cascada; depèn del domini. El criteri ha de ser funcional, no automàtic.",
            'Taules pivot amb dades útils de negoci' => "Una taula pivot no és només un pont entre dues claus. Sovint també guarda informació útil. En una plataforma de cursos, la relació entre usuari i curs pot guardar `data_inscripcio`, estat o observacions.\n\nExemple de pivot:\n- id\n- user_id\n- course_id\n- data_inscripcio\n- timestamps\n\nAixò permet consultar no només qui està inscrit, sinó també quan s'ha fet la inscripció o com ha evolucionat. Quan la relació té valor de negoci, deixa de ser una simple unió i passa a ser part del model.",
            default => null,
        };
    }
}
