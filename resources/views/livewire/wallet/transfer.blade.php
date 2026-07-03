<div class="pb-card max-w-sm mx-auto p-8">
    <h1 class="font-serif text-2xl font-semibold mb-1 pb-gold-text tracking-tight">Transferir</h1>
    <p class="text-sm text-platinum-300 mb-6">
        Saldo atual:
        <span class="font-medium text-platinum-100 tabular-nums">R$ {{ number_format($wallet->balance, 2, ',', '.') }}</span>
    </p>

    @if ($successMessage)
        <div class="bg-emerald-500/10 text-emerald-200 text-sm rounded-md p-3 mb-4 border border-emerald-500/30">{{ $successMessage }}</div>
    @endif

    <form wire:submit="transfer" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1.5 text-platinum-100" for="recipient_email">E-mail do destinatário</label>
            <input wire:model="recipient_email" id="recipient_email" type="email" class="pb-input px-3 py-2.5 text-sm">
            @error('recipient_email') <p class="text-sm text-red-300 mt-1.5">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5 text-platinum-100" for="amount">Valor</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-sm text-gold-300">R$</span>
                <input wire:model="amount" id="amount" type="number" step="0.01" min="0.01" class="pb-input pl-9 pr-3 py-2.5 text-sm">
            </div>
            @error('amount') <p class="text-sm text-red-300 mt-1.5">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5 text-platinum-100" for="description">Descrição (opcional)</label>
            <input wire:model="description" id="description" type="text" class="pb-input px-3 py-2.5 text-sm">
        </div>

        <button type="submit" class="pb-btn-gold w-full rounded-md py-2.5 text-sm">
            Transferir
        </button>
    </form>
</div>
