<div class="container" style="max-width: 600px; text-align: center; margin-top: 6rem; margin-bottom: 6rem;">
    <section class="card" style="display: flex; flex-direction: column; align-items: center; gap: 1.5rem; padding: 3rem 2rem;">
        <div style="font-size: 5rem; font-weight: 800; background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; letter-spacing: -2px;">
            500
        </div>
        <h2 style="font-size: 1.6rem; font-weight: 600;">Erro Interno do Servidor</h2>
        <p style="color: var(--text-secondary); line-height: 1.6; font-size: 0.95rem; font-weight: 300;">
            <?= !empty($message) ? htmlspecialchars($message) : 'Ocorreu um erro interno em nosso servidor. Por favor, tente novamente mais tarde.' ?>
        </p>
        <div style="margin-top: 1rem; width: 100%;">
            <a href="/" class="btn" style="text-decoration: none; width: 100%;">Voltar para a Homepage</a>
        </div>
    </section>
</div>
