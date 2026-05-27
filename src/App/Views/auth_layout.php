<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= isset($title) ? htmlspecialchars($title) : 'Camagru - Autenticação' ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
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
                      "label-sm": ["12px", {"lineHeight": "14px", "fontWeight": "500"}],
                      "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                      "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                      "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                      "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                      "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                      "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                      "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                      "label-md": ["14px", {"lineHeight": "16px", "letterSpacing": "0.01em", "fontWeight": "600"}]
              }
            }
          }
        }
    </script>
    <style>
        .form-input {
            border: 1px solid #D1D5DB;
            transition: all 0.2s ease-in-out;
        }
        .form-input:focus {
            border-color: #003c90;
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 60, 144, 0.1);
        }
        .strength-bar {
            height: 4px;
            width: 100%;
            background-color: #E5E7EB;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 8px;
        }
        .strength-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease, background-color 0.3s ease;
        }
        .strength-weak { background-color: #EF4444; width: 33%; }
        .strength-medium { background-color: #F59E0B; width: 66%; }
        .strength-strong { background-color: #10B981; width: 100%; }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid #E5E7EB;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex flex-col">
<main class="flex-grow flex items-center justify-center p-margin-mobile md:p-margin-desktop relative overflow-hidden">
    <!-- Abstract Background Elements for Modern Minimalist Vibe -->
    <div class="absolute inset-0 pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-primary-fixed opacity-20 blur-3xl mix-blend-multiply"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40vw] h-[40vw] rounded-full bg-secondary-fixed opacity-20 blur-3xl mix-blend-multiply"></div>
    </div>
    
    <div class="w-full max-w-[1000px] grid grid-cols-1 md:grid-cols-2 gap-0 glass-panel rounded-xl overflow-hidden z-10">
        <!-- Left Side: Hero Image Area (Hidden on Mobile) -->
        <div class="hidden md:block relative bg-surface-variant w-full h-full min-h-[600px]">
            <img alt="Photography Setup" class="absolute inset-0 w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAulQpuDr-23iqhnV9hO4UWKjv0gdOtUYwH7OBPv9MGmaQ5R7bK4b41oKmAzS2d3C9Q-D90eMya1hFHecAAfYdNnMnL7Uwo236H1sp2Oy2fNruJXx0_AmKAW5bfX8N-TCQs1713QqlyuTi3zK1NOcIzz-Ksmpl5w5nF8mX7LSeyBN18ofdFzP7imxHlitGm-mCSj32PHWTWE-kG--KzJyKgAkm4ILq5RGvVpZ5SQ16NmN5bwgb35mht6mSIJcfb_erjYbULOg_YORU"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex flex-col justify-end p-lg">
                <h2 class="font-headline-lg text-headline-lg text-white mb-sm">Capture. Edite. Compartilhe.</h2>
                <p class="font-body-lg text-body-lg text-white/80">Junte-se à comunidade Camagru e eleve a sua narrativa visual.</p>
            </div>
        </div>
        
        <!-- Right Side: Forms Area -->
        <div class="p-margin-desktop md:p-lg flex flex-col justify-center bg-surface-container-lowest w-full relative min-h-[600px]">
            <?php if (!empty($_SESSION['flash'])): ?>
                <div class="mb-md p-sm rounded-lg flex items-center gap-sm <?= $_SESSION['flash']['type'] === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' ?>">
                    <span class="material-symbols-outlined"><?= $_SESSION['flash']['type'] === 'success' ? 'check_circle' : 'error' ?></span>
                    <span class="text-body-sm font-medium"><?= htmlspecialchars($_SESSION['flash']['message']) ?></span>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>
            <?= $content ?>
        </div>
    </div>
</main>

<footer class="bg-surface-container-lowest dark:bg-on-background border-t border-outline-variant dark:border-outline full-width">
    <div class="flex flex-col md:flex-row justify-between items-center w-full py-lg px-margin-desktop max-w-7xl mx-auto space-y-md md:space-y-0">
        <div class="text-label-md font-label-md font-bold text-primary dark:text-primary-fixed-dim">
            Camagru
        </div>
        <div class="flex flex-wrap justify-center gap-md">
            <a class="text-body-sm font-body-sm text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-primary-fixed transition-colors opacity-80 hover:opacity-100 transition-opacity" href="#">Termos de Serviço</a>
            <a class="text-body-sm font-body-sm text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-primary-fixed transition-colors opacity-80 hover:opacity-100 transition-opacity" href="#">Política de Privacidade</a>
            <a class="text-body-sm font-body-sm text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-primary-fixed transition-colors opacity-80 hover:opacity-100 transition-opacity" href="#">Central de Ajuda</a>
            <a class="text-body-sm font-body-sm text-secondary dark:text-secondary-fixed-dim hover:text-primary dark:hover:text-primary-fixed transition-colors opacity-80 hover:opacity-100 transition-opacity" href="#">Contato</a>
        </div>
        <div class="text-body-sm font-body-sm text-secondary dark:text-secondary-fixed-dim opacity-80">
            © 2024 Plataforma Camagru. Todos os direitos reservados.
        </div>
    </div>
</footer>
</body>
</html>
