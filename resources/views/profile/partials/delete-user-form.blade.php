<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Eliminar compte
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Si elimines el compte, se suprimiran definitivament les dades associades. Fes-ho només si realment no vols continuar utilitzant la plataforma.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Eliminar compte</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                Segur que vols eliminar el teu compte?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Aquesta acció és definitiva. Introdueix la contrasenya per confirmar que vols eliminar el compte de manera permanent.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Contrasenya" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Contrasenya"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancel·lar
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    Eliminar compte
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
