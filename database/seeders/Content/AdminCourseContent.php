<?php

namespace Database\Seeders\Content;

final class AdminCourseContent
{
    public static function forLesson(string $lessonTitle): ?string
    {
        return match ($lessonTitle) {
            'Panell d\'administració i supervisió general' => "El panell d'administració ha de donar una visió ràpida de l'estat del sistema: cursos publicats, sol·licituds pendents, usuaris registrats i inscripcions totals.\n\nL'objectiu és que l'admin detecti si hi ha contingut buit, cursos sense responsable, peticions pendents o anomalies bàsiques abans que afectin la demo o el funcionament normal.\n\nUna bona pantalla d'administració prioritza indicadors clars i accions directes, no només llistats llargs.",
            'Revisió de sol·licituds de professor' => "Quan un usuari vol passar de rol d'alumne a professor, cal un petit flux de revisió. L'admin ha de veure qui fa la petició, quan la fa i tenir la possibilitat d'aprovar o rebutjar.\n\nAprovar una sol·licitud ha d'actualitzar el rol i l'estat de revisió. Rebutjar-la també ha de deixar el sistema consistent, sense permisos residuals.\n\nAquest procés és important perquè la publicació de cursos no quedi oberta a qualsevol compte registrat.",
            'Preparació de dades demo i verificació final' => "Les dades de demo són part del producte quan el projecte s'ha d'ensenyar. Un bon seeder ha de crear usuaris, cursos, lliçons i inscripcions que permetin provar fluxos reals sense haver de preparar-ho tot a mà.\n\nAbans de fer una presentació final, convé revisar que els comptes entren, que els cursos tenen contingut, que les lliçons es poden llegir i que els rols responen com toca.\n\nUna validació final ben feta evita molts problemes petits que, en una demo, es noten molt.",
            default => null,
        };
    }
}
