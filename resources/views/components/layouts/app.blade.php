<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Wallet' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900 antialiased">
    @auth
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-4xl mx-auto px-4 flex items-center justify-between h-14">
                <div class="flex items-center gap-6">
                    <a href="{{ route('dashboard') }}" class="font-semibold">Wallet</a>
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Painel</a>
                    <a href="{{ route('wallet.deposit') }}" class="text-sm text-gray-600 hover:text-gray-900">Depositar</a>
                    <a href="{{ route('wallet.transfer') }}" class="text-sm text-gray-600 hover:text-gray-900">Transferir</a>
                    <a href="{{ route('wallet.history') }}" class="text-sm text-gray-600 hover:text-gray-900">Histórico</a>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">Sair</button>
                    </form>
                </div>
            </div>
        </nav>
    @endauth

    <main class="max-w-4xl mx-auto px-4 py-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
