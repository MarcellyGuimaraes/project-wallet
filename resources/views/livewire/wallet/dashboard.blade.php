<div class="space-y-6">
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <p class="text-sm text-gray-500">Saldo atual</p>
        <p class="text-3xl font-semibold {{ $wallet->balance < 0 ? 'text-red-600' : 'text-gray-900' }}">
            R$ {{ number_format($wallet->balance, 2, ',', '.') }}
        </p>

        <div class="flex gap-3 mt-4">
            <a href="{{ route('wallet.deposit') }}" class="bg-gray-900 text-white text-sm rounded px-4 py-2 hover:bg-gray-700">Depositar</a>
            <a href="{{ route('wallet.transfer') }}" class="bg-gray-100 text-gray-900 text-sm rounded px-4 py-2 hover:bg-gray-200">Transferir</a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold">Últimas movimentações</h2>
            <a href="{{ route('wallet.history') }}" class="text-sm text-gray-600 hover:text-gray-900">Ver histórico completo</a>
        </div>

        @forelse ($recentTransactions as $transaction)
            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                <div>
                    <p class="text-sm font-medium">{{ $transaction->type->label() }}</p>
                    <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @php $signed = $transaction->signedAmountFor($wallet->id); @endphp
                <span class="text-sm font-medium {{ $signed >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $signed >= 0 ? '+' : '-' }} R$ {{ number_format(abs($signed), 2, ',', '.') }}
                </span>
            </div>
        @empty
            <p class="text-sm text-gray-500">Nenhuma movimentação ainda.</p>
        @endforelse
    </div>
</div>
