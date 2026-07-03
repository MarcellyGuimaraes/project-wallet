<div>
    <div class="max-w-2xl mx-auto text-center py-10">
        <span class="inline-grid place-items-center size-11 rounded-md bg-neutral-900 text-white font-semibold text-sm mb-6 shadow-sm ring-1 ring-inset ring-white/10">W</span>

        <h1 class="text-4xl font-semibold text-neutral-900 tracking-tight leading-tight">
            Sua carteira digital, com controle total sobre cada centavo.
        </h1>

        <p class="text-base text-neutral-500 mt-4 leading-relaxed max-w-lg mx-auto">
            Deposite, transfira e acompanhe cada movimentação em um único lugar. Todo lançamento
            fica registrado de forma imutável — nada é apagado, tudo pode ser conferido.
        </p>

        <div class="flex items-center justify-center gap-3 mt-8">
            <a href="{{ route('register') }}" class="bg-neutral-900 text-white rounded-md px-5 py-2.5 text-sm font-medium shadow-sm hover:bg-neutral-800 active:scale-[0.99] transition-all">
                Criar conta
            </a>
            <a href="{{ route('login') }}" class="text-neutral-700 rounded-md px-5 py-2.5 text-sm font-medium border border-neutral-300 hover:border-neutral-900 hover:text-neutral-900 transition-colors">
                Entrar
            </a>
        </div>
    </div>

    <div class="grid sm:grid-cols-2 gap-4 mt-6">
        <div class="bg-white rounded-xl border border-neutral-200 shadow-sm p-6">
            <span class="text-xs font-semibold text-neutral-300 tabular-nums">01</span>
            <p class="text-sm font-semibold text-neutral-900 mt-2">Depósito</p>
            <p class="text-sm text-neutral-500 mt-1.5 leading-relaxed">
                Adicione saldo à sua carteira a qualquer momento. Se o saldo estiver negativo, o
                valor depositado abate a diferença automaticamente.
            </p>
        </div>

        <div class="bg-white rounded-xl border border-neutral-200 shadow-sm p-6">
            <span class="text-xs font-semibold text-neutral-300 tabular-nums">02</span>
            <p class="text-sm font-semibold text-neutral-900 mt-2">Transferência</p>
            <p class="text-sm text-neutral-500 mt-1.5 leading-relaxed">
                Envie dinheiro para outro usuário pelo e-mail cadastrado. O saldo é conferido antes
                de cada operação — nunca fica negativo por uma transferência.
            </p>
        </div>

        <div class="bg-white rounded-xl border border-neutral-200 shadow-sm p-6">
            <span class="text-xs font-semibold text-neutral-300 tabular-nums">03</span>
            <p class="text-sm font-semibold text-neutral-900 mt-2">Estorno</p>
            <p class="text-sm text-neutral-500 mt-1.5 leading-relaxed">
                Reverta um depósito ou transferência em caso de erro ou inconsistência. O valor
                volta para quem tinha antes, com o motivo registrado no histórico.
            </p>
        </div>

        <div class="bg-white rounded-xl border border-neutral-200 shadow-sm p-6">
            <span class="text-xs font-semibold text-neutral-300 tabular-nums">04</span>
            <p class="text-sm font-semibold text-neutral-900 mt-2">Histórico auditável</p>
            <p class="text-sm text-neutral-500 mt-1.5 leading-relaxed">
                Toda movimentação — depósito, transferência ou estorno — fica registrada e visível
                a qualquer momento. Nenhum lançamento é apagado ou sobrescrito.
            </p>
        </div>
    </div>
</div>
