<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Sol·licituds de professor</h1>
                <p class="mt-1 text-sm text-gray-600">Panell d'administració per validar qui pot publicar i gestionar cursos.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Nom</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Correu</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Data de sol·licitud</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">Accions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($pendingUsers as $user)
                            <tr>
                                <td class="px-4 py-3 text-gray-900">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $user->teacher_requested_at?->format('d/m/Y H:i') ?? 'Sense data' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-3">
                                        <form method="POST" action="{{ route('teacher-applications.approve', $user) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                                                Aprovar
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('teacher-applications.reject', $user) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex rounded-md border border-red-300 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50">
                                                Rebutjar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-600">
                                    No hi ha sol·licituds pendents en aquest moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $pendingUsers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
