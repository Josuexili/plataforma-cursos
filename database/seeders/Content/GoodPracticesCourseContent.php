<?php

namespace Database\Seeders\Content;

final class GoodPracticesCourseContent
{
    public static function forLesson(string $lessonTitle): ?string
    {
        return match ($lessonTitle) {
            'Començar bé un projecte: ordre abans de velocitat' => "Quan comences un projecte, el més fàcil és anar afegint fitxers ràpidament. El problema és que això passa factura molt aviat. Si des del primer dia poses noms coherents, carpetes clares i una estructura mínima, qualsevol canvi posterior és més segur.\n\nAbans de programar gaire, convé definir com anomenaràs models, controladors, rutes i vistes. També és útil decidir on aniran les migracions, les dades de demo i la documentació mínima.\n\nUn projecte modest però ordenat és molt més professional que un projecte gran i descontrolat.",
            'Escriure codi llegible i fàcil de revisar' => "Codi llegible vol dir que una altra persona pot entendre què passa sense haver de reconstruir-ho tot al cap. Això s'aconsegueix amb noms clars, mètodes curts i responsabilitats separades.\n\nNo és bona pràctica posar consultes, autorització i format visual tot barrejat dins d'un mateix mètode. Quan cada peça fa una feina concreta, el codi es revisa millor i els errors es detecten abans.\n\nCom a regla simple: si un mètode fa massa coses, parteix-lo. Si una variable necessita explicació oral, canvia-li el nom.",
            'Validació, missatges d\'error i experiència d\'ús' => "Una aplicació usable no és només la que accepta dades correctes, sinó també la que reacciona bé quan l'usuari s'equivoca. Per això cal validar camps obligatoris, longituds, formats i relacions.\n\nA Laravel pots centralitzar la validació amb `FormRequest` o amb `validate()` al controlador. El missatge ha de dir què falla i, si pot ser, com corregir-ho.\n\nExemple: no és el mateix mostrar 'Error' que mostrar 'El títol del curs és obligatori i ha de tenir com a mínim 5 caràcters'. La segona opció ajuda l'usuari i també dona sensació de producte més madur.",
            'Control de canvis i preparació per a lliurar' => "Abans d'ensenyar o lliurar un projecte, cal fer una passada final. Aquesta passada ha d'incloure proves bàsiques, verificació visual, revisió de dades demo i comprovació de rols o fluxos principals.\n\nSi fas servir Git, els commits han de reflectir canvis coherents i no barreges coses sense relació. També convé comprovar que el seeder deixa el projecte en un estat presentable.\n\nUn lliurament acadèmic millora molt quan l'avaluador pot arrencar l'app, entrar amb un compte demo i veure un recorregut clar sense errors obvis.",
            'Documentació útil per a un projecte acadèmic' => "La documentació útil és breu però concreta. Hauria d'explicar què fa el projecte, quins rols existeixen, com s'arrenca, quines dades de demo hi ha i quins recorreguts principals cal provar.\n\nNo cal escriure un manual immens. El que cal és que una altra persona pugui entendre l'objectiu del projecte i posar-lo en marxa sense dependre de tu.\n\nEn un projecte acadèmic, un bon `README` o una bona proposta en markdown ajuda tant com una part del codi, perquè dona context i ordre.",
            default => null,
        };
    }
}
