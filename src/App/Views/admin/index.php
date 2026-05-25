<!-- Header Section -->
<section class="mb-lg text-center md:text-left">
    <h1 class="text-display-lg font-display-lg text-on-surface mb-xs">Painel Administrativo</h1>
    <p class="text-body-lg font-body-lg text-on-surface-variant">Monitore o status da infraestrutura e execute testes de integração do servidor.</p>
</section>

<!-- Status Grid -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-lg">
    <!-- Web Server Status -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex flex-col gap-4 shadow-sm">
        <span class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Servidor Web</span>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_8px_#10b981]"></span>
            <span class="text-body-md font-semibold">Apache / PHP <?= phpversion() ?></span>
        </div>
    </div>
    
    <!-- Database Status -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex flex-col gap-4 shadow-sm">
        <span class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Banco de Dados</span>
        <div class="flex items-center gap-2">
            <?php if ($db_connected): ?>
                <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_8px_#10b981]"></span>
                <span class="text-body-md font-semibold text-on-surface">MariaDB (Conectado)</span>
            <?php else: ?>
                <span class="w-3 h-3 rounded-full bg-rose-500 shadow-[0_0_8px_#ef4444]"></span>
                <span class="text-body-md font-semibold text-error">Desconectado</span>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- GD Library Status -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 flex flex-col gap-4 shadow-sm">
        <span class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Biblioteca GD</span>
        <div class="flex items-center gap-2">
            <?php if ($gd_loaded): ?>
                <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_8px_#10b981]"></span>
                <span class="text-body-md font-semibold">Ativa (<?= htmlspecialchars($gd_version) ?>)</span>
            <?php else: ?>
                <span class="w-3 h-3 rounded-full bg-rose-500 shadow-[0_0_8px_#ef4444]"></span>
                <span class="text-body-md font-semibold text-error">Inativa</span>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Mail Tester Section -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 md:p-8 mb-lg shadow-sm">
    <div class="flex items-center gap-2 mb-4">
        <span class="material-symbols-outlined text-primary text-[24px]">mail</span>
        <h2 class="text-headline-md font-bold text-on-surface">Teste de Envio de E-mails</h2>
    </div>
    <p class="text-body-sm md:text-body-md text-on-surface-variant mb-6 max-w-2xl leading-relaxed">
        O Camagru precisa enviar e-mails para validação de contas de usuário e recuperação de senhas. Use o formulário abaixo para disparar um e-mail de teste via MSMTP direto do container para o painel do Mailpit.
    </p>
    <form method="POST" action="/admin" class="flex flex-col gap-4 items-start">
        <button type="submit" name="send_email" class="bg-primary text-on-primary px-6 py-3 rounded-lg font-label-md text-label-md hover:bg-primary-container transition-colors duration-200 shadow-sm active:scale-95 transition-transform duration-150">
            Enviar E-mail de Teste
        </button>
    </form>
    
    <?php if ($email_sent === true): ?>
        <div class="mt-4 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 rounded-lg text-body-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <span><strong>E-mail enviado!</strong> Verifique a caixa de entrada no seu <a href="http://localhost:8025" target="_blank" class="underline font-bold hover:text-emerald-900 dark:hover:text-emerald-200">Mailpit Dashboard</a>.</span>
        </div>
    <?php elseif ($email_sent === false): ?>
        <div class="mt-4 p-4 bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-400 rounded-lg text-body-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">error</span>
            <span><strong>Erro ao enviar e-mail:</strong> <?= htmlspecialchars($email_error) ?></span>
        </div>
    <?php endif; ?>
</section>

<!-- Quick Links Section -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 md:p-8 shadow-sm">
    <div class="flex items-center gap-2 mb-6">
        <span class="material-symbols-outlined text-primary text-[24px]">link</span>
        <h2 class="text-headline-md font-bold text-on-surface">Atalhos de Desenvolvimento</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="/" class="group flex flex-col p-4 border border-outline-variant rounded-xl hover:border-primary transition-colors duration-300 bg-surface-container-lowest">
            <span class="text-label-md font-bold text-primary group-hover:underline">Voltar para a Home</span>
            <span class="text-body-sm text-secondary mt-1">Ir para a galeria pública de fotos</span>
        </a>
        <a href="http://localhost:8085" target="_blank" class="group flex flex-col p-4 border border-outline-variant rounded-xl hover:border-primary transition-colors duration-300 bg-surface-container-lowest">
            <span class="text-label-md font-bold text-primary group-hover:underline">phpMyAdmin</span>
            <span class="text-body-sm text-secondary mt-1">Gerenciar tabelas do banco de dados (Porta 8085)</span>
        </a>
        <a href="http://localhost:8025" target="_blank" class="group flex flex-col p-4 border border-outline-variant rounded-xl hover:border-primary transition-colors duration-300 bg-surface-container-lowest">
            <span class="text-label-md font-bold text-primary group-hover:underline">Mailpit Dashboard</span>
            <span class="text-body-sm text-secondary mt-1">Ver a caixa de e-mails de teste (Porta 8025)</span>
        </a>
    </div>
</section>
