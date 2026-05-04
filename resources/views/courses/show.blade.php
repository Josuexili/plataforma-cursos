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
                    @can('is-owner', $course)
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
            <section class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-[0_18px_45px_-30px_rgba(15,23,42,0.4)]">
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

            <section class="institutional-panel p-6">
                <h2 class="text-xl font-semibold text-slate-950">Contingut del curs</h2>

                @if ($canViewContent)
                    <p class="mt-2 text-sm text-slate-600">Tens accés complet al contingut d'aquest curs.</p>
                    <div class="mt-4 space-y-3">
                        @forelse ($fullLessons as $lesson)
                            <div class="rounded-2xl border border-blue-100 bg-white px-4 py-4 shadow-sm">
                                <p class="text-sm font-semibold text-slate-950">{{ $lesson->ordre }}. {{ $lesson->titol }}</p>
                                <p class="mt-2 text-sm leading-7 text-slate-600 whitespace-pre-line">{{ $lesson->contingut }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-600">Aquest curs encara no te lliçons registrades.</p>
                        @endforelse
                    </div>
                @else
                    <div class="mt-4 rounded-2xl border border-blue-100 bg-blue-50/80 p-5">
                        <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-900">Vista prèvia del curs</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-700">
                            @if ($isGuest)
                                Pots veure una petita vista prèvia, però cal registrar-se i inscriure's al curs per accedir al contingut complet.
                            @else
                                Estàs autenticat, però encara no estàs inscrit en aquest curs. Inscriu-te per veure totes les lliçons.
                            @endif
                        </p>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse ($previewLessons as $lesson)
                            <div class="rounded-2xl border border-dashed border-blue-200 bg-white/90 px-4 py-4">
                                <p class="text-sm font-semibold text-slate-950">{{ $lesson->ordre }}. {{ $lesson->titol }}</p>
                                <p class="mt-2 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($lesson->contingut, 120) }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-600">Aquest curs encara no te lliçons registrades.</p>
                        @endforelse
                    </div>

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
                @endif
            </section>

            @if ($isStaff && $enrolledUsers)
                <section class="institutional-panel p-6">
                    <h2 class="text-xl font-semibold text-slate-950">Usuaris inscrits</h2>
                    <p class="mt-2 text-sm text-slate-600">Aquest llistat és visible per al professor responsable i per a l'administració.</p>

                    <div class="mt-4 overflow-hidden rounded-2xl border border-blue-100">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-blue-50/80">
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
