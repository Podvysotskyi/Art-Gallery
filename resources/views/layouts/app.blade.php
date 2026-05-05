<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    @livewireStyles
</head>
<body class="min-h-screen bg-[#f1f1f1] text-[#131416]">
<header class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col">
    <div class="flex h-20 pt-6 items-end lg:justify-between justify-normal">
        <div class="flex items-center grow">
            <div class="flex flex-col lg:grow-0 grow lg:text-left text-center">
                <a class="shrink-0 text-2xl tracking-wide capitalize mb-2" href="/" wire:navigate>
                    The Painted World of Anastasia
                </a>
            </div>
            <div class="hidden lg:block">
                <div class="ml-10 flex items-baseline space-x-4">
                    <a href="/gallery" wire:navigate wire:current="text-red-400 hover:text-red-400" class="hover:text-black
     text-gray-700 py-2 text-base font-medium uppercase">Gallery</a>
                    <a href="/projects" wire:navigate wire:current="text-red-400 hover:text-red-400" class="hover:text-black
     text-gray-700 py-2 text-base font-medium uppercase">Art Projects</a>
                    <a href="/about" wire:navigate wire:current="text-red-400 hover:text-red-400" class="hover:text-black
     text-gray-700 py-2 text-base font-medium uppercase">About</a>
                    <a href="/contacts" wire:navigate wire:current="text-red-400 hover:text-red-400" class="hover:text-black
     text-gray-700 py-2 text-base font-medium uppercase">Contact</a>
                    @auth
                        <a href="/admin" wire:navigate wire:current="text-red-400 hover:text-red-400" class="hover:text-black
     text-gray-700 py-2 text-base font-medium uppercase">Admin</a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="flex lg:items-center">
            @auth
                <a href="/logout"
                   class="rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:border-gray-400 hover:text-black"
                   wire:navigate>
                    Logout
                </a>
            @else
                <a href="/login"
                   class="rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:border-gray-400 hover:text-black"
                   wire:navigate>
                    Login
                </a>
            @endauth
        </div>
    </div>
    <div class="text-xs text-gray-400 mb-2 lg:text-left text-center">
        by <span class="uppercase">Anastasia Podvysotska</span>
    </div>
</header>

<main class="mx-auto w-full max-w-7xl px-6 pb-12 lg:px-10">
    {{ $slot }}
</main>

@include('partials.toast')

@livewireScripts
@fluxScripts
</body>
</html>
