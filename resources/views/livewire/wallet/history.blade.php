<div class="bg-white p-6 rounded-lg shadow-sm">
    <h1 class="text-xl font-semibold mb-4">Histórico de transações</h1>

    @if ($errorMessage)
        <div class="bg-red-50 text-red-700 text-sm rounded p-3 mb-4">{{ $errorMessage }}</div>
    @endif

    @if ($successMessage)
        <div class="bg-green-50 text-green-700 text-sm rounded p-3 mb-4">{{ $successMessage }}</div>
    @endif

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-500 border-b border-gray-200">
                <th class="py-2">Data</th>
                <th class="py-2">Tipo</th>
                <th class="py-2">Valor</th>
                <th class="py-2">Status</th>
                <th class="py-2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                @php $signed = $transaction->signedAmountFor($wallet->id); @endphp
                <tr class="border-b border-gray-100">
                    <td class="py-3">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-3">{{ $transaction->type->label() }}</td>
                    <td class="py-3 {{ $signed >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $signed >= 0 ? '+' : '-' }} R$ {{ number_format(abs($signed), 2, ',', '.') }}
                    </td>
                    <td class="py-3">{{ $transaction->status->label() }}</td>
                    <td class="py-3 text-right">
                        @if ($transaction->isReversibleBy(auth()->user()))
                            <button
                                type="button"
                                wire:click="reverse({{ $transaction->id }})"
                                wire:confirm="Tem certeza que deseja reverter esta transação?"
                                class="text-xs text-red-600 hover:text-red-800 underline"
                            >
                                Reverter
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-gray-500">Nenhuma transação encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
