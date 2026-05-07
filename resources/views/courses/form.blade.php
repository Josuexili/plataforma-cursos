<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-gray-900">
            {{ $isEditing ? 'Editar curs' : 'Crear curs' }}
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

                <form method="POST" action="{{ $isEditing ? route('courses.update', $course) : route('courses.store') }}" class="space-y-6">
                    @csrf
                    @if ($isEditing)
                        @method('PUT')
                    @endif

                    <div>
                        <label for="titol" class="block text-sm font-medium text-gray-700">Titol</label>
                        <input id="titol" name="titol" type="text" value="{{ old('titol', $course->titol) }}" class="mt-1 block w-full">
                        @error('titol')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="descripcio" class="block text-sm font-medium text-gray-700">Descripcio</label>
                        <textarea id="descripcio" name="descripcio" rows="5" class="mt-1 block w-full">{{ old('descripcio', $course->descripcio) }}</textarea>
                        @error('descripcio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="nivell" class="block text-sm font-medium text-gray-700">Nivell</label>
                            <select id="nivell" name="nivell" class="mt-1 block w-full">
                                <option value="">Selecciona un nivell</option>
                                @foreach ($levels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('nivell', $course->nivell?->value) === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nivell')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="durada_hores" class="block text-sm font-medium text-gray-700">Durada (hores)</label>
                            <input id="durada_hores" name="durada_hores" type="number" min="1" value="{{ old('durada_hores', $course->durada_hores) }}" class="mt-1 block w-full">
                            @error('durada_hores')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn-institutional">
                            {{ $isEditing ? 'Guardar canvis' : 'Crear curs' }}
                        </button>
                        <a href="{{ route('courses.index') }}" class="btn-institutional-secondary">
                            Tornar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
