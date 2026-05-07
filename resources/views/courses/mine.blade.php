<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="section-label">Gestió docent</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Els meus cursos</h1>
                <p class="mt-2 text-sm leading-6 text-slate-600">Espai de treball per crear, editar i mantenir els teus cursos.</p>
            </div>
            <a href="{{ route('courses.create') }}" class="btn-institutional">
                Nou curs
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('courses.index') }}" class="btn-institutional-secondary">
                    Tots els cursos
                </a>
                <a href="{{ route('courses.mine') }}" class="btn-institutional">
                    Els meus cursos
                </a>
            </div>

            <div class="institutional-panel p-5">
                <form method="GET" action="{{ route('courses.mine') }}" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700">Estat</label>
                        <select id="status" name="status" class="mt-1 block w-full rounded-lg border-[#cfd7e6] shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="active" @selected($status === 'active')>Actius</option>
                            <option value="archived" @selected($status === 'archived')>Arxivats</option>
                            <option value="all" @selected($status === 'all')>Tots</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-institutional">Aplicar</button>
                </form>
            </div>

            <div class="page-table">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Curs</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Nivell</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Durada</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Lliçons</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Estat</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">Accions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($courses as $course)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ $course->titol }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($course->descripcio, 90) }}</p>
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ $course->nivell_label }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $course->durada_hores }} h</td>
                                <td class="px-4 py-3 text-gray-700">{{ $course->lessons_count }}</td>
                                <td class="px-4 py-3">
                                    @if ($course->trashed())
                                        <span class="status-chip-amber">Arxivat</span>
                                    @else
                                        <span class="status-chip-emerald">Actiu</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        @if (! $course->trashed())
                                            <a href="{{ route('courses.show', $course) }}" class="btn-institutional-secondary">Obrir</a>
                                            <a href="{{ route('courses.edit', $course) }}" class="btn-institutional-secondary">Editar</a>
                                            <a href="{{ route('lessons.create', ['course_id' => $course->id]) }}" class="btn-institutional-secondary">Nova lliçó</a>
                                            <form method="POST" action="{{ route('courses.destroy', $course) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-institutional-danger">Arxivar</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('courses.restore', $course->id) }}">
                                                @csrf
                                                <button type="submit" class="btn-institutional-secondary">Restaurar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-600">Encara no has creat cap curs.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
