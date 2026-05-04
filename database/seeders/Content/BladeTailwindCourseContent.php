<?php

namespace Database\Seeders\Content;

final class BladeTailwindCourseContent
{
    public static function forLesson(string $lessonTitle): ?string
    {
        return match ($lessonTitle) {
            'Layouts base i estructura visual del projecte' => "Un layout base és la plantilla comuna sobre la qual es construeixen la resta de pàgines. Sol incloure la capçalera, la navegació, missatges d'estat i l'espai principal del contingut.\n\nA Blade això es resol habitualment amb un layout com `layouts/app.blade.php` i un slot o seccions on cada pantalla injecta el seu contingut. Això evita repetir HTML i ajuda a mantenir una experiència coherent.\n\nQuan treballis la part visual, pensa primer en la jerarquia general de la pàgina: capçalera, bloc principal, accions i informació secundària.",
            'Components Blade per reutilitzar interfície' => "Un component Blade és una peça de UI reutilitzable. En lloc de copiar el mateix botó o la mateixa targeta moltes vegades, defineixes un component i li passes dades o atributs.\n\nExemples habituals són botons d'acció, missatges d'estat, links de navegació o targetes de curs. Això simplifica la mantenibilitat i fa que la UI tingui una línia més consistent.\n\nQuan un mateix patró apareix tres o quatre vegades, normalment ja mereix ser component.",
            'Tailwind per ordenar espais, tipografia i jerarquia' => "Tailwind no és només posar classes fins que la pantalla sembla acceptable. La seva gràcia és poder controlar espais, mides, colors i alineacions amb un sistema coherent.\n\nPensa en blocs: separació entre seccions, mida dels titulars, contrast dels textos secundaris i jerarquia dels botons. Si tot pesa igual, l'usuari no sap on mirar.\n\nUna bona interfície no necessita ser molt complexa, però sí clara. Amb Tailwind pots aconseguir-ho mantenint un llenguatge visual consistent.",
            'Formularis clars i pantalles de gestió' => "Els formularis han de ser fàcils de llegir i de corregir. Cada camp ha de tenir una etiqueta clara, l'error ha d'aparèixer a prop del problema i els botons han d'indicar bé l'acció que fan.\n\nEn pantalles de gestió com crear o editar cursos, és útil agrupar camps relacionats: dades generals, nivell, durada, contingut o estat. Això redueix la càrrega visual.\n\nQuan provis un formulari, fixa't en tres coses: si s'entén què cal escriure, si els errors són útils i si l'usuari pot tornar enrere sense perdre el context.",
            default => null,
        };
    }
}
