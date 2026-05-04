<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Inscripcions</h1>
                <p class="mt-1 text-sm text-gray-600">Llistat base de les inscripcions registrades al sistema.</p>
            </div>
            <a href="{{ route('enrollments.create') }}" class="inline-flex rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                Nova inscripcio
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
                <form method="GET" action="{{ route('enrollments.index') }}" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Estat</label>
                        <select id="status" name="status" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                            <option value="active" @selected($status === 'active')>Actives</option>
                            <option value="archived" @selected($status === 'archived')>Arxivades</option>
                            <option value="all" @selected($status === 'all')>Totes</option>
                        </select>
                    </div>
                    <button type="submit" class="inline-flex rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                        Aplicar
                    </button>
                </form>
            </div>

            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Usuari</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Curs</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Data d'inscripcio</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">Accions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($enrollments as $enrollment)
                            <tr>
                                <td class="px-4 py-3 text-gray-900">{{ $enrollment->user?->name ?? 'Sense usuari' }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $enrollment->course?->titol ?? 'Sense curs' }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $enrollment->data_inscripcio?->format('d/m/Y H:i') ?? 'Pendent' }}</td>
                                <td class="px-4 py-3 text-right">
                                    @if (! $enrollment->trashed())
                                        <a href="{{ route('enrollments.show', $enrollment) }}" class="text-gray-700 hover:text-gray-900">Veure</a>
                                        <span class="mx-2 text-gray-300">|</span>
                                        <a href="{{ route('enrollments.edit', $enrollment) }}" class="text-gray-700 hover:text-gray-900">Editar</a>
                                        <span class="mx-2 text-gray-300">|</span>
                                        <form method="POST" action="{{ route('enrollments.destroy', $enrollment) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-700 hover:text-red-900">Arxivar</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('enrollments.restore', $enrollment->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-emerald-700 hover:text-emerald-900">Restaurar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-600">Encara no hi ha inscripcions registrades.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $enrollments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
