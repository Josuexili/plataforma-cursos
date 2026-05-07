<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Lliçons</h1>
                <p class="mt-1 text-sm text-gray-600">Llistat de lliçons.</p>
            </div>
            <a href="{{ route('lessons.create') }}" class="btn-institutional">
                Nova lliço
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="institutional-panel p-5">
                <form method="GET" action="{{ route('lessons.index') }}" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Estat</label>
                        <select id="status" name="status" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                            <option value="active" @selected($status === 'active')>Actives</option>
                            <option value="archived" @selected($status === 'archived')>Arxivades</option>
                            <option value="all" @selected($status === 'all')>Totes</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-institutional">
                        Aplicar
                    </button>
                </form>
            </div>

            <div class="page-table">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Ordre</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Titol</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Curs</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">Accions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($lessons as $lesson)
                            <tr>
                                <td class="px-4 py-3 text-gray-700">{{ $lesson->ordre }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $lesson->titol }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $lesson->course?->titol ?? 'Sense curs' }}</td>
                                <td class="px-4 py-3 text-right">
                                    @if (! $lesson->trashed())
                                        <a href="{{ route('lessons.show', $lesson) }}" class="text-blue-800 hover:text-blue-900">Veure</a>
                                        <span class="mx-2 text-gray-300">|</span>
                                        <a href="{{ route('lessons.edit', $lesson) }}" class="text-blue-800 hover:text-blue-900">Editar</a>
                                        <span class="mx-2 text-gray-300">|</span>
                                        <form method="POST" action="{{ route('lessons.destroy', $lesson) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-700 hover:text-red-900">Arxivar</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('lessons.restore', $lesson->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-emerald-700 hover:text-emerald-900">Restaurar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-600">Encara no hi ha lliçons creades.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $lessons->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
