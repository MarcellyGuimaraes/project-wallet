<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Wallet' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-neutral-50 text-neutral-900 antialiased">
    @auth
        <nav class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-neutral-200">
            <div class="max-w-5xl mx-auto px-4 flex items-center justify-between h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 shrink-0">
                        <span class="grid place-items-center size-7 rounded-md bg-neutral-900 text-white font-semibold text-xs shadow-sm ring-1 ring-inset ring-white/10">W</span>
                        <span class="font-semibold text-neutral-900 tracking-tight">Wallet</span>
                    </a>
                    <div class="hidden sm:flex items-center gap-6 h-16">
                        @php
                            $navLink = fn (string $route) => request()->routeIs($route)
                                ? 'text-neutral-900 border-neutral-900'
                                : 'text-neutral-500 border-transparent hover:text-neutral-900';
                        @endphp
                        <a href="{{ route('dashboard') }}" class="h-full flex items-center text-sm font-medium border-b-2 transition-colors {{ $navLink('dashboard') }}">Painel</a>
                        <a href="{{ route('wallet.deposit') }}" class="h-full flex items-center text-sm font-medium border-b-2 transition-colors {{ $navLink('wallet.deposit') }}">Depositar</a>
                        <a href="{{ route('wallet.transfer') }}" class="h-full flex items-center text-sm font-medium border-b-2 transition-colors {{ $navLink('wallet.transfer') }}">Transferir</a>
                        <a href="{{ route('wallet.history') }}" class="h-full flex items-center text-sm font-medium border-b-2 transition-colors {{ $navLink('wallet.history') }}">Histórico</a>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.users') }}" class="h-full flex items-center text-sm font-medium border-b-2 transition-colors {{ $navLink('admin.users') }}">Administração</a>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2.5">
                        <span class="grid place-items-center size-7 rounded-full bg-neutral-100 text-neutral-600 text-xs font-semibold uppercase ring-1 ring-neutral-200">
                            {{ Illuminate\Support\Str::of(auth()->user()->name)->substr(0, 1) }}
                        </span>
                        <span class="text-sm text-neutral-700">{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 text-sm text-neutral-500 hover:text-neutral-900 transition-colors">
                            Sair
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 17l5-5-5-5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12H9" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    @endauth

    <main class="max-w-5xl mx-auto px-4 py-10">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
