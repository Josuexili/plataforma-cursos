<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="section-label">Inici</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Plataforma de cursos</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Cursos, inscripcions i seguiment de contingut en un entorn clar i fàcil d'utilitzar.</p>
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
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-100">Aprenentatge</p>
                        <h2 class="mt-3 max-w-3xl text-4xl font-semibold tracking-tight">Aprèn al teu ritme i accedeix al contingut quan estiguis inscrit al curs.</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-blue-100">Cada curs mostra una vista prèvia oberta i un recorregut complet per a l'alumnat inscrit.</p>
                        <div class="mt-7 flex flex-wrap gap-3">
                            <a href="{{ route('courses.index') }}" class="btn-institutional-secondary !border-white !bg-white !text-blue-900 hover:!bg-blue-50">Veure catàleg</a>
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn-institutional-secondary !border-blue-200 !bg-blue-800 !text-white hover:!bg-blue-700">Obrir panell</a>
                            @else
                                <a href="{{ route('login') }}" class="btn-institutional-secondary !border-blue-200 !bg-blue-800 !text-white hover:!bg-blue-700">Iniciar sessió</a>
                            @endauth
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="rounded-2xl border border-blue-800 bg-blue-800 p-6">
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-100">Accés</p>
                            <div class="mt-4 space-y-4 text-sm text-blue-50">
                                <div>
                                    <p class="font-semibold text-white">Primer, una vista prèvia</p>
                                    <p class="mt-1 text-blue-100">Qualsevol persona pot consultar el catàleg i veure els primers continguts.</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-white">Després, accés complet</p>
                                    <p class="mt-1 text-blue-100">La inscripció activa totes les lliçons i el seguiment complet del curs.</p>
                                </div>
                            </div>
                        </div>
                        <div class="home-note">
                            L'accés s'adapta al rol de cada usuari: visitant, alumne, professor o administració.
                        </div>
                    </div>
                </div>
            </section>

            <div class="home-roles">
                <div class="home-role-card">
                    <p class="section-label">Guest</p>
                    <h2 class="mt-3 text-xl font-semibold text-slate-950">Entrar i mirar</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Consulta el catàleg i accedeix a la vista prèvia dels cursos sense registrar-te.</p>
                </div>
                <div class="home-role-card">
                    <p class="section-label">Usuari registrat</p>
                    <h2 class="mt-3 text-xl font-semibold text-slate-950">Inscriure's i seguir</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Inscriu-te als cursos i consulta totes les lliçons disponibles des del teu compte.</p>
                </div>
                <div class="home-role-card">
                    <p class="section-label">Administració</p>
                    <h2 class="mt-3 text-xl font-semibold text-slate-950">Posar ordre</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Gestiona cursos, lliçons, inscripcions i sol·licituds des del panell intern.</p>
                </div>
            </div>

            <section class="institutional-panel p-8">
                <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="section-label">Cursos destacats</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Cursos destacats</h2>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Una selecció de cursos disponibles per començar a navegar per la plataforma.</p>
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
                                        <span class="rounded-lg bg-white px-3 py-1.5">{{ $course->durada_hores }} hores</span>
                                        <span class="rounded-lg bg-white px-3 py-1.5">{{ $course->lessons_count }} lliçons</span>
                                        <span class="rounded-lg bg-white px-3 py-1.5">{{ $course->creator?->name ?? 'Sense responsable' }}</span>
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
                    <p class="section-label">Funcionament</p>
                    <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Accés clar i recorregut simple</h2>
                    <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">
                        Els cursos mostren una part inicial oberta i requereixen inscripció per consultar totes les lliçons.
                    </p>
                    <div class="mt-6 grid gap-3 md:grid-cols-2">
                        <div class="home-note">
                            El catàleg públic ajuda a localitzar contingut sense necessitat d'entrar.
                        </div>
                        <div class="home-note">
                            El professorat i l'administració disposen d'espais de gestió diferenciats.
                        </div>
                    </div>
                </div>

                <div class="institutional-panel p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-700">Accions ràpides</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-700">
                        <div class="home-note">
                            Consulta el catàleg per veure cursos, nivells i responsables.
                        </div>
                        <div class="home-note">
                            Registra't per inscriure't i accedir al contingut complet.
                        </div>
                    </div>
                    <div class="mt-6 flex flex-wrap gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-institutional">
                                Obrir panell
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-institutional">
                                Crear compte
                            </a>
                            <a href="{{ route('login') }}" class="btn-institutional-secondary">
                                Entrar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
