<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-sm">
    <h1 class="text-xl font-semibold mb-6">Criar conta</h1>

    <form wire:submit="register" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1" for="name">Nome</label>
            <input wire:model="name" id="name" type="text" class="w-full rounded border-gray-300 shadow-sm">
            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="email">E-mail</label>
            <input wire:model="email" id="email" type="email" class="w-full rounded border-gray-300 shadow-sm">
            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="document">CPF ou CNPJ</label>
            <input wire:model="document" id="document" type="text" class="w-full rounded border-gray-300 shadow-sm" placeholder="Somente números">
            @error('document') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="initial_balance">Saldo inicial (opcional)</label>
            <input wire:model="initial_balance" id="initial_balance" type="number" step="0.01" min="0" class="w-full rounded border-gray-300 shadow-sm">
            @error('initial_balance') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="password">Senha</label>
            <input wire:model="password" id="password" type="password" class="w-full rounded border-gray-300 shadow-sm">
            @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="password_confirmation">Confirmar senha</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" class="w-full rounded border-gray-300 shadow-sm">
        </div>

        <button type="submit" class="w-full bg-gray-900 text-white rounded py-2 font-medium hover:bg-gray-700">
            Criar conta
        </button>
    </form>

    <p class="text-sm text-gray-600 mt-4">
        Já tem conta? <a href="{{ route('login') }}" class="text-gray-900 underline">Entrar</a>
    </p>
</div>
