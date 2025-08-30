<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
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
            
            /* Rregullime për sidebar fiks */
            .desktop-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                height: 100vh;
                width: 16rem; /* 64px */
                overflow-y: auto;
                z-index: 30;
            }
            
            .main-content-container {
                margin-left: 16rem; /* 64px */
                width: calc(100% - 16rem);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
            
            /* Stilizim për header fix */
            .top-navigation-bar {
                position: sticky;
                top: 0;
                z-index: 40;
                background-color: rgb(255 255 255 / 0.95);
            }
            
            @media (prefers-color-scheme: dark) {
                .top-navigation-bar {
                    background-color: rgb(31 41 55 / 0.95);
                }
            }
            
            @media (max-width: 768px) {
                .desktop-sidebar {
                    display: none;
                }
                
                .main-content-container {
                    margin-left: 0;
                    width: 100%;
                }
                
                .sidebar-mobile {
                    position: fixed;
                    left: -300px;
                    top: 0;
                    bottom: 0;
                    z-index: 50;
                    width: 300px;
                    height: 100vh;
                    overflow-y: auto;
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
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased" x-data="{ mobileOpen: false, profileOpen: false }">
        <!-- Overlay for mobile -->
        <div class="overlay" :class="{ 'open': mobileOpen }" @click="mobileOpen = false; profileOpen = false;"></div>

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex">
            <!-- Desktop Sidebar -->
            <aside class="desktop-sidebar bg-white dark:bg-gray-800 shadow-lg sidebar-transition flex-shrink-0 hidden md:flex flex-col">
                <!-- Logo -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-center">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ config('app.name', 'Laravel') }}</a>
                </div>

                <!-- User Profile -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <!-- Navigation Menu -->
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
                        <!-- New Tags Menu Item -->
                        <li>
                            <a href="{{ route('tags.index') }}" class="flex items-center p-3 rounded-lg menu-item {{ request()->routeIs('tags.*') ? 'active-menu' : 'text-gray-700 dark:text-gray-300' }}">
                                <i class="bi bi-tags mr-3 text-lg"></i>
                                <span class="font-medium">{{ __('Tags') }}</span>
                            </a>
                        </li>
                        <!-- New Users Menu Item -->
                        <li>
                            <a href="{{ route('users.index') }}" class="flex items-center p-3 rounded-lg menu-item {{ request()->routeIs('users.*') ? 'active-menu' : 'text-gray-700 dark:text-gray-300' }}">
                                <i class="bi bi-people mr-3 text-lg"></i>
                                <span class="font-medium">{{ __('Users') }}</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Menu section divider -->
                    <div class="px-4 my-4">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Settings</p>
                    </div>

                    <ul class="space-y-1 px-2">
                        <li>
                            <a href="{{ route('profile.edit') }}" class="flex items-center p-3 rounded-lg menu-item {{ request()->routeIs('profile.edit') ? 'active-menu' : 'text-gray-700 dark:text-gray-300' }}">
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

                <!-- Sidebar footer -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">v1.0.0</p>
                </div>
            </aside>

            <!-- Mobile sidebar -->
            <aside class="sidebar-mobile bg-white dark:bg-gray-800 shadow-lg sidebar-transition flex-shrink-0 flex flex-col md:hidden" :class="{ 'open': mobileOpen }">
                <!-- Logo -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ config('app.name', 'Laravel') }}</a>
                    <button @click="mobileOpen = false" class="p-1 rounded-md text-gray-400 hover:text-gray-500">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>

                <!-- User Profile -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <!-- Navigation Menu -->
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
                        <!-- New Tags Menu Item for Mobile -->
                        <li>
                            <a href="{{ route('tags.index') }}" class="flex items-center p-3 rounded-lg menu-item {{ request()->routeIs('tags.*') ? 'active-menu' : 'text-gray-700 dark:text-gray-300' }}" @click="mobileOpen = false">
                                <i class="bi bi-tags mr-3 text-lg"></i>
                                <span class="font-medium">{{ __('Tags') }}</span>
                            </a>
                        </li>
                        <!-- New Users Menu Item for Mobile -->
                        <li>
                            <a href="{{ route('users.index') }}" class="flex items-center p-3 rounded-lg menu-item {{ request()->routeIs('users.*') ? 'active-menu' : 'text-gray-700 dark:text-gray-300' }}" @click="mobileOpen = false">
                                <i class="bi bi-people mr-3 text-lg"></i>
                                <span class="font-medium">{{ __('Users') }}</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Menu section divider -->
                    <div class="px-4 my-4">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Settings</p>
                    </div>

                    <ul class="space-y-1 px-2">
                        <li>
                            <a href="{{ route('profile.edit') }}" class="flex items-center p-3 rounded-lg menu-item {{ request()->routeIs('profile.edit') ? 'active-menu' : 'text-gray-700 dark:text-gray-300' }}" @click="mobileOpen = false">
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

                <!-- Sidebar footer -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">v1.0.0</p>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="main-content-container">
                <!-- Top navigation bar - Fixed -->
                <nav class="top-navigation-bar shadow-sm border-b border-gray-200 dark:border-gray-700">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex items-center">
                                <button @click="mobileOpen = true" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none md:hidden">
                                    <i class="bi bi-list text-xl"></i>
                                </button>
                                <!-- Header title based on current page -->
                                <div class="ml-4 md:ml-6">
                                    <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        @if(request()->routeIs('dashboard'))
                                            {{ __('Dashboard') }}
                                        @elseif(request()->routeIs('projects.*'))
                                            {{ __('Projects') }}
                                        @elseif(request()->routeIs('issues.*'))
                                            {{ __('Issues') }}
                                        @elseif(request()->routeIs('tags.*'))
                                            {{ __('Tags') }}
                                        @elseif(request()->routeIs('users.*'))
                                            {{ __('Users') }}
                                        @elseif(request()->routeIs('profile.edit'))
                                            {{ __('Profile') }}
                                        @else
                                            {{ config('app.name', 'Laravel') }}
                                        @endif
                                    </h1>
                                </div>
                            </div>

                            <!-- User dropdown (Breeze style) -->
                            <div class="flex items-center ms-6 relative">
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
                                            {{ __('Profile') }}
                                        </x-dropdown-link>

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf

                                            <x-dropdown-link :href="route('logout')"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 md:p-6">
                    <div class="mx-auto max-w-7xl py-4 md:py-6 sm:px-6 lg:px-8">
                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Profile Form Content -->
                        @if(request()->routeIs('profile.edit'))
                            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                @include('profile.partials.update-profile-information-form')
                                
                                <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-8">
                                    @include('profile.partials.update-password-form')
                                </div>
                                
                                <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-8">
                                    @include('profile.partials.delete-user-form')
                                </div>
                            </div>
                        @else
                            <!-- Other page content -->
                            @yield('content')
                        @endif
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>