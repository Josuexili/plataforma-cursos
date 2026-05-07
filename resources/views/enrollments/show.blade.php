<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Detall de la inscripcio</h1>
                <p class="mt-1 text-sm text-gray-600">Informació de la inscripció.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('enrollments.edit', $enrollment) }}" class="btn-institutional">
                    Editar inscripcio
                </a>
                <form method="POST" action="{{ route('enrollments.destroy', $enrollment) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-institutional-danger">
                        Arxivar
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="institutional-panel p-6">
                <div class="grid gap-6 md:grid-cols-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Usuari</p>
                        <p class="mt-2 text-base text-gray-900">{{ $enrollment->user?->name ?? 'Sense usuari' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Curs</p>
                        <p class="mt-2 text-base text-gray-900">{{ $enrollment->course?->titol ?? 'Sense curs' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Data</p>
                        <p class="mt-2 text-base text-gray-900">{{ $enrollment->data_inscripcio?->format('d/m/Y H:i') ?? 'Pendent' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
