<style>
    /* Estilos específicos da Homepage */
    .navbar {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        position: fixed;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        z-index: 100;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .navbar .logo {
        font-size: 1.6rem;
        font-weight: 800;
        background: linear-gradient(135deg, #a5b4fc 0%, #6366f1 50%, #4338ca 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-decoration: none;
        letter-spacing: -1px;
    }

    .nav-links {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }

    .nav-links a {
        color: var(--text-secondary);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
        font-size: 0.95rem;
    }

    .nav-links a:hover {
        color: var(--text-primary);
    }

    .nav-links a.btn-nav {
        background-color: var(--primary);
        color: var(--text-primary);
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        transition: background-color 0.2s, transform 0.2s;
    }

    .nav-links a.btn-nav:hover {
        background-color: var(--primary-hover);
        transform: translateY(-1px);
    }

    /* Container Principal */
    .hero-section {
        text-align: center;
        margin-top: 8rem;
        margin-bottom: 4rem;
        padding: 0 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.5rem;
    }

    .hero-badge {
        background: rgba(99, 102, 241, 0.1);
        border: 1px solid rgba(99, 102, 241, 0.2);
        color: #818cf8;
        padding: 0.4rem 1rem;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        animation: pulse 2s infinite;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -2px;
        background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 50%, #94a3b8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        max-width: 650px;
    }

    .hero-subtitle {
        color: var(--text-secondary);
        font-size: 1.2rem;
        max-width: 500px;
        line-height: 1.6;
        font-weight: 300;
    }

    .hero-cta {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .btn-secondary {
        background-color: transparent;
        border: 1px solid var(--border);
        color: var(--text-primary);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary:hover {
        background-color: rgba(255, 255, 255, 0.05);
        border-color: var(--text-secondary);
    }

    .btn-primary {
        text-decoration: none;
    }

    /* Galeria Mock */
    .gallery-preview {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto 6rem auto;
        padding: 0 1.5rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--border);
        padding-bottom: 1rem;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 600;
    }

    .grid-photos {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }

    .photo-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    }

    .photo-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4), 0 0 15px var(--glow);
        border-color: var(--primary);
    }

    .photo-img {
        position: relative;
        width: 100%;
        height: 250px;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .photo-img svg {
        transition: transform 0.5s ease;
    }

    .photo-card:hover .photo-img svg {
        transform: scale(1.08);
    }

    .photo-overlay-filter {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(4px);
        padding: 0.3rem 0.7rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #a5b4fc;
    }

    .photo-info {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .photo-user {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.85rem;
        color: white;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.95rem;
    }

    .photo-caption {
        color: var(--text-secondary);
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .photo-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 0.75rem;
        margin-top: 0.25rem;
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    .meta-actions {
        display: flex;
        gap: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        cursor: pointer;
        transition: color 0.2s;
    }

    .meta-item:hover {
        color: var(--text-primary);
    }

    .meta-item.like:hover {
        color: #f43f5e;
    }

    /* Recursos Section */
    .features-section {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto 6rem auto;
        padding: 0 1.5rem;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
    }

    .feature-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        border-color: rgba(99, 102, 241, 0.3);
    }

    .feature-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(99, 102, 241, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
    }

    .feature-title {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .feature-desc {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.5;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.6;
        }
    }
</style>

<!-- Barra de Navegação Superior -->
<nav class="navbar">
    <a href="/" class="logo">Camagru</a>
    <div class="nav-links">
        <a href="#galeria">Galeria</a>
        <a href="#recursos">Recursos</a>
        <a href="/admin">Admin (Status)</a>
        <a href="#" class="btn-nav">Entrar</a>
    </div>
</nav>

<!-- Seção Hero -->
<header class="hero-section">
    <div class="hero-badge">Disponível em Docker 🐳</div>
    <h1 class="hero-title">Capture & Compartilhe com Estilo Próprio</h1>
    <p class="hero-subtitle">Crie composições fotográficas exclusivas, aplique filtros de imagem artísticos na sua webcam e compartilhe com uma comunidade vibrante.</p>
    
    <div class="hero-cta">
        <a href="#" class="btn btn-primary">Começar Agora</a>
        <a href="#galeria" class="btn-secondary">
            Ver Galeria
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        </a>
    </div>
</header>

<!-- Seção de Recursos -->
<section id="recursos" class="features-section">
    <div class="section-header">
        <h2 class="section-title">Por que o Camagru?</h2>
    </div>
    <div class="features-grid">
        <!-- Card 1 -->
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
            </div>
            <h3 class="feature-title">Filtros via Webcam</h3>
            <p class="feature-desc">Utilize sua câmera em tempo real para sobrepor filtros, molduras e stickers. Tire fotos fantásticas direto do navegador.</p>
        </div>
        <!-- Card 2 -->
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            </div>
            <h3 class="feature-title">Likes e Comentários</h3>
            <p class="feature-desc">Interaja com a galeria de outros usuários de forma simples e rápida, comentando e enviando curtidas em suas publicações.</p>
        </div>
        <!-- Card 3 -->
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <h3 class="feature-title">100% Seguro e Nativo</h3>
            <p class="feature-desc">Desenvolvido com validações severas de segurança contra vulnerabilidades da web, sem frameworks, puramente PHP, HTML e CSS.</p>
        </div>
    </div>
</section>

<!-- Seção Galeria Pública Fictícia -->
<section id="galeria" class="gallery-preview">
    <div class="section-header">
        <h2 class="section-title">Explorar Publicações</h2>
        <span style="color: var(--text-secondary); font-size: 0.9rem;">Fotos mais recentes</span>
    </div>

    <div class="grid-photos">
        <!-- Foto 1 -->
        <div class="photo-card">
            <div class="photo-img">
                <span class="photo-overlay-filter">Neon Vintage</span>
                <svg width="100%" height="100%" viewBox="0 0 300 250" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#ec4899;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grad1)" />
                    <circle cx="150" cy="125" r="45" fill="none" stroke="white" stroke-width="3" stroke-dasharray="10 5" />
                    <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" fill="white" font-family="'Outfit', sans-serif" font-weight="800" font-size="20">CODANDO EM RIO</text>
                </svg>
            </div>
            <div class="photo-info">
                <div class="photo-user">
                    <div class="user-avatar">LL</div>
                    <span class="user-name">luciano_lima</span>
                </div>
                <p class="photo-caption">Montando a estrutura MVC do Camagru hoje de manhã! O código está ficando lindo.</p>
                <div class="photo-meta">
                    <span>Há 10 min</span>
                    <div class="meta-actions">
                        <span class="meta-item like">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                            42
                        </span>
                        <span class="meta-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            7
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Foto 2 -->
        <div class="photo-card">
            <div class="photo-img">
                <span class="photo-overlay-filter">Cyberpunk</span>
                <svg width="100%" height="100%" viewBox="0 0 300 250" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="grad2" x1="0%" y1="100%" x2="100%" y2="0%">
                            <stop offset="0%" style="stop-color:#8b5cf6;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#10b981;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grad2)" />
                    <polygon points="150,50 190,200 70,110 230,110 110,200" fill="none" stroke="white" stroke-width="3" />
                    <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" fill="white" font-family="'Outfit', sans-serif" font-weight="800" font-size="20">CYBER DECOR</text>
                </svg>
            </div>
            <div class="photo-info">
                <div class="photo-user">
                    <div class="user-avatar" style="background-color: #8b5cf6;">AG</div>
                    <span class="user-name">antigravity_dev</span>
                </div>
                <p class="photo-caption">Filtros geométricos aplicados com a biblioteca GD. Perfeito para postar na galeria pública!</p>
                <div class="photo-meta">
                    <span>Há 1 hora</span>
                    <div class="meta-actions">
                        <span class="meta-item like">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                            128
                        </span>
                        <span class="meta-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            24
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Foto 3 -->
        <div class="photo-card">
            <div class="photo-img">
                <span class="photo-overlay-filter">Manga Noir</span>
                <svg width="100%" height="100%" viewBox="0 0 300 250" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="grad3" x1="100%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#374151;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#030712;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grad3)" />
                    <line x1="20" y1="20" x2="280" y2="230" stroke="rgba(255,255,255,0.2)" stroke-width="4" />
                    <line x1="280" y1="20" x2="20" y2="230" stroke="rgba(255,255,255,0.2)" stroke-width="4" />
                    <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="white" font-family="'Outfit', sans-serif" font-weight="800" font-size="24">RETRO 1993</text>
                </svg>
            </div>
            <div class="photo-info">
                <div class="photo-user">
                    <div class="user-avatar" style="background-color: #f43f5e;">SP</div>
                    <span class="user-name">sao_paulo_42</span>
                </div>
                <p class="photo-caption">Explorando as opções de layout grid. O visual dark mode premium faz toda a diferença.</p>
                <div class="photo-meta">
                    <span>Há 2 dias</span>
                    <div class="meta-actions">
                        <span class="meta-item like">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                            96
                        </span>
                        <span class="meta-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            15
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
