<nav x-data="{ open: false }" class="relative z-[90] border-b border-slate-200 bg-white">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-[4.35rem] justify-between">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <x-application-logo class="block h-12 w-auto" />
                        <div class="hidden border-l border-slate-200 pl-3 sm:block">
                            <p class="text-sm font-semibold text-slate-800">Plataforma de cursos</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-7 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        Inici
                    </x-nav-link>
                    <x-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index') || request()->routeIs('courses.show')">
                        Cursos
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            Dashboard
                        </x-nav-link>
                        @if (auth()->user()->can('courses.create'))
                            <x-nav-link :href="route('courses.mine')" :active="request()->routeIs('courses.mine') || request()->routeIs('courses.create') || request()->routeIs('courses.edit') || request()->routeIs('lessons.create') || request()->routeIs('lessons.edit')">
                                Els meus cursos
                            </x-nav-link>
                        @endif
                        <x-nav-link :href="route('enrollments.index')" :active="request()->routeIs('enrollments.*')">
                            Inscripcions
                        </x-nav-link>
                        @if (auth()->user()->can('teacher-requests.review'))
                            <x-nav-link :href="route('teacher-applications.index')" :active="request()->routeIs('teacher-applications.*')">
                                Sol·licituds
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium leading-4 text-slate-800 transition ease-in-out duration-150 hover:bg-slate-50 focus:outline-none">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Perfil
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    Tancar sessio
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-4 text-sm">
                        <a href="{{ route('login') }}" class="font-medium text-slate-700 hover:text-blue-900">Entrar</a>
                        <a href="{{ route('register') }}" class="btn-institutional">Registrar-se</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-xl p-2 text-slate-500 transition duration-150 ease-in-out hover:bg-slate-100 hover:text-slate-900 focus:bg-slate-100 focus:text-slate-900 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                Inici
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index') || request()->routeIs('courses.show')">
                Cursos
            </x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Dashboard
                </x-responsive-nav-link>
                @if (auth()->user()->can('courses.create'))
                    <x-responsive-nav-link :href="route('courses.mine')" :active="request()->routeIs('courses.mine') || request()->routeIs('courses.create') || request()->routeIs('courses.edit') || request()->routeIs('lessons.create') || request()->routeIs('lessons.edit')">
                        Els meus cursos
                    </x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('enrollments.index')" :active="request()->routeIs('enrollments.*')">
                    Inscripcions
                </x-responsive-nav-link>
                @if (auth()->user()->can('teacher-requests.review'))
                    <x-responsive-nav-link :href="route('teacher-applications.index')" :active="request()->routeIs('teacher-applications.*')">
                        Sol·licituds
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="border-t border-slate-200 pt-4 pb-1">
                <div class="px-4">
                    <div class="text-base font-semibold text-slate-900">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-slate-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Perfil
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            Tancar sessio
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="space-y-2 border-t border-slate-200 px-4 py-4">
                <x-responsive-nav-link :href="route('login')">
                    Entrar
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">
                    Registrar-se
                </x-responsive-nav-link>
            </div>
        @endauth
    </div>
</nav>
