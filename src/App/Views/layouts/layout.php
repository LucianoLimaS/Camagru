<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= isset($title) ? htmlspecialchars($title) : 'Camagru - Galeria Pública' ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link href="/webroot/css/app.css" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-error-container": "#93000a",
                        "on-primary-container": "#bcceff",
                        "on-tertiary": "#ffffff",
                        "outline": "#737784",
                        "primary-fixed": "#d9e2ff",
                        "on-background": "#191c1d",
                        "tertiary": "#732900",
                        "on-secondary-container": "#5e6572",
                        "error": "#ba1a1a",
                        "on-primary-fixed-variant": "#00419c",
                        "on-surface": "#191c1d",
                        "tertiary-fixed-dim": "#ffb596",
                        "surface-dim": "#d9dadb",
                        "surface-container-highest": "#e1e3e4",
                        "on-primary-fixed": "#001945",
                        "inverse-surface": "#2e3132",
                        "background": "#f8f9fa",
                        "on-secondary": "#ffffff",
                        "on-surface-variant": "#434653",
                        "secondary-container": "#dce2f3",
                        "error-container": "#ffdad6",
                        "secondary": "#585f6c",
                        "tertiary-fixed": "#ffdbcd",
                        "primary-container": "#0f52ba",
                        "tertiary-container": "#993900",
                        "on-primary": "#ffffff",
                        "surface-container-high": "#e7e8e9",
                        "surface-container": "#edeeef",
                        "on-secondary-fixed-variant": "#404754",
                        "on-secondary-fixed": "#151c27",
                        "surface-bright": "#f8f9fa",
                        "on-error": "#ffffff",
                        "inverse-primary": "#b0c6ff",
                        "on-tertiary-container": "#ffc0a7",
                        "primary": "#003c90",
                        "surface-tint": "#1d59c1",
                        "inverse-on-surface": "#f0f1f2",
                        "secondary-fixed": "#dce2f3",
                        "on-tertiary-fixed": "#360f00",
                        "surface-variant": "#e1e3e4",
                        "secondary-fixed-dim": "#c0c7d6",
                        "on-tertiary-fixed-variant": "#7d2d00",
                        "surface": "#f8f9fa",
                        "primary-fixed-dim": "#b0c6ff",
                        "outline-variant": "#c3c6d5",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f3f4f5"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "gutter": "24px",
                        "sm": "12px",
                        "xl": "80px",
                        "md": "24px",
                        "margin-mobile": "16px",
                        "lg": "48px",
                        "margin-desktop": "32px",
                        "xs": "4px",
                        "base": "8px"
                    },
                    "fontFamily": {
                        "label-sm": ["Inter"],
                        "headline-lg": ["Inter"],
                        "headline-md": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "body-lg": ["Inter"],
                        "display-lg": ["Inter"],
                        "body-sm": ["Inter"],
                        "body-md": ["Inter"],
                        "label-md": ["Inter"]
                    },
                    "fontSize": {
                        "label-sm": ["12px", { "lineHeight": "14px", "fontWeight": "500" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                        "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                        "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "body-sm": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "label-md": ["14px", { "lineHeight": "16px", "letterSpacing": "0.01em", "fontWeight": "600" }]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-on-background antialiased min-h-screen flex flex-col">
    <!-- TopAppBar -->
    <header class="bg-surface-container-lowest dark:bg-on-background border-b border-outline-variant dark:border-outline docked full-width top-0 sticky z-50">
        <div class="flex justify-between items-center w-full h-16 px-margin-desktop max-w-7xl mx-auto">
            <div class="flex items-center gap-md">
                <a href="/" class="text-headline-md font-headline-md font-bold tracking-tight text-primary dark:text-primary-fixed-dim cursor-pointer">Camagru</a>
                <nav class="hidden md:flex gap-sm ml-md">
                    <a class="text-primary dark:text-primary-fixed font-bold border-b-2 border-primary dark:border-primary-fixed pb-1 hover:text-primary dark:hover:text-primary-fixed transition-colors duration-200 active:scale-95 transition-transform duration-150" href="/">Galeria</a>
                    <a class="text-secondary dark:text-secondary-fixed-dim font-medium hover:text-primary dark:hover:text-primary-fixed transition-colors duration-200 active:scale-95 transition-transform duration-150" href="/editor">Editor</a>
                    <a class="text-secondary dark:text-secondary-fixed-dim font-medium hover:text-primary dark:hover:text-primary-fixed transition-colors duration-200 active:scale-95 transition-transform duration-150" href="/admin">Admin</a>
                </nav>
            </div>
            <div class="flex items-center gap-sm">
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <span class="text-body-sm font-medium text-secondary hidden md:inline">Olá, <strong class="text-primary"><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
                    <a href="/logout" class="bg-transparent text-error border border-error px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-error-container hover:text-on-error-container transition-colors duration-200 text-center decoration-none">Sair</a>
                <?php else: ?>
                    <!-- Autenticação Buttons for Public Page -->
                    <a href="/login" class="bg-transparent text-primary border border-primary px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-primary-fixed transition-colors duration-200 hidden md:block text-center decoration-none">Entrar</a>
                    <a href="/register" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-primary-fixed-dim transition-colors duration-200 text-center decoration-none">Cadastrar-se</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
 
    <!-- Main Content wrapper -->
    <main class="flex-grow w-full max-w-7xl mx-auto px-margin-mobile md:px-margin-desktop py-lg">
        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="mb-md p-sm rounded-lg flex items-center gap-sm <?= $_SESSION['flash']['type'] === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' ?>">
                <span class="material-symbols-outlined"><?= $_SESSION['flash']['type'] === 'success' ? 'check_circle' : 'error' ?></span>
                <span class="text-body-md font-medium"><?= htmlspecialchars($_SESSION['flash']['message']) ?></span>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-surface-container-lowest dark:bg-on-background border-t border-outline-variant dark:border-outline full-width mt-auto">
        <div class="flex flex-col md:flex-row justify-between items-center w-full py-lg px-margin-desktop max-w-7xl mx-auto space-y-md md:space-y-0">
            <span class="text-label-md font-label-md font-bold text-primary dark:text-primary-fixed-dim">Camagru</span>
            <div class="flex flex-wrap justify-center gap-md">
                <a class="text-body-sm font-body-sm text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-primary-fixed transition-colors opacity-80 hover:opacity-100 transition-opacity" href="#">Termos de Serviço</a>
                <a class="text-body-sm font-body-sm text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-primary-fixed transition-colors opacity-80 hover:opacity-100 transition-opacity" href="#">Política de Privacidade</a>
                <a class="text-body-sm font-body-sm text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-primary-fixed transition-colors opacity-80 hover:opacity-100 transition-opacity" href="#">Central de Ajuda</a>
                <a class="text-body-sm font-body-sm text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-primary-fixed transition-colors opacity-80 hover:opacity-100 transition-opacity" href="#">Contato</a>
            </div>
            <span class="text-body-sm font-body-sm text-secondary dark:text-secondary-fixed-dim">© 2024 Plataforma Camagru. Todos os direitos reservados.</span>
        </div>
    </footer>
</body>
</html>
