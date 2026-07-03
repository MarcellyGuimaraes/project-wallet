<div class="space-y-6">
    <div class="relative rounded-xl bg-gradient-to-br from-neutral-900 to-neutral-950 p-8 shadow-lg shadow-neutral-900/10 ring-1 ring-inset ring-white/10">
        <div class="flex items-start justify-between">
            <div class="h-7 w-10 rounded bg-gradient-to-br from-neutral-500 to-neutral-700 ring-1 ring-inset ring-white/20"></div>
            <span class="text-[11px] font-medium uppercase tracking-widest text-neutral-500">Wallet</span>
        </div>

        <p class="text-xs font-medium uppercase tracking-widest text-neutral-400 mt-6">Saldo disponível</p>
        <p class="text-5xl font-semibold mt-2 tabular-nums tracking-tight {{ $wallet->balance < 0 ? 'text-red-400' : 'text-white' }}">
            R$ {{ number_format($wallet->balance, 2, ',', '.') }}
        </p>
        <p class="text-xs text-neutral-500 mt-2 tracking-[0.2em] tabular-nums">
            •••• •••• •••• {{ str_pad((string) $wallet->id, 4, '0', STR_PAD_LEFT) }}
        </p>

        <div class="flex gap-3 mt-7">
            <a href="{{ route('wallet.deposit') }}" class="inline-flex items-center gap-1.5 bg-white text-neutral-900 text-sm font-medium rounded-md px-4 py-2.5 shadow-sm hover:bg-neutral-100 active:scale-[0.99] transition-all">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M19 12l-7 7-7-7" />
                </svg>
                Depositar
            </a>
            <a href="{{ route('wallet.transfer') }}" class="inline-flex items-center gap-1.5 text-white text-sm font-medium rounded-md px-4 py-2.5 border border-neutral-700 hover:border-neutral-500 active:scale-[0.99] transition-all">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M7 7h10v10" />
                </svg>
                Transferir
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-neutral-200 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-200">
            <h2 class="font-semibold text-neutral-900 text-sm">Últimas movimentações</h2>
            <a href="{{ route('wallet.history') }}" class="text-sm text-neutral-500 hover:text-neutral-900 transition-colors">Ver histórico completo</a>
        </div>

        <div class="divide-y divide-neutral-100">
            @forelse ($recentTransactions as $transaction)
                @php $signed = $transaction->signedAmountFor($wallet->id); @endphp
                <div class="flex items-center justify-between px-6 py-3.5">
                    <div class="flex items-center gap-3">
                        <span class="grid place-items-center size-8 rounded-full {{ $signed >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-3.5">
                                @if ($signed >= 0)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5M5 12l7-7 7 7" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M19 12l-7 7-7-7" />
                                @endif
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-medium text-neutral-900">{{ $transaction->type->label() }}</p>
                            <p class="text-xs text-neutral-500 mt-0.5">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-medium tabular-nums {{ $signed >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                        {{ $signed >= 0 ? '+' : '−' }} R$ {{ number_format(abs($signed), 2, ',', '.') }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-neutral-500 px-6 py-6">Nenhuma movimentação ainda.</p>
            @endforelse
        </div>
    </div>
</div>
