<div class="space-y-6">
    <div class="pb-card-hero p-8">
        <div class="relative z-[1] flex items-start justify-between">
            <div class="pb-logo h-7 w-10 rounded"></div>
            <span class="font-serif text-[11px] font-medium uppercase tracking-[0.25em] text-gold-300">Wallet</span>
        </div>

        <p class="relative z-[1] text-xs font-medium uppercase tracking-[0.25em] text-platinum-300 mt-6">Saldo disponível</p>
        <p class="relative z-[1] font-serif text-5xl font-semibold mt-2 tabular-nums tracking-tight {{ $wallet->balance < 0 ? 'text-red-300' : 'pb-gold-text' }}">
            R$ {{ number_format($wallet->balance, 2, ',', '.') }}
        </p>
        <p class="relative z-[1] text-xs text-platinum-500 mt-2 tracking-[0.2em] tabular-nums">
            •••• •••• •••• {{ str_pad((string) $wallet->id, 4, '0', STR_PAD_LEFT) }}
        </p>

        <div class="relative z-[1] flex gap-3 mt-7">
            <a href="{{ route('wallet.deposit') }}" class="pb-btn-gold inline-flex items-center gap-1.5 text-sm rounded-md px-4 py-2.5">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M19 12l-7 7-7-7" />
                </svg>
                Depositar
            </a>
            <a href="{{ route('wallet.transfer') }}" class="pb-btn-outline inline-flex items-center gap-1.5 text-sm rounded-md px-4 py-2.5">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M7 7h10v10" />
                </svg>
                Transferir
            </a>
        </div>
    </div>

    <div class="pb-card">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gold-500/20">
            <h2 class="font-serif font-semibold text-platinum-100 text-base tracking-wide">Últimas movimentações</h2>
            <a href="{{ route('wallet.history') }}" class="text-sm text-platinum-300 hover:text-gold-200 transition-colors">Ver histórico completo</a>
        </div>

        <div class="divide-y divide-white/5">
            @forelse ($recentTransactions as $transaction)
                @php $signed = $transaction->signedAmountFor($wallet->id); @endphp
                <div class="flex items-center justify-between px-6 py-3.5">
                    <div class="flex items-center gap-3">
                        <span class="grid place-items-center size-8 rounded-full ring-1 {{ $signed >= 0 ? 'bg-emerald-500/10 text-emerald-300 ring-emerald-500/25' : 'bg-red-500/10 text-red-300 ring-red-500/25' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-3.5">
                                @if ($signed >= 0)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5M5 12l7-7 7 7" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M19 12l-7 7-7-7" />
                                @endif
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-medium text-platinum-100">{{ $transaction->type->label() }}</p>
                            <p class="text-xs text-platinum-500 mt-0.5">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-medium tabular-nums {{ $signed >= 0 ? 'text-emerald-300' : 'text-red-300' }}">
                        {{ $signed >= 0 ? '+' : '−' }} R$ {{ number_format(abs($signed), 2, ',', '.') }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-platinum-300 px-6 py-6">Nenhuma movimentação ainda.</p>
            @endforelse
        </div>
    </div>
</div>
