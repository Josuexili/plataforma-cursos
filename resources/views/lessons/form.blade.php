<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-gray-900">
            {{ $isEditing ? 'Editar lliço' : 'Crear lliço' }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="page-form">
                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <p class="font-medium">Revisa els camps del formulari.</p>
                    </div>
                @endif

                <form method="POST" action="{{ $isEditing ? route('lessons.update', $lesson) : route('lessons.store') }}" class="space-y-6">
                    @csrf
                    @if ($isEditing)
                        @method('PUT')
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Curs</label>
                        <div class="mt-1 rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-800">
                            {{ $selectedCourse?->titol ?? 'Curs no disponible' }}
                        </div>
                        <input type="hidden" name="course_id" value="{{ old('course_id', $lesson->course_id) }}">
                        @error('course_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="titol" class="block text-sm font-medium text-gray-700">Titol</label>
                        <input id="titol" name="titol" type="text" value="{{ old('titol', $lesson->titol) }}" class="mt-1 block w-full">
                        @error('titol')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contingut" class="block text-sm font-medium text-gray-700">Contingut</label>
                        <textarea id="contingut" name="contingut" rows="6" class="mt-1 block w-full">{{ old('contingut', $lesson->contingut) }}</textarea>
                        @error('contingut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-slate-500">L'ordre de la lliçó es gestiona des de la fitxa del curs.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn-institutional">
                            {{ $isEditing ? 'Guardar canvis' : 'Crear lliço' }}
                        </button>
                        <a href="{{ route($backRoute, $backRouteParams) }}" class="btn-institutional-secondary">
                            Tornar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
