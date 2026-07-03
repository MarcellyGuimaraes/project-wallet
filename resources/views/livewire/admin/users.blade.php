<div class="pb-card">
    <div class="px-6 py-4 border-b border-gold-500/20">
        <h1 class="font-serif text-2xl font-semibold pb-gold-text tracking-tight">Administração de contas</h1>
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
                    <th class="py-2.5 px-6 font-medium">Nome</th>
                    <th class="py-2.5 px-6 font-medium">E-mail</th>
                    <th class="py-2.5 px-6 font-medium">Documento</th>
                    <th class="py-2.5 px-6 font-medium text-right">Saldo</th>
                    <th class="py-2.5 px-6 font-medium">Status</th>
                    <th class="py-2.5 px-6"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse ($users as $user)
                    <tr>
                        <td class="py-3.5 px-6 text-platinum-100 font-medium">{{ $user->name }}</td>
                        <td class="py-3.5 px-6 text-platinum-300">{{ $user->email }}</td>
                        <td class="py-3.5 px-6 text-platinum-300">{{ $user->document }}</td>
                        <td class="py-3.5 px-6 text-platinum-100 font-medium tabular-nums text-right whitespace-nowrap">R$ {{ number_format($user->wallet?->balance ?? 0, 2, ',', '.') }}</td>
                        <td class="py-3.5 px-6">
                            @if ($user->isBlocked())
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-medium bg-red-500/10 text-red-200">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-3">
                                        <rect x="5" y="11" width="14" height="9" rx="2" />
                                        <path stroke-linecap="round" d="M8 11V7a4 4 0 0 1 8 0v4" />
                                    </svg>
                                    Bloqueada
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-medium bg-emerald-500/10 text-emerald-200">
                                    <span class="size-1.5 rounded-full bg-current"></span>
                                    Ativa
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            <button
                                type="button"
                                wire:click="toggleBlock({{ $user->id }})"
                                wire:confirm="{{ $user->isBlocked() ? 'Desbloquear esta conta?' : 'Bloquear esta conta?' }}"
                                class="text-xs font-medium text-gold-300 hover:text-gold-200 underline underline-offset-2"
                            >
                                {{ $user->isBlocked() ? 'Desbloquear' : 'Bloquear' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-platinum-500">Nenhuma conta cadastrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gold-500/20">
        {{ $users->links() }}
    </div>
</div>
