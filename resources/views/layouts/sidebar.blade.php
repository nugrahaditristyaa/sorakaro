<div class="min-h-screen bg-gray-50">
    {{-- Mobile topbar --}}
    <header class="sticky top-0 z-30 bg-white border-b border-gray-200 sm:hidden">
        <div class="px-4 h-14 flex items-center justify-between">
            <button type="button"
                    class="inline-flex items-center p-2 text-gray-600 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                    data-drawer-target="sidebar"
                    data-drawer-toggle="sidebar"
                    aria-controls="sidebar">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <x-application-logo class="h-7 w-auto fill-current text-gray-800" />
                <span class="font-semibold text-gray-900">Sorakaro</span>
            </a>

            {{-- Profile dropdown (mobile) --}}
            <button type="button"
                    class="inline-flex items-center gap-2 px-2 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100"
                    data-dropdown-toggle="user-dropdown-mobile"
                    data-dropdown-placement="bottom-end">
                <span class="text-lg leading-none">{{ Auth::user()->getAvatarIcon() }}</span>
            </button>

            <div id="user-dropdown-mobile"
                 class="hidden z-50 my-2 w-56 bg-white rounded-xl shadow border border-gray-100">
                <div class="px-4 py-3 border-b border-gray-100">
                    <div class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</div>
                </div>
                <ul class="py-2">
                    <li>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Profile
                        </a>
                    </li>
                </ul>
                <div class="py-2 border-t border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="flex">
        {{-- Backdrop for mobile (Flowbite uses this automatically, but class below helps when sidebar open) --}}
        <div class="hidden sm:hidden"></div>

        {{-- Sidebar (Drawer) --}}
        <aside id="sidebar"
               class="fixed top-0 left-0 z-40 w-64 h-screen bg-white border-r border-gray-200
                      transition-transform -translate-x-full sm:translate-x-0"
               aria-label="Sidebar">

            <div class="h-full px-3 py-4 overflow-y-auto">
                {{-- Brand (desktop) --}}
                <a href="{{ route('dashboard') }}" class="hidden sm:flex items-center gap-2 mb-6 px-2">
                    <x-application-logo class="h-8 w-auto fill-current text-gray-800" />
                    <span class="font-semibold text-gray-900 text-lg">Sorakaro</span>
                </a>

                {{-- User mini card (desktop) --}}
                <div class="hidden sm:flex items-center gap-3 px-2 mb-6">
                    <div class="text-2xl leading-none">{{ Auth::user()->getAvatarIcon() }}</div>
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</div>
                    </div>
                    <button type="button"
                            class="ml-auto inline-flex items-center p-2 text-gray-500 rounded-lg hover:bg-gray-100"
                            data-dropdown-toggle="user-dropdown-desktop"
                            data-dropdown-placement="bottom-start">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                        </svg>
                    </button>
                </div>

                <div id="user-dropdown-desktop"
                     class="hidden z-50 w-56 bg-white rounded-xl shadow border border-gray-100">
                    <ul class="py-2">
                        <li>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Profile
                            </a>
                        </li>
                    </ul>
                    <div class="py-2 border-t border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Menu --}}
                <ul class="space-y-1 text-sm font-medium">
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center px-3 py-2 rounded-lg
                                  {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('learn.index') }}"
                           class="flex items-center px-3 py-2 rounded-lg
                                  {{ request()->routeIs('learn.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span>Learn</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center px-3 py-2 rounded-lg
                                  {{ request()->routeIs('profile.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span>Profile</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('attempts.index') }}"
                           class="flex items-center px-3 py-2 rounded-lg
                                  {{ request()->routeIs('attempts.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span>Attempts</span>
                        </a>
                    </li>

                </ul>

                <div class="mt-6 pt-4 border-t border-gray-200">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Content --}}
        <div class="flex-1 sm:ml-64 min-w-0">
            {{-- Desktop header area (optional breadcrumb/header from pages) --}}
            @isset($header)
                <header class="hidden sm:block bg-white border-b border-gray-200">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</div>
