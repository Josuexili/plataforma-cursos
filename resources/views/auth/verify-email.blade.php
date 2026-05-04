<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Gràcies per registrar-te. Abans de començar, cal verificar l'adreça de correu fent clic a l'enllaç que t'hem enviat. Si no l'has rebut, te'l podem tornar a enviar.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            S'ha enviat un nou enllaç de verificació al correu que vas indicar en registrar-te.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    Tornar a enviar el correu de verificació
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Tancar sessió
            </button>
        </form>
    </div>
</x-guest-layout>
