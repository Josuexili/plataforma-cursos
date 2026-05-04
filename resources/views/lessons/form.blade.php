<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-gray-900">
            {{ $isEditing ? 'Editar lliço' : 'Crear lliço' }}
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

                <form method="POST" action="{{ $isEditing ? route('lessons.update', $lesson) : route('lessons.store') }}" class="space-y-6">
                    @csrf
                    @if ($isEditing)
                        @method('PUT')
                    @endif

                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700">Curs</label>
                        <select id="course_id" name="course_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                            <option value="">Selecciona un curs</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @selected(old('course_id', $lesson->course_id) == $course->id)>
                                    {{ $course->titol }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="titol" class="block text-sm font-medium text-gray-700">Titol</label>
                        <input id="titol" name="titol" type="text" value="{{ old('titol', $lesson->titol) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                        @error('titol')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="ordre" class="block text-sm font-medium text-gray-700">Ordre</label>
                            <input id="ordre" name="ordre" type="number" min="1" value="{{ old('ordre', $lesson->ordre) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                            @error('ordre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="contingut" class="block text-sm font-medium text-gray-700">Contingut</label>
                        <textarea id="contingut" name="contingut" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">{{ old('contingut', $lesson->contingut) }}</textarea>
                        @error('contingut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="inline-flex rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                            {{ $isEditing ? 'Guardar canvis' : 'Crear lliço' }}
                        </button>
                        <a href="{{ route('lessons.index') }}" class="inline-flex rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Tornar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
