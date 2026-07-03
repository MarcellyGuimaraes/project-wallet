<div class="bg-white p-6 rounded-lg shadow-sm">
    <h1 class="text-xl font-semibold mb-4">Administração de contas</h1>

    @if ($errorMessage)
        <div class="bg-red-50 text-red-700 text-sm rounded p-3 mb-4">{{ $errorMessage }}</div>
    @endif

    @if ($successMessage)
        <div class="bg-green-50 text-green-700 text-sm rounded p-3 mb-4">{{ $successMessage }}</div>
    @endif

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-500 border-b border-gray-200">
                <th class="py-2">Nome</th>
                <th class="py-2">E-mail</th>
                <th class="py-2">Documento</th>
                <th class="py-2">Saldo</th>
                <th class="py-2">Status</th>
                <th class="py-2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr class="border-b border-gray-100">
                    <td class="py-3">{{ $user->name }}</td>
                    <td class="py-3">{{ $user->email }}</td>
                    <td class="py-3">{{ $user->document }}</td>
                    <td class="py-3">R$ {{ number_format($user->wallet?->balance ?? 0, 2, ',', '.') }}</td>
                    <td class="py-3">
                        @if ($user->isBlocked())
                            <span class="text-red-600">Bloqueada</span>
                        @else
                            <span class="text-green-600">Ativa</span>
                        @endif
                    </td>
                    <td class="py-3 text-right">
                        <button
                            type="button"
                            wire:click="toggleBlock({{ $user->id }})"
                            wire:confirm="{{ $user->isBlocked() ? 'Desbloquear esta conta?' : 'Bloquear esta conta?' }}"
                            class="text-xs underline {{ $user->isBlocked() ? 'text-green-600 hover:text-green-800' : 'text-red-600 hover:text-red-800' }}"
                        >
                            {{ $user->isBlocked() ? 'Desbloquear' : 'Bloquear' }}
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-6 text-center text-gray-500">Nenhuma conta cadastrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
