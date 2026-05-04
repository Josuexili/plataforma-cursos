<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $lesson->titol }}</h1>
                <p class="mt-1 text-sm text-gray-600">Curs: {{ $lesson->course?->titol ?? 'Sense curs assignat' }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('lessons.edit', $lesson) }}" class="inline-flex rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                    Editar lliço
                </a>
                <form method="POST" action="{{ route('lessons.destroy', $lesson) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex rounded-md border border-red-300 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50">
                        Arxivar
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Ordre</p>
                        <p class="mt-2 text-base text-gray-900">{{ $lesson->ordre }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Curs vinculat</p>
                        <p class="mt-2 text-base text-gray-900">{{ $lesson->course?->titol ?? 'Sense curs' }}</p>
                    </div>
                </div>
                <div class="mt-6">
                    <p class="text-sm font-medium text-gray-500">Contingut</p>
                    <p class="mt-2 text-sm leading-6 text-gray-700 whitespace-pre-line">{{ $lesson->contingut }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
