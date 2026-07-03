<div>
    <div class="max-w-2xl mx-auto text-center py-10">
        <span class="pb-logo inline-grid place-items-center size-11 rounded-md text-sm mb-6">W</span>

        <h1 class="font-serif text-4xl font-semibold text-platinum-100 tracking-tight leading-tight">
            Sua carteira digital, com controle total sobre cada centavo.
        </h1>

        <div class="pb-rule w-24 mx-auto mt-6"></div>

        <p class="text-lg text-platinum-300 mt-5 leading-relaxed max-w-lg mx-auto">
            Deposite, transfira e acompanhe cada movimentação em um único lugar. Todo lançamento
            fica registrado de forma imutável — nada é apagado, tudo pode ser conferido.
        </p>

        <div class="flex items-center justify-center gap-3 mt-8">
            <a href="{{ route('register') }}" class="pb-btn-gold rounded-md px-6 py-2.5 text-sm">
                Criar conta
            </a>
            <a href="{{ route('login') }}" class="pb-btn-outline rounded-md px-6 py-2.5 text-sm">
                Entrar
            </a>
        </div>
    </div>

    <div class="grid sm:grid-cols-2 gap-4 mt-6">
        <div class="pb-card p-6">
            <span class="pb-gold-text font-serif text-lg font-semibold tabular-nums">01</span>
            <p class="text-base font-semibold text-platinum-100 mt-2">Depósito</p>
            <p class="text-sm text-platinum-300 mt-1.5 leading-relaxed">
                Adicione saldo à sua carteira a qualquer momento. Se o saldo estiver negativo, o
                valor depositado abate a diferença automaticamente.
            </p>
        </div>

        <div class="pb-card p-6">
            <span class="pb-gold-text font-serif text-lg font-semibold tabular-nums">02</span>
            <p class="text-base font-semibold text-platinum-100 mt-2">Transferência</p>
            <p class="text-sm text-platinum-300 mt-1.5 leading-relaxed">
                Envie dinheiro para outro usuário pelo e-mail cadastrado. O saldo é conferido antes
                de cada operação — nunca fica negativo por uma transferência.
            </p>
        </div>

        <div class="pb-card p-6">
            <span class="pb-gold-text font-serif text-lg font-semibold tabular-nums">03</span>
            <p class="text-base font-semibold text-platinum-100 mt-2">Estorno</p>
            <p class="text-sm text-platinum-300 mt-1.5 leading-relaxed">
                Reverta um depósito ou transferência em caso de erro ou inconsistência. O valor
                volta para quem tinha antes, com o motivo registrado no histórico.
            </p>
        </div>

        <div class="pb-card p-6">
            <span class="pb-gold-text font-serif text-lg font-semibold tabular-nums">04</span>
            <p class="text-base font-semibold text-platinum-100 mt-2">Histórico auditável</p>
            <p class="text-sm text-platinum-300 mt-1.5 leading-relaxed">
                Toda movimentação — depósito, transferência ou estorno — fica registrada e visível
                a qualquer momento. Nenhum lançamento é apagado ou sobrescrito.
            </p>
        </div>
    </div>
</div>
