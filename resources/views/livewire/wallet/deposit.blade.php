<div class="max-w-sm mx-auto bg-white p-8 rounded-xl border border-neutral-200 shadow-sm">
    <h1 class="text-lg font-semibold mb-1 text-neutral-900 tracking-tight">Depositar</h1>
    <p class="text-sm text-neutral-500 mb-6">
        Saldo atual:
        <span class="font-medium text-neutral-700 tabular-nums">R$ {{ number_format($wallet->balance, 2, ',', '.') }}</span>
    </p>

    @if ($successMessage)
        <div class="bg-emerald-50 text-emerald-800 text-sm rounded-md p-3 mb-4 border border-emerald-200">{{ $successMessage }}</div>
    @endif

    <form wire:submit="deposit" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1.5 text-neutral-700" for="amount">Valor</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-sm text-neutral-400">R$</span>
                <input wire:model="amount" id="amount" type="number" step="0.01" min="0.01" class="w-full rounded-md border-neutral-300 pl-9 text-sm shadow-sm transition-shadow focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900">
            </div>
            @error('amount') <p class="text-sm text-red-700 mt-1.5">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5 text-neutral-700" for="description">Descrição (opcional)</label>
            <input wire:model="description" id="description" type="text" class="w-full rounded-md border-neutral-300 text-sm shadow-sm transition-shadow focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900">
        </div>

        <button type="submit" class="w-full bg-neutral-900 text-white rounded-md py-2.5 text-sm font-medium shadow-sm hover:bg-neutral-800 active:scale-[0.99] transition-all">
            Depositar
        </button>
    </form>
</div>
