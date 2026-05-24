<div class="container">
    <header>
        <h1>Camagru</h1>
        <p>Painel Administrativo e Status do Sistema ⚙️</p>
    </header>

    <!-- Status da Infraestrutura -->
    <section class="card">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
            Status do Sistema
        </h2>
        <div class="status-grid">
            <div class="status-item">
                <span class="status-label">Servidor Web</span>
                <span class="status-value">
                    <span class="indicator success"></span>
                    Apache / PHP <?= phpversion() ?>
                </span>
            </div>

            <div class="status-item">
                <span class="status-label">Banco de Dados</span>
                <span class="status-value">
                    <?php if ($db_connected): ?>
                        <span class="indicator success"></span> Conectado
                    <?php else: ?>
                        <span class="indicator danger"></span> Desconectado
                    <?php endif; ?>
                </span>
            </div>

            <div class="status-item">
                <span class="status-label">Biblioteca GD</span>
                <span class="status-value">
                    <?php if ($gd_loaded): ?>
                        <span class="indicator success"></span> Ativa (<?= htmlspecialchars($gd_version) ?>)
                    <?php else: ?>
                        <span class="indicator danger"></span> Inativa
                    <?php endif; ?>
                </span>
            </div>
        </div>
    </section>

    <!-- Testador de E-mails (Mailpit) -->
    <section class="card">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            Teste de Envio de E-mails
        </h2>
        <form method="POST">
            <p style="color: var(--text-secondary); margin-bottom: 1rem; font-size: 0.95rem; line-height: 1.5;">
                O Camagru precisa enviar e-mails para confirmação de conta e recuperação de senha. Use o botão abaixo para enviar um e-mail de teste direto deste container. Você poderá visualizá-lo abrindo o painel do Mailpit.
            </p>
            <button type="submit" name="send_email" class="btn">
                Enviar E-mail de Teste
            </button>
        </form>

        <?php if ($email_sent === true): ?>
            <div class="alert success">
                <strong>E-mail enviado!</strong> Verifique a caixa de entrada em seu <a href="http://localhost:8025" target="_blank" style="color: #6ee7b7; font-weight: 600; text-decoration: underline;">Mailpit Dashboard</a>.
            </div>
        <?php elseif ($email_sent === false): ?>
            <div class="alert danger">
                <strong>Erro ao enviar e-mail:</strong><br>
                <?= htmlspecialchars($email_error) ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Atalhos Rápidos -->
    <section class="card">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
            Links de Acesso Rápido
        </h2>
        <div class="links-grid">
            <a href="/" class="link-card">
                <span class="title">Voltar para a Home</span>
                <span class="url">Ir para a homepage pública</span>
            </a>
            <a href="http://localhost:8085" target="_blank" class="link-card">
                <span class="title">phpMyAdmin (Banco)</span>
                <span class="url">localhost:8085</span>
            </a>
            <a href="http://localhost:8025" target="_blank" class="link-card">
                <span class="title">Mailpit Dashboard</span>
                <span class="url">localhost:8025</span>
            </a>
        </div>
    </section>
</div>
