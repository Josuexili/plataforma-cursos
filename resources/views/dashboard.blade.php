<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-950 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            <section class="institutional-hero p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-100">Àrea personal</p>
                        <h3 class="mt-3 text-3xl font-semibold tracking-tight">Benvingut, {{ auth()->user()->name }}</h3>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-blue-100/90">Aquest panell resumeix l’activitat del compte, l’estat de les inscripcions i els accessos disponibles segons el teu rol dins la plataforma.</p>
                    </div>
                    <div class="rounded-2xl border border-white/15 bg-white/10 px-5 py-4 backdrop-blur-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-100">Rol actual</p>
                        <p class="mt-2 text-lg font-semibold text-white">{{ auth()->user()->role_label }}</p>
                    </div>
                </div>
            </section>

            <div class="grid gap-6 md:grid-cols-3">
                <div class="institutional-card p-6">
                    <p class="section-label">Sessió</p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-950">Dades del compte</h3>
                    <p class="mt-3 text-sm text-slate-600">Rol actual: {{ auth()->user()->role_label }}</p>
                    @if (auth()->user()->hasRole('student'))
                        <p class="mt-2 text-sm text-slate-600">Estat de professor: {{ auth()->user()->teacher_application_status }}</p>
                    @endif
                </div>

                <div class="institutional-card p-6">
                    <p class="section-label">Catàleg</p>
                    <h3 class="mt-2 text-3xl font-semibold text-slate-950">{{ $stats['courses'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">Cursos disponibles actualment dins la plataforma.</p>
                </div>

                <div class="institutional-card p-6">
                    <p class="section-label">
                        @if (auth()->user()->can('enrollments.manage.all'))
                            Inscripcions totals
                        @else
                            Les teves inscripcions
                        @endif
                    </p>
                    <h3 class="mt-2 text-3xl font-semibold text-slate-950">{{ $stats['enrollments'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">Resum ràpid de l'activitat associada al compte i als cursos.</p>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="institutional-card p-6">
                    <p class="section-label">Contingut</p>
                    <h3 class="mt-2 text-3xl font-semibold text-slate-950">{{ $stats['lessons'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">Lliçons disponibles entre els cursos publicats i accessibles segons el rol.</p>
                    <a href="{{ route('courses.index') }}" class="btn-institutional mt-4">
                        Veure cataleg
                    </a>
                </div>

                <div class="institutional-card p-6">
                    <p class="section-label">Activitat recent</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-950">Últims cursos</h3>
                    <div class="mt-4 space-y-3">
                        @foreach ($latestCourses as $course)
                            <div class="rounded-2xl border border-blue-100 bg-blue-50/50 px-4 py-3">
                                <p class="font-medium text-slate-950">{{ $course->titol }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $course->nivell_label }} · {{ $course->durada_hores }} hores</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                @if (auth()->user()->hasRole('student'))
                    <div class="institutional-card p-6">
                        <p class="section-label">Itinerari docent</p>
                        <h3 class="mt-2 text-lg font-semibold text-slate-950">Validació per administració</h3>
                        <p class="mt-2 text-sm text-slate-600">El rol de professor no s'assigna automàticament. Pots enviar una sol·licitud perquè l'admin la revisi.</p>

                        @if (auth()->user()->teacher_application_status === 'pending')
                            <p class="status-chip-amber mt-4">
                                Sol·licitud pendent
                            </p>
                        @elseif (auth()->user()->teacher_application_status === 'rejected')
                            <div class="mt-4 flex flex-wrap gap-3">
                                <p class="status-chip bg-red-100 text-red-800">
                                    Sol·licitud rebutjada
                                </p>
                                <form method="POST" action="{{ route('teacher-applications.store') }}">
                                    @csrf
                                    <button type="submit" class="btn-institutional">
                                        Tornar a sol·licitar
                                    </button>
                                </form>
                            </div>
                        @elseif (auth()->user()->teacher_application_status === 'approved')
                            <p class="status-chip-emerald mt-4">
                                Sol·licitud aprovada
                            </p>
                        @else
                            <form method="POST" action="{{ route('teacher-applications.store') }}" class="mt-4">
                                @csrf
                                <button type="submit" class="btn-institutional">
                                    Demanar rol de professor
                                </button>
                            </form>
                        @endif
                    </div>
                @endif

                @if (auth()->user()->can('teacher-requests.review'))
                    <div class="institutional-card p-6">
                        <p class="section-label">Administració</p>
                        <h3 class="mt-2 text-lg font-semibold text-slate-950">Sol·licituds pendents</h3>
                        <p class="mt-2 text-sm text-slate-600">Actualment hi ha {{ $pendingTeacherApplications }} peticions de professor per revisar.</p>
                        <a href="{{ route('teacher-applications.index') }}" class="btn-institutional mt-4">
                            Obrir panell
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
