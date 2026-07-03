<div class="min-h-[75vh] flex flex-col items-center justify-center py-8">
    <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-10">
        <span class="pb-logo grid place-items-center size-9 rounded-md text-sm">W</span>
        <span class="font-serif font-semibold text-lg text-platinum-100 tracking-wide">Wallet</span>
    </a>

    <div class="pb-card w-full max-w-sm p-8">
        <h1 class="font-serif text-2xl font-semibold mb-1 pb-gold-text tracking-tight">Criar conta</h1>
        <p class="text-sm text-platinum-300 mb-6">Abra sua carteira digital em segundos.</p>

        <form wire:submit="register" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1.5 text-platinum-100" for="name">Nome</label>
                <input wire:model="name" id="name" type="text" class="pb-input px-3 py-2.5 text-sm">
                @error('name') <p class="text-sm text-red-300 mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5 text-platinum-100" for="email">E-mail</label>
                <input wire:model="email" id="email" type="email" class="pb-input px-3 py-2.5 text-sm">
                @error('email') <p class="text-sm text-red-300 mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5 text-platinum-100" for="document">CPF ou CNPJ</label>
                <input wire:model="document" id="document" type="text" class="pb-input px-3 py-2.5 text-sm" placeholder="Somente números">
                @error('document') <p class="text-sm text-red-300 mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5 text-platinum-100" for="initial_balance">Saldo inicial (opcional)</label>
                <input wire:model="initial_balance" id="initial_balance" type="number" step="0.01" min="0" class="pb-input px-3 py-2.5 text-sm">
                @error('initial_balance') <p class="text-sm text-red-300 mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5 text-platinum-100" for="password">Senha</label>
                <input wire:model="password" id="password" type="password" class="pb-input px-3 py-2.5 text-sm">
                @error('password') <p class="text-sm text-red-300 mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5 text-platinum-100" for="password_confirmation">Confirmar senha</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" class="pb-input px-3 py-2.5 text-sm">
            </div>

            <button type="submit" class="pb-btn-gold w-full rounded-md py-2.5 text-sm">
                Criar conta
            </button>
        </form>

        <p class="text-sm text-platinum-300 mt-6 text-center">
            Já tem conta? <a href="{{ route('login') }}" class="text-gold-300 font-medium hover:text-gold-200 hover:underline underline-offset-2">Entrar</a>
        </p>
    </div>
</div>
