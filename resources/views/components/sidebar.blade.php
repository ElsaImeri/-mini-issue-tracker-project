<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Sidebar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .sidebar-transition {
            transition: all 0.3s ease;
        }
        .active-menu {
            background: linear-gradient(90deg, rgb(59 130 246 / 0.2) 0%, rgb(59 130 246 / 0.1) 100%);
            border-right: 4px solid #3b82f6;
            color: #3b82f6;
        }
        .menu-item:hover {
            background: linear-gradient(90deg, rgb(59 130 246 / 0.1) 0%, rgb(59 130 246 / 0.05) 100%);
        }
        @media (max-width: 768px) {
            .sidebar-mobile {
                position: fixed;
                left: -300px;
                top: 0;
                bottom: 0;
                z-index: 50;
                width: 300px;
            }
            .sidebar-mobile.open {
                left: 0;
            }
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }
            .overlay.open {
                display: block;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex" x-data="{ mobileOpen: false }">
    <div class="overlay" :class="{ 'open': mobileOpen }" @click="mobileOpen = false"></div>

    <aside class="bg-white dark:bg-gray-800 shadow-lg sidebar-transition w-64 flex-shrink-0 hidden md:flex flex-col">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-center">
            <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-blue-600 dark:text-blue-400">LOGO</a>
        </div>

        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center space-x-3">
            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div>
                <p class="font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1 px-2">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg menu-item {{ request()->routeIs('dashboard') ? 'active-menu' : 'text-gray-700 dark:text-gray-300' }}">
                        <i class="bi bi-speedometer2 mr-3 text-lg"></i>
                        <span class="font-medium">{{ __('Dashboard') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('projects.index') }}" class="flex items-center p-3 rounded-lg menu-item {{ request()->routeIs('projects.*') ? 'active-menu' : 'text-gray-700 dark:text-gray-300' }}">
                        <i class="bi bi-kanban mr-3 text-lg"></i>
                        <span class="font-medium">{{ __('Projects') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('issues.index') }}" class="flex items-center p-3 rounded-lg menu-item {{ request()->routeIs('issues.*') ? 'active-menu' : 'text-gray-700 dark:text-gray-300' }}">
                        <i class="bi bi-exclamation-circle mr-3 text-lg"></i>
                        <span class="font-medium">{{ __('Issues') }}</span>
                    </a>
                </li>
            </ul>

            <div class="px-4 my-4">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Settings</p>
            </div>

            <ul class="space-y-1 px-2">
                <li>
                    <a href="{{ route('profile.edit') }}" class="flex items-center p-3 rounded-lg menu-item text-gray-700 dark:text-gray-300">
                        <i class="bi bi-person mr-3 text-lg"></i>
                        <span class="font-medium">{{ __('Profile') }}</span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full p-3 rounded-lg menu-item text-gray-700 dark:text-gray-300">
                            <i class="bi bi-box-arrow-right mr-3 text-lg"></i>
                            <span class="font-medium">{{ __('Log Out') }}</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <div class="p-4 border-t border-gray-200 dark:border-gray-700 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400">v1.0.0</p>
        </div>
    </aside>

    <aside class="sidebar-mobile bg-white dark:bg-gray-800 shadow-lg sidebar-transition w-64 flex-shrink-0 flex flex-col md:hidden" :class="{ 'open': mobileOpen }">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-blue-600 dark:text-blue-400">LOGO</a>
            <button @click="mobileOpen = false" class="p-1 rounded-md text-gray-400 hover:text-gray-500">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center space-x-3">
            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div>
                <p class="font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1 px-2">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg menu-item {{ request()->routeIs('dashboard') ? 'active-menu' : 'text-gray-700 dark:text-gray-300' }}" @click="mobileOpen = false">
                        <i class="bi bi-speedometer2 mr-3 text-lg"></i>
                        <span class="font-medium">{{ __('Dashboard') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('projects.index') }}" class="flex items-center p-3 rounded-lg menu-item {{ request()->routeIs('projects.*') ? 'active-menu' : 'text-gray-700 dark:text-gray-300' }}" @click="mobileOpen = false">
                        <i class="bi bi-kanban mr-3 text-lg"></i>
                        <span class="font-medium">{{ __('Projects') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('issues.index') }}" class="flex items-center p-3 rounded-lg menu-item {{ request()->routeIs('issues.*') ? 'active-menu' : 'text-gray-700 dark:text-gray-300' }}" @click="mobileOpen = false">
                        <i class="bi bi-exclamation-circle mr-3 text-lg"></i>
                        <span class="font-medium">{{ __('Issues') }}</span>
                    </a>
                </li>
            </ul>

            <div class="px-4 my-4">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Settings</p>
            </div>

            <ul class="space-y-1 px-2">
                <li>
                    <a href="{{ route('profile.edit') }}" class="flex items-center p-3 rounded-lg menu-item text-gray-700 dark:text-gray-300" @click="mobileOpen = false">
                        <i class="bi bi-person mr-3 text-lg"></i>
                        <span class="font-medium">{{ __('Profile') }}</span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full p-3 rounded-lg menu-item text-gray-700 dark:text-gray-300">
                            <i class="bi bi-box-arrow-right mr-3 text-lg"></i>
                            <span class="font-medium">{{ __('Log Out') }}</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <div class="p-4 border-t border-gray-200 dark:border-gray-700 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400">v1.0.0</p>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <nav class="bg-white dark:bg-gray-800 shadow-sm">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <button @click="mobileOpen = true" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none md:hidden">
                            <i class="bi bi-list text-xl"></i>
                        </button>
                        <div class="hidden md:flex items-center space-x-4 ml-6">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">
                                {{ __('Projects') }}
                            </x-nav-link>
                            <x-nav-link :href="route('issues.index')" :active="request()->routeIs('issues.*')">
                                {{ __('Issues') }}
                            </x-nav-link>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="flex items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
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
                                    <i class="bi bi-person mr-2"></i> {{ __('Profile') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="bi bi-box-arrow-right mr-2"></i> {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>