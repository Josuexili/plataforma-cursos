<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="section-label">Fitxa del curs</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">{{ $course->titol }}</h1>
                <p class="mt-2 text-sm text-slate-600">Responsable: {{ $course->creator?->name ?? 'Sense assignar' }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @auth
                    @can('update', $course)
                        <a href="{{ route('courses.mine') }}" class="btn-institutional-secondary">
                            Els meus cursos
                        </a>
                        <a href="{{ route('lessons.create', ['course_id' => $course->id]) }}" class="btn-institutional-secondary">
                            Nova lliçó
                        </a>
                        <a href="{{ route('courses.edit', $course) }}" class="btn-institutional">
                            Editar curs
                        </a>
                        <form method="POST" action="{{ route('courses.destroy', $course) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-institutional-danger">
                                Arxivar
                            </button>
                        </form>
                    @endcan
                @endauth
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">
            @php
                $lessonCollection = $canViewContent ? ($isStaff ? $manageableLessons : $fullLessons) : $previewLessons;
                $lessonCards = $lessonCollection->map(fn ($lesson) => [
                    'id' => $lesson->id,
                    'ordre' => $lesson->ordre,
                    'titol' => $lesson->titol,
                    'summary' => \Illuminate\Support\Str::limit($lesson->contingut, 150),
                    'content' => $lesson->contingut,
                    'editUrl' => $isStaff ? route('lessons.edit', $lesson) : null,
                    'archiveUrl' => $isStaff ? route('lessons.destroy', $lesson) : null,
                ])->values();
            @endphp

            <section class="institutional-panel overflow-hidden">
                <img src="{{ $course->cover_url }}" alt="Portada del curs {{ $course->titol }}" class="h-72 w-full object-cover md:h-80">
                <div class="p-6">
                    <div class="grid gap-6 md:grid-cols-3">
                        <div>
                            <p class="section-label">Nivell</p>
                            <p class="mt-2 text-base font-medium text-slate-950">{{ $course->nivell_label }}</p>
                        </div>
                        <div>
                            <p class="section-label">Durada</p>
                            <p class="mt-2 text-base font-medium text-slate-950">{{ $course->durada_hores }} hores</p>
                        </div>
                        <div>
                            <p class="section-label">Lliçons previstes</p>
                            <p class="mt-2 text-base font-medium text-slate-950">{{ $course->lessons->count() }}</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <p class="section-label">Descripció</p>
                        <p class="mt-2 text-sm leading-7 text-slate-600">{{ $course->descripcio }}</p>
                    </div>
                </div>
            </section>

            <section id="lessons-section" class="institutional-panel p-6 scroll-mt-28" x-data="{ openLessonId: null }">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950">Lliçons del curs</h2>
                        @if ($canViewContent)
                            <p class="mt-2 text-sm text-slate-600">Tens accés complet al contingut d'aquest curs.</p>
                            <p class="mt-2 text-sm text-slate-600">
                                @if ($isStaff)
                                    Desplega cada lliçó per revisar el contingut i reordena-les amb les fletxes laterals.
                                @else
                                    Desplega cada lliçó per consultar-ne el contingut.
                                @endif
                            </p>
                        @else
                            <p class="mt-2 text-sm text-slate-600">Vista general del temari disponible.</p>
                        @endif
                    </div>

                    @if ($isStaff)
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('lessons.create', ['course_id' => $course->id]) }}" class="btn-institutional">
                                Afegir lliçó
                            </a>
                        </div>
                    @endif
                </div>

                @unless ($canViewContent)
                    <div class="preview-panel mt-4">
                        <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-900">Vista prèvia del curs</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-700">
                            @if ($isGuest)
                                Pots veure una petita vista prèvia, però cal registrar-se i inscriure's al curs per accedir al contingut complet.
                            @else
                                Estàs autenticat, però encara no estàs inscrit en aquest curs. Inscriu-te per veure totes les lliçons.
                            @endif
                        </p>
                    </div>
                @endunless

                <div class="lesson-board">
                    @if ($canViewContent && $lessonCards->isNotEmpty())
                        <div class="sr-only">
                            @foreach ($lessonCards as $lessonCard)
                                <p>{{ $lessonCard['content'] }}</p>
                            @endforeach
                        </div>
                    @endif

                    @foreach ($lessonCards as $index => $lessonCard)
                        <article class="lesson-card">
                            <div class="lesson-card__row">
                                @if ($isStaff)
                                    <div class="lesson-card__controls">
                                        @if (! $loop->first)
                                            <form method="POST" action="{{ route('lessons.move', $lessonCard['id']) }}">
                                                @csrf
                                                <input type="hidden" name="direction" value="up">
                                                <button type="submit" class="lesson-arrow-button" aria-label="Pujar lliçó">
                                                    <span>↑</span>
                                                </button>
                                            </form>
                                        @else
                                            <div class="lesson-arrow-placeholder" aria-hidden="true"></div>
                                        @endif

                                        @if (! $loop->last)
                                            <form method="POST" action="{{ route('lessons.move', $lessonCard['id']) }}">
                                                @csrf
                                                <input type="hidden" name="direction" value="down">
                                                <button type="submit" class="lesson-arrow-button" aria-label="Baixar lliçó">
                                                    <span>↓</span>
                                                </button>
                                            </form>
                                        @else
                                            <div class="lesson-arrow-placeholder" aria-hidden="true"></div>
                                        @endif
                                    </div>
                                @endif

                                <div class="lesson-card__meta">
                                    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Lliçó {{ $index + 1 }}</p>
                                            <h3 class="lesson-card__title">{{ $lessonCard['titol'] }}</h3>
                                            <p class="lesson-card__summary">{{ $lessonCard['summary'] }}</p>
                                        </div>

                                        <div class="lesson-card__actions">
                                            <button type="button" class="btn-institutional-secondary" @click="openLessonId = openLessonId === {{ $lessonCard['id'] }} ? null : {{ $lessonCard['id'] }}">
                                                <span x-show="openLessonId !== {{ $lessonCard['id'] }}">Desplegar</span>
                                                <span x-show="openLessonId === {{ $lessonCard['id'] }}">Amagar</span>
                                            </button>
                                            @if ($isStaff)
                                                <a href="{{ $lessonCard['editUrl'] }}" class="btn-institutional-secondary">Editar</a>
                                                <form method="POST" action="{{ $lessonCard['archiveUrl'] }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-institutional-danger">Arxivar</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="lesson-card__body" x-cloak x-show="openLessonId === {{ $lessonCard['id'] }}" x-transition.opacity.duration.150ms>
                                <p>{{ $lessonCard['content'] }}</p>
                            </div>
                        </article>
                    @endforeach

                    @if ($lessonCards->isEmpty())
                        <div class="page-empty">
                            Encara no hi ha lliçons en aquest curs.
                        </div>
                    @endif
                </div>

                @unless ($canViewContent)
                    <div class="mt-6 flex flex-wrap gap-3">
                        @if ($isGuest)
                            <a href="{{ route('register') }}" class="btn-institutional">
                                Registrar-me
                            </a>
                            <a href="{{ route('login') }}" class="btn-institutional-secondary">
                                Iniciar sessio
                            </a>
                        @elseif ($canEnroll)
                            <form method="POST" action="{{ route('courses.enroll', $course) }}">
                                @csrf
                                <button type="submit" class="btn-institutional">
                                    Inscriure'm al curs
                                </button>
                            </form>
                        @endif
                    </div>
                @endunless
            </section>

            @if ($isStaff && $enrolledUsers)
                <section class="institutional-panel p-6">
                    <h2 class="text-xl font-semibold text-slate-950">Usuaris inscrits</h2>
                    <p class="mt-2 text-sm text-slate-600">Llistat d'usuaris inscrits al curs.</p>

                    <div class="page-table mt-4">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Nom</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Correu</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Data d'inscripcio</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($enrolledUsers as $enrollment)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-900">{{ $enrollment->user?->name ?? 'Sense nom' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $enrollment->user?->email ?? 'Sense correu' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $enrollment->data_inscripcio?->format('d/m/Y H:i') ?? 'Pendent' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center text-gray-600">Encara no hi ha usuaris inscrits en aquest curs.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $enrolledUsers->links() }}
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
