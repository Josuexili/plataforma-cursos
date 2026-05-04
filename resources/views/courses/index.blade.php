<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="section-label">{{ $isGuestView ? 'Catàleg públic' : 'Oferta formativa' }}</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Cursos</h1>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    @if ($isGuestView)
                        Vista limitada per a visitants.
                    @else
                        Llistat general de cursos disponibles.
                    @endif
                </p>
            </div>
            @auth
                @if (auth()->user()->can('courses.create'))
                    <a href="{{ route('courses.create') }}" class="btn-institutional">
                        Nou curs
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="institutional-panel p-5">
                <form method="GET" action="{{ route('courses.index') }}" class="grid gap-4 md:grid-cols-4">
                    <div>
                        <label for="nivell" class="block text-sm font-medium text-slate-700">Filtrar per nivell</label>
                        <select id="nivell" name="nivell" class="mt-1 block w-full rounded-lg border-[#cfd7e6] shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Tots els nivells</option>
                            @foreach ($levels as $value => $label)
                                <option value="{{ $value }}" @selected($selectedLevel === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($isAdmin)
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700">Estat</label>
                            <select id="status" name="status" class="mt-1 block w-full rounded-lg border-[#cfd7e6] shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="active" @selected($status === 'active')>Actius</option>
                                <option value="archived" @selected($status === 'archived')>Arxivats</option>
                                <option value="all" @selected($status === 'all')>Tots</option>
                            </select>
                        </div>
                    @endif

                    <div class="flex items-end gap-3 md:col-span-{{ $isAdmin ? '2' : '3' }}">
                        <button type="submit" class="btn-institutional">
                            Aplicar filtres
                        </button>
                        <a href="{{ route('courses.index') }}" class="btn-institutional-secondary">
                            Reiniciar
                        </a>
                    </div>
                </form>
            </div>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($courses as $course)
                    @php
                        $levelKey = $course->nivell instanceof \App\Enums\CourseLevel ? $course->nivell->value : $course->nivell;
                        $levelClasses = [
                            'inicial' => ['card' => 'course-card--inicial', 'chip' => 'status-chip-level-inicial', 'metrics' => 'course-card-metrics--inicial'],
                            'intermedi' => ['card' => 'course-card--intermedi', 'chip' => 'status-chip-level-intermedi', 'metrics' => 'course-card-metrics--intermedi'],
                            'avancat' => ['card' => 'course-card--avancat', 'chip' => 'status-chip-level-avancat', 'metrics' => 'course-card-metrics--avancat'],
                        ][$levelKey] ?? ['card' => 'course-card--inicial', 'chip' => 'status-chip-level-inicial', 'metrics' => 'course-card-metrics--inicial'];
                    @endphp

                    <article class="course-card {{ $levelClasses['card'] }}">
                        <img src="{{ $course->cover_url }}" alt="Portada del curs {{ $course->titol }}" class="course-card-cover">
                        <div class="course-card-body">
                            <div class="flex items-start justify-between gap-3">
                                <span class="{{ $levelClasses['chip'] }}">{{ $course->nivell_label }}</span>
                                @if ($course->trashed())
                                    <span class="status-chip-amber">Arxivat</span>
                                @endif
                            </div>
                            <h2 class="mt-3 text-xl font-semibold tracking-tight text-slate-950">{{ $course->titol }}</h2>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($course->descripcio, 120) }}</p>
                            <div class="course-card-metrics {{ $levelClasses['metrics'] }}">
                                <div class="flex flex-wrap gap-2">
                                    <span class="rounded-md bg-white/75 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $course->durada_hores }} h</span>
                                    <span class="rounded-md bg-white/75 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $course->lessons_count }} lliçons</span>
                                </div>
                                <p class="mt-3 text-sm text-slate-600">Responsable: <span class="font-medium text-slate-800">{{ $course->creator?->name ?? 'Sense assignar' }}</span></p>
                            </div>

                            <div class="mt-auto flex flex-wrap gap-3 pt-6">
                                @if (! $course->trashed())
                                    <a href="{{ route('courses.show', $course) }}" class="btn-institutional-secondary">
                                        {{ $isGuestView ? 'Vista previa' : 'Veure detall' }}
                                    </a>
                                @endif

                                @auth
                                    @if (! $course->trashed() && auth()->user()->can('courses.update.own') && (auth()->user()->isAdmin() || auth()->id() === $course->user_id))
                                        <a href="{{ route('courses.edit', $course) }}" class="btn-institutional-secondary">
                                            Editar
                                        </a>

                                        <form method="POST" action="{{ route('courses.destroy', $course) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-institutional-danger">
                                                Arxivar
                                            </button>
                                        </form>
                                    @elseif ($course->trashed() && auth()->user()->can('courses.restore.own') && (auth()->user()->isAdmin() || auth()->id() === $course->user_id))
                                        <form method="POST" action="{{ route('courses.restore', $course->id) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-emerald-200 bg-white px-4 py-2.5 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                                Restaurar
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="institutional-card p-6 text-sm text-slate-600 md:col-span-2 xl:col-span-3">
                        Encara no hi ha cursos registrats.
                    </div>
                @endforelse
            </div>

            <div>
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
