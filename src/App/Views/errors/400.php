<div class="container" style="max-width: 600px; text-align: center; margin-top: 6rem; margin-bottom: 6rem;">
    <section class="card" style="display: flex; flex-direction: column; align-items: center; gap: 1.5rem; padding: 3rem 2rem;">
        <div style="font-size: 5rem; font-weight: 800; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; letter-spacing: -2px;">
            400
        </div>
        <h2 style="font-size: 1.6rem; font-weight: 600;">Requisição Inválida</h2>
        <p style="color: var(--text-secondary); line-height: 1.6; font-size: 0.95rem; font-weight: 300;">
            <?= !empty($message) ? htmlspecialchars($message) : 'Os parâmetros ou dados fornecidos na requisição são inválidos ou estão malformados.' ?>
        </p>
        <div style="margin-top: 1rem; width: 100%;">
            <a href="/" class="btn" style="text-decoration: none; width: 100%;">Voltar para a Homepage</a>
        </div>
    </section>
</div>
