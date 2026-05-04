<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-gray-900">
            {{ $isEditing ? 'Editar curs' : 'Crear curs' }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
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
                        <input id="titol" name="titol" type="text" value="{{ old('titol', $course->titol) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                        @error('titol')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="descripcio" class="block text-sm font-medium text-gray-700">Descripcio</label>
                        <textarea id="descripcio" name="descripcio" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">{{ old('descripcio', $course->descripcio) }}</textarea>
                        @error('descripcio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="nivell" class="block text-sm font-medium text-gray-700">Nivell</label>
                            <select id="nivell" name="nivell" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
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
                            <input id="durada_hores" name="durada_hores" type="number" min="1" value="{{ old('durada_hores', $course->durada_hores) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                            @error('durada_hores')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="inline-flex rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                            {{ $isEditing ? 'Guardar canvis' : 'Crear curs' }}
                        </button>
                        <a href="{{ route('courses.index') }}" class="inline-flex rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Tornar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
