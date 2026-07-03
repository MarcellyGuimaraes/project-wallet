<div class="min-h-[75vh] flex flex-col items-center justify-center">
    <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-10">
        <span class="grid place-items-center size-9 rounded-md bg-neutral-900 text-white font-semibold text-sm shadow-sm ring-1 ring-inset ring-white/10">W</span>
        <span class="font-semibold text-lg text-neutral-900 tracking-tight">Wallet</span>
    </a>

    <div class="w-full max-w-sm bg-white p-8 rounded-xl border border-neutral-200 shadow-[0_1px_2px_rgba(0,0,0,0.04),0_8px_24px_-12px_rgba(0,0,0,0.08)]">
        <h1 class="text-lg font-semibold mb-1 text-neutral-900 tracking-tight">Entrar</h1>
        <p class="text-sm text-neutral-500 mb-6">Acesse sua carteira digital.</p>

        <form wire:submit="login" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1.5 text-neutral-700" for="email">E-mail</label>
                <input wire:model="email" id="email" type="email" class="w-full rounded-md border-neutral-300 text-sm shadow-sm transition-shadow focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900">
                @error('email') <p class="text-sm text-red-700 mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5 text-neutral-700" for="password">Senha</label>
                <input wire:model="password" id="password" type="password" class="w-full rounded-md border-neutral-300 text-sm shadow-sm transition-shadow focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900">
                @error('password') <p class="text-sm text-red-700 mt-1.5">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full bg-neutral-900 text-white rounded-md py-2.5 text-sm font-medium shadow-sm hover:bg-neutral-800 active:scale-[0.99] transition-all">
                Entrar
            </button>
        </form>

        <p class="text-sm text-neutral-500 mt-6 text-center">
            Não tem conta? <a href="{{ route('register') }}" class="text-neutral-900 font-medium hover:underline underline-offset-2">Criar conta</a>
        </p>
    </div>
</div>
