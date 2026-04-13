# Proposta del projecte: Plataforma de cursos

## Títol del projecte

**Plataforma de cursos**

## Descripció del projecte

Aquest projecte consisteix en la creació d’una aplicació web feta amb Laravel pensada per gestionar cursos online. La idea és desenvolupar una plataforma senzilla on es puguin mostrar cursos, organitzar les lliçons de cada curs i permetre que els usuaris s’hi puguin inscriure.

En aquesta primera fase, el més important és definir bé la idea general del projecte i preparar l’estructura inicial. Per això, de moment no es desenvoluparan totes les funcionalitats, sinó que es treballarà sobretot la planificació, la base de dades i l’organització del sistema.

## Objectius

Els objectius principals d’aquest projecte són:

- Crear una base sòlida per a una futura plataforma de cursos online.
- Organitzar correctament la informació que es guardarà a la base de dades.
- Diferenciar els tipus d’usuaris que hi haurà al sistema.
- Deixar el projecte preparat per poder afegir més funcionalitats en fases posteriors.
- Explicar de manera clara com serà la proposta abans de començar la part pràctica.

## Justificació del projecte

He escollit aquesta idea perquè una plataforma de cursos és un projecte molt complet i útil per aprendre a desenvolupar una aplicació web real. És un exemple interessant perquè permet treballar amb usuaris, continguts, cursos i diferents tipus d’accés segons cada perfil.

També considero que és un bon projecte per fer de manera progressiva. Primer es pot preparar tota l’estructura, després es pot afegir el sistema d’usuaris, més endavant la gestió dels cursos i, finalment, altres opcions més avançades. Això fa que sigui un projecte assumible i alhora ampliable.

## Estructura bàsica de la base de dades

Per al funcionament d’aquesta plataforma, es preveuen quatre taules principals:

### Usuaris

Aquesta taula guardarà la informació bàsica de les persones que utilitzin la plataforma. Hi haurà usuaris normals i també administradors.

### Cursos

En aquesta taula es guardarà la informació de cada curs, com ara el títol, la descripció o la durada.

### Lliçons

Cada curs estarà format per diferents lliçons. Aquesta taula servirà per guardar el contingut de cada una i l’ordre dins del curs.

### Inscripcions

També caldrà una taula per relacionar els usuaris amb els cursos on s’hagin inscrit.

## Relació entre les taules

La relació entre les taules serà la següent:

- Un usuari podrà crear diversos cursos.
- Cada curs pertanyerà a un únic usuari responsable.
- Un curs podrà contenir diverses lliçons.
- Cada lliçó formarà part d’un únic curs.
- Un usuari es podrà inscriure a diferents cursos.
- Un mateix curs podrà tenir diversos usuaris inscrits.

## Esquema resumit de les relacions

- Un usuari pot tenir molts cursos.
- Un curs pot tenir moltes lliçons.
- Un usuari es pot inscriure a molts cursos.
- Un curs pot tenir molts usuaris inscrits.

## Possibles apartats o pantalles en el futur

Tot i que en aquesta fase encara no es desenvoluparan, més endavant la plataforma podria tenir apartats com aquests:

- Pàgina principal
- Llistat de cursos
- Vista detallada d’un curs
- Panell d’usuari
- Apartat amb els meus cursos
- Panell d’administració
- Gestió de cursos
- Gestió de lliçons
- Gestió d’inscripcions

## Possibles controladors en fases futures

Quan es desenvolupi la part funcional del projecte, probablement caldrà crear controladors per gestionar:

- Els cursos
- Les inscripcions
- El panell principal
- L’administració de cursos
- L’administració de lliçons
- L’administració de les inscripcions

## Tipus d’usuaris

### Visitant

És la persona que entra a la plataforma sense registrar-se. Podrà veure una part dels cursos, però no es podrà inscriure ni accedir al contingut complet.

### Usuari registrat

És l’usuari que tindrà un compte a la plataforma. Podrà inscriure’s als cursos i consultar aquells en què participi.

### Administrador

És l’usuari encarregat de gestionar la plataforma. Podrà crear i modificar cursos, organitzar les lliçons i controlar les inscripcions.

## Conclusió

En conclusió, aquest projecte planteja la creació d’una plataforma de cursos online amb una estructura clara i pensada per créixer en el futur. En aquesta primera fase, l’objectiu principal és definir bé la idea, preparar la base de dades i establir les relacions entre els diferents elements del sistema.

Més endavant, el projecte es podrà ampliar amb funcionalitats com el registre d’usuaris, la gestió de cursos, les inscripcions i un espai d’administració més complet.
