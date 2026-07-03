<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-sm">
    <h1 class="text-xl font-semibold mb-1">Depositar</h1>
    <p class="text-sm text-gray-500 mb-6">Saldo atual: R$ {{ number_format($wallet->balance, 2, ',', '.') }}</p>

    @if ($successMessage)
        <div class="bg-green-50 text-green-700 text-sm rounded p-3 mb-4">{{ $successMessage }}</div>
    @endif

    <form wire:submit="deposit" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1" for="amount">Valor</label>
            <input wire:model="amount" id="amount" type="number" step="0.01" min="0.01" class="w-full rounded border-gray-300 shadow-sm">
            @error('amount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="description">Descrição (opcional)</label>
            <input wire:model="description" id="description" type="text" class="w-full rounded border-gray-300 shadow-sm">
        </div>

        <button type="submit" class="w-full bg-gray-900 text-white rounded py-2 font-medium hover:bg-gray-700">
            Depositar
        </button>
    </form>
</div>
