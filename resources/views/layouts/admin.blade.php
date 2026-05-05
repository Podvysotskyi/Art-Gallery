<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => $title ?? 'Admin'])
    @livewireStyles
</head>
<body class="min-h-screen bg-zinc-100 text-zinc-900">
<div class="min-h-screen lg:grid lg:grid-cols-[260px_minmax(0,1fr)]">
    <aside class="flex flex-col border-b border-zinc-200 bg-white lg:border-b-0 lg:border-r">
        <div class="px-5 py-5">
            <a href="/" class="text-base font-semibold tracking-wide text-zinc-900" wire:navigate>
                Home
            </a>
        </div>

        <nav class="flex flex-1 flex-col px-3 pb-5">
            <div>
                <a
                    href="/admin/images"
                    class="mb-1 flex items-center rounded-md px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-900 data-current:bg-zinc-900 data-current:text-white"
                    wire:navigate
                >
                    Images
                </a>
                <a
                    href="/admin/stories"
                    class="flex items-center rounded-md px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-900 data-current:bg-zinc-900 data-current:text-white"
                    wire:navigate
                >
                    Stories
                </a>
                <a
                    href="/admin/projects"
                    class="mb-1 flex items-center rounded-md px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-900 data-current:bg-zinc-900 data-current:text-white"
                    wire:navigate
                >
                    Projects
                </a>
            </div>
            <a
                href="/logs"
                class="mt-auto flex items-center rounded-md px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-900 data-current:bg-zinc-900 data-current:text-white"
            >
                Logs
            </a>
        </nav>
    </aside>

    <div class="min-w-0">
        <header class="border-b border-zinc-200 bg-white">
            <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <h1 class="text-lg font-semibold text-zinc-900">Admin Panel</h1>
                <a
                    href="/logout"
                    class="rounded-md border border-zinc-300 px-3 py-2 text-sm font-medium text-zinc-700 transition hover:border-zinc-400 hover:text-zinc-900"
                    wire:navigate
                >
                    Logout
                </a>
            </div>
        </header>

        <main class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>
    </div>
</div>

@include('partials.toast')

@livewireScripts
@fluxScripts
</body>
</html>
