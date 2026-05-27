<!-- Back to Login Link -->
<div class="absolute top-margin-desktop right-margin-desktop">
    <a class="px-sm py-xs rounded-full font-label-md text-label-md bg-surface-container-low text-secondary hover:text-primary transition-all flex items-center gap-xs decoration-none" href="/login">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        Voltar ao Login
    </a>
</div>

<!-- Mobile Brand Name -->
<div class="md:hidden mb-lg text-center">
    <h1 class="font-headline-lg-mobile text-headline-lg-mobile text-primary font-bold tracking-tight">Camagru</h1>
</div>

<!-- Forgot Password Form -->
<div class="w-full max-w-[400px] mx-auto">
    <div class="mb-lg">
        <h2 class="font-headline-md text-headline-md text-on-background mb-xs">Esqueceu a Senha?</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant">Digite seu e-mail para receber um link de redefinição.</p>
    </div>
    <form class="space-y-md" method="POST" action="/forgot-password">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>"/>
        <div class="space-y-xs">
            <label class="block font-label-sm text-label-sm text-on-surface" for="forgot-email">E-mail Cadastrado</label>
            <input class="form-input w-full px-sm py-[10px] rounded text-body-md text-on-background bg-surface-container-lowest" id="forgot-email" name="email" placeholder="seu-email@exemplo.com" type="email" required/>
        </div>
        <button class="w-full bg-primary hover:bg-primary/90 text-white font-label-md text-label-md py-[12px] rounded-lg transition-colors flex justify-center items-center gap-xs" type="submit">
            <span class="material-symbols-outlined text-[20px]">mail</span>
            Enviar Link de Recuperação
        </button>
    </form>
</div>
