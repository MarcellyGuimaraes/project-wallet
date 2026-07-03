<div class="min-h-[75vh] flex flex-col items-center justify-center">
    <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-10">
        <span class="pb-logo grid place-items-center size-9 rounded-md text-sm">W</span>
        <span class="font-serif font-semibold text-lg text-platinum-100 tracking-wide">Wallet</span>
    </a>

    <div class="pb-card w-full max-w-sm p-8">
        <h1 class="font-serif text-2xl font-semibold mb-1 pb-gold-text tracking-tight">Entrar</h1>
        <p class="text-sm text-platinum-300 mb-6">Acesse sua carteira digital.</p>

        <form wire:submit="login" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1.5 text-platinum-100" for="email">E-mail</label>
                <input wire:model="email" id="email" type="email" class="pb-input px-3 py-2.5 text-sm">
                @error('email') <p class="text-sm text-red-300 mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5 text-platinum-100" for="password">Senha</label>
                <input wire:model="password" id="password" type="password" class="pb-input px-3 py-2.5 text-sm">
                @error('password') <p class="text-sm text-red-300 mt-1.5">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="pb-btn-gold w-full rounded-md py-2.5 text-sm">
                Entrar
            </button>
        </form>

        <p class="text-sm text-platinum-300 mt-6 text-center">
            Não tem conta? <a href="{{ route('register') }}" class="text-gold-300 font-medium hover:text-gold-200 hover:underline underline-offset-2">Criar conta</a>
        </p>
    </div>
</div>
