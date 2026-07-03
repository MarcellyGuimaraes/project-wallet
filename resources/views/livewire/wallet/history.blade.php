<div class="pb-card">
    <div class="px-6 py-4 border-b border-gold-500/20">
        <h1 class="font-serif text-2xl font-semibold pb-gold-text tracking-tight">Histórico de transações</h1>
    </div>

    <div class="px-6 pt-4">
        @if ($errorMessage)
            <div class="bg-red-500/10 text-red-200 text-sm rounded-md p-3 mb-4 border border-red-500/30">{{ $errorMessage }}</div>
        @endif

        @if ($successMessage)
            <div class="bg-emerald-500/10 text-emerald-200 text-sm rounded-md p-3 mb-4 border border-emerald-500/30">{{ $successMessage }}</div>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gold-300 text-xs uppercase tracking-wider border-b border-gold-500/20">
                    <th class="py-2.5 px-6 font-medium"></th>
                    <th class="py-2.5 px-6 font-medium">Data</th>
                    <th class="py-2.5 px-6 font-medium">Tipo</th>
                    <th class="py-2.5 px-6 font-medium text-right">Valor</th>
                    <th class="py-2.5 px-6 font-medium">Status</th>
                    <th class="py-2.5 px-6"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse ($transactions as $transaction)
                    @php
                        $signed = $transaction->signedAmountFor($wallet->id);
                        $statusClasses = match ($transaction->status) {
                            App\Enums\TransactionStatus::Completed => 'bg-emerald-500/10 text-emerald-200',
                            App\Enums\TransactionStatus::Reversed => 'bg-white/5 text-platinum-300',
                            App\Enums\TransactionStatus::Failed => 'bg-red-500/10 text-red-200',
                        };
                    @endphp
                    <tr>
                        <td class="py-3 px-6">
                            <span class="grid place-items-center size-7 rounded-full ring-1 {{ $signed >= 0 ? 'bg-emerald-500/10 text-emerald-300 ring-emerald-500/25' : 'bg-red-500/10 text-red-300 ring-red-500/25' }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-3">
                                    @if ($signed >= 0)
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5M5 12l7-7 7 7" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M19 12l-7 7-7-7" />
                                    @endif
                                </svg>
                            </span>
                        </td>
                        <td class="py-3 px-6 text-platinum-300 whitespace-nowrap">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-3 px-6 text-platinum-100 font-medium">{{ $transaction->type->label() }}</td>
                        <td class="py-3 px-6 font-medium tabular-nums text-right whitespace-nowrap {{ $signed >= 0 ? 'text-emerald-300' : 'text-red-300' }}">
                            {{ $signed >= 0 ? '+' : '−' }} R$ {{ number_format(abs($signed), 2, ',', '.') }}
                        </td>
                        <td class="py-3 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-medium {{ $statusClasses }}">
                                <span class="size-1.5 rounded-full bg-current"></span>
                                {{ $transaction->status->label() }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-right">
                            @if ($transaction->isReversibleBy(auth()->user()))
                                <button
                                    type="button"
                                    wire:click="reverse({{ $transaction->id }})"
                                    wire:confirm="Tem certeza que deseja reverter esta transação?"
                                    class="text-xs font-medium text-gold-300 hover:text-gold-200 underline underline-offset-2"
                                >
                                    Reverter
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-platinum-500">Nenhuma transação encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gold-500/20">
        {{ $transactions->links() }}
    </div>
</div>
