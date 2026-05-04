<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="section-label">Campus digital</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Plataforma de cursos</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Un espai simple per consultar cursos, apuntar-s'hi i seguir lliçons amb una estructura clara, sense fer veure que és una gran plataforma corporativa.</p>
            </div>
            <a href="{{ route('courses.index') }}" class="btn-institutional">
                Explorar cursos
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="institutional-hero p-8 md:p-10">
                <div class="grid gap-8 md:grid-cols-[1.55fr_0.95fr]">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-100">Aprenentatge online</p>
                        <h2 class="mt-3 max-w-3xl text-4xl font-semibold tracking-tight">Cursos online amb accés progressiu, rols clars i una interfície més propera que institucional.</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-blue-100/92">Els visitants poden tafanejar, l'alumnat s'inscriu i desbloqueja les lliçons, i el professorat gestiona el seu espai. Tot està pensat perquè l'experiència sigui senzilla d'entendre des del primer moment.</p>
                        <div class="mt-7 flex flex-wrap gap-3">
                            <a href="{{ route('courses.index') }}" class="inline-flex items-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-blue-950 transition hover:bg-blue-50">Veure catàleg</a>
                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-xl border border-white/25 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10">Accedir al dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center rounded-xl border border-white/25 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10">Iniciar sessió</a>
                            @endauth
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="rounded-[1.6rem] border border-white/15 bg-white/12 p-6 backdrop-blur-sm">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-100">Com funciona</p>
                            <div class="mt-4 space-y-4 text-sm text-blue-50">
                                <div>
                                    <p class="font-semibold text-white">Primer, una vista prèvia</p>
                                    <p class="mt-1 text-blue-100/85">Qualsevol persona pot entrar i veure el catàleg sense necessitat de tenir compte.</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-white">Després, accés complet</p>
                                    <p class="mt-1 text-blue-100/85">Quan t'inscrius, el curs deixa de ser una fitxa i passa a ser un espai de treball real.</p>
                                </div>
                            </div>
                        </div>
                        <div class="home-note border border-white/10 bg-white/10 text-blue-50">
                            El recorregut principal està preparat perquè qualsevol usuari pugui entrar, navegar i entendre com funciona la plataforma sense passos previs complicats.
                        </div>
                    </div>
                </div>
            </section>

            <div class="home-roles">
                <div class="home-role-card">
                    <p class="section-label">Guest</p>
                    <h2 class="mt-3 text-xl font-semibold text-slate-950">Entrar i mirar</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El catàleg es pot consultar sense compte. És una entrada natural per descobrir cursos i veure una primera mostra del contingut.</p>
                </div>
                <div class="home-role-card">
                    <p class="section-label">Usuari registrat</p>
                    <h2 class="mt-3 text-xl font-semibold text-slate-950">Inscriure's i seguir</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Quan l'alumne entra amb compte, ja pot apuntar-se als cursos i passar de la vista prèvia al contingut sencer.</p>
                </div>
                <div class="home-role-card">
                    <p class="section-label">Administració</p>
                    <h2 class="mt-3 text-xl font-semibold text-slate-950">Posar ordre</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">L'admin i el professorat disposen d'un espai de gestió per revisar cursos, usuaris i sol·licituds amb una visió més completa.</p>
                </div>
            </div>

            <section class="institutional-panel p-8">
                <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="section-label">Cursos destacats</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Una selecció curta, amb prou personalitat</h2>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Les portades i els nivells donen context ràpid. La idea no és fer una landing comercial, sinó un aparador que sembli preparat per ser utilitzat de veritat.</p>
                    </div>
                    <a href="{{ route('courses.index') }}" class="btn-institutional-secondary">Veure tots els cursos</a>
                </div>

                <div class="mt-6 grid gap-6 lg:grid-cols-3">
                    @foreach ($featuredCourses as $course)
                        @php
                            $levelKey = $course->nivell instanceof \App\Enums\CourseLevel ? $course->nivell->value : $course->nivell;
                            $levelClasses = [
                                'inicial' => ['card' => 'course-card--inicial', 'chip' => 'status-chip-level-inicial', 'metrics' => 'course-card-metrics--inicial'],
                                'intermedi' => ['card' => 'course-card--intermedi', 'chip' => 'status-chip-level-intermedi', 'metrics' => 'course-card-metrics--intermedi'],
                                'avancat' => ['card' => 'course-card--avancat', 'chip' => 'status-chip-level-avancat', 'metrics' => 'course-card-metrics--avancat'],
                            ][$levelKey] ?? ['card' => 'course-card--inicial', 'chip' => 'status-chip-level-inicial', 'metrics' => 'course-card-metrics--inicial'];
                        @endphp

                        <article class="course-card {{ $levelClasses['card'] }}">
                            <img src="{{ $course->cover_url }}" alt="Portada del curs {{ $course->titol }}" class="h-48 w-full object-cover">
                            <div class="course-card-body">
                                <span class="{{ $levelClasses['chip'] }} w-fit">{{ $course->nivell_label }}</span>
                                <h3 class="mt-3 text-xl font-semibold tracking-tight text-slate-950">{{ $course->titol }}</h3>
                                <p class="mt-3 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($course->descripcio, 120) }}</p>
                                <div class="course-card-metrics {{ $levelClasses['metrics'] }}">
                                    <div class="flex flex-wrap gap-2 text-xs font-semibold text-slate-600">
                                        <span class="rounded-lg bg-white/90 px-3 py-1.5">{{ $course->durada_hores }} hores</span>
                                        <span class="rounded-lg bg-white/90 px-3 py-1.5">{{ $course->lessons_count }} lliçons</span>
                                        <span class="rounded-lg bg-white/90 px-3 py-1.5">{{ $course->creator?->name ?? 'Sense responsable' }}</span>
                                    </div>
                                </div>
                                <div class="mt-auto pt-5">
                                    <a href="{{ route('courses.show', $course) }}" class="btn-institutional-secondary">Veure curs</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
                <div class="editorial-callout">
                    <p class="section-label">Com funciona</p>
                    <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Una plataforma pensada perquè els rols i els accessos siguin clars</h2>
                    <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">
                        Els cursos tenen vista prèvia pública, l'alumnat pot inscriure's i seguir contingut complet, i el professorat gestiona la seva àrea amb permisos diferenciats.
                    </p>
                    <div class="mt-6 grid gap-3 md:grid-cols-2">
                        <div class="home-note">
                            El catàleg públic ensenya només una part del contingut i obliga a seguir el flux lògic si es vol veure el curs complet.
                        </div>
                        <div class="home-note">
                            El professorat i l'administració tenen espais separats per revisar dades, gestionar cursos i validar sol·licituds.
                        </div>
                    </div>
                </div>

                <div class="institutional-panel p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-700">Comptes d'accés</h3>
                    <div class="mt-4 grid gap-4 text-sm text-slate-700">
                        <div>
                            <p class="font-semibold text-slate-950">Admin</p>
                            <p>admin@plataforma-cursos.test</p>
                            <p>password</p>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-950">Professorat</p>
                            <p>laia.professor@plataforma-cursos.test</p>
                            <p>joan.professor@plataforma-cursos.test</p>
                            <p>marta.professor@plataforma-cursos.test</p>
                            <p>password</p>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-950">Alumnes</p>
                            <p>anna@plataforma-cursos.test</p>
                            <p>marc@plataforma-cursos.test</p>
                            <p>password</p>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-slate-600">Els visitants sense compte poden navegar com a guest i només veure la vista prèvia dels cursos.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-institutional">
                                Anar al dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-institutional">
                                Crear compte
                            </a>
                            <a href="{{ route('login') }}" class="btn-institutional-secondary">
                                Iniciar sessio
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
