<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-sm">
    <h1 class="text-xl font-semibold mb-6">Entrar</h1>

    <form wire:submit="login" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1" for="email">E-mail</label>
            <input wire:model="email" id="email" type="email" class="w-full rounded border-gray-300 shadow-sm">
            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="password">Senha</label>
            <input wire:model="password" id="password" type="password" class="w-full rounded border-gray-300 shadow-sm">
            @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full bg-gray-900 text-white rounded py-2 font-medium hover:bg-gray-700">
            Entrar
        </button>
    </form>

    <p class="text-sm text-gray-600 mt-4">
        Não tem conta? <a href="{{ route('register') }}" class="text-gray-900 underline">Criar conta</a>
    </p>
</div>
