<!-- Toggle Container (Entrar / Cadastrar-se) -->
<div class="absolute top-margin-desktop right-margin-desktop flex space-x-sm bg-surface-container-low p-xs rounded-full">
    <button class="px-sm py-xs rounded-full font-label-md text-label-md bg-white shadow-sm text-primary transition-all" id="toggleLoginBtn">Entrar</button>
    <button class="px-sm py-xs rounded-full font-label-md text-label-md text-secondary hover:text-primary transition-all" id="toggleSignupBtn">Cadastrar-se</button>
</div>

<!-- Brand Name for Mobile (since TopAppBar is hidden) -->
<div class="md:hidden mb-lg text-center">
    <h1 class="font-headline-lg-mobile text-headline-lg-mobile text-primary font-bold tracking-tight">Camagru</h1>
</div>

<!-- Login Form -->
<div class="w-full max-w-[400px] mx-auto transition-all duration-500 transform translate-x-0 opacity-100" id="loginFormContainer">
    <div class="mb-lg">
        <h2 class="font-headline-md text-headline-md text-on-background mb-xs">Bem-vindo de volta</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant">Por favor, insira suas credenciais para acessar sua conta.</p>
    </div>
    <form class="space-y-md" method="POST" action="/login">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>"/>
        <div class="space-y-xs">
            <label class="block font-label-sm text-label-sm text-on-surface" for="login-username">Nome de Usuário</label>
            <input class="form-input w-full px-sm py-[10px] rounded text-body-md text-on-background bg-surface-container-lowest" id="login-username" name="username" placeholder="Digite seu nome de usuário" type="text" required/>
        </div>
        <div class="space-y-xs">
            <div class="flex justify-between items-center">
                <label class="block font-label-sm text-label-sm text-on-surface" for="login-password">Senha</label>
                <a class="font-label-sm text-label-sm text-primary hover:underline" href="/forgot-password">Esqueceu a senha?</a>
            </div>
            <div class="relative">
                <input class="form-input w-full px-sm py-[10px] rounded text-body-md text-on-background bg-surface-container-lowest pr-10" id="login-password" name="password" placeholder="••••••••" type="password" required/>
                <button class="absolute right-sm top-1/2 transform -translate-y-1/2 text-secondary hover:text-primary" type="button" onclick="togglePasswordVisibility('login-password', this)">
                    <span class="material-symbols-outlined text-[20px]" id="login-password-toggle-icon">visibility_off</span>
                </button>
            </div>
        </div>
        <button class="w-full bg-primary hover:bg-primary/90 text-white font-label-md text-label-md py-[12px] rounded-lg transition-colors flex justify-center items-center" type="submit">
            Entrar
        </button>
    </form>
</div>

<!-- Sign Up Form (Initially Hidden) -->
<div class="w-full max-w-[400px] mx-auto hidden transition-all duration-500 transform translate-x-0 opacity-100" id="signupFormContainer">
    <div class="mb-lg">
        <h2 class="font-headline-md text-headline-md text-on-background mb-xs">Criar Conta</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant">Junte-se ao Camagru para compartilhar suas fotos.</p>
    </div>
    <form class="space-y-md" method="POST" action="/register">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>"/>
        <div class="space-y-xs">
            <label class="block font-label-sm text-label-sm text-on-surface" for="signup-email">Endereço de E-mail</label>
            <input class="form-input w-full px-sm py-[10px] rounded text-body-md text-on-background bg-surface-container-lowest" id="signup-email" name="email" placeholder="voce@exemplo.com" type="email" required/>
        </div>
        <div class="space-y-xs">
            <label class="block font-label-sm text-label-sm text-on-surface" for="signup-username">Nome de Usuário</label>
            <input class="form-input w-full px-sm py-[10px] rounded text-body-md text-on-background bg-surface-container-lowest" id="signup-username" name="username" placeholder="Escolha um nome de usuário único" type="text" required/>
        </div>
        <div class="space-y-xs">
            <label class="block font-label-sm text-label-sm text-on-surface" for="signup-password">Senha</label>
            <div class="relative">
                <input class="form-input w-full px-sm py-[10px] rounded text-body-md text-on-background bg-surface-container-lowest pr-10" id="signup-password" name="password" placeholder="Crie uma senha forte" type="password" required/>
                <button class="absolute right-sm top-1/2 transform -translate-y-1/2 text-secondary hover:text-primary" type="button" onclick="togglePasswordVisibility('signup-password', this)">
                    <span class="material-symbols-outlined text-[20px]" id="signup-password-toggle-icon">visibility_off</span>
                </button>
            </div>
            <!-- Password Strength Indicator -->
            <div class="mt-2">
                <div class="flex justify-between items-center mb-1">
                    <span class="font-label-sm text-[10px] text-on-surface-variant">Força da Senha</span>
                    <span class="font-label-sm text-[10px] text-on-surface-variant" id="strength-text">Nenhuma</span>
                </div>
                <div class="strength-bar">
                    <div class="strength-fill" id="strength-fill"></div>
                </div>
            </div>
        </div>
        <button class="w-full bg-primary hover:bg-primary/90 text-white font-label-md text-label-md py-[12px] rounded-lg transition-colors flex justify-center items-center" type="submit">
            Criar Conta
        </button>
    </form>
</div>

<script>
    function togglePasswordVisibility(fieldId, button) {
        const passwordField = document.getElementById(fieldId);
        const iconSpan = button.querySelector('span');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            iconSpan.textContent = 'visibility';
        } else {
            passwordField.type = 'password';
            iconSpan.textContent = 'visibility_off';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const loginBtn = document.getElementById('toggleLoginBtn');
        const signupBtn = document.getElementById('toggleSignupBtn');
        const loginForm = document.getElementById('loginFormContainer');
        const signupForm = document.getElementById('signupFormContainer');

        function showLogin(updateUrl = true) {
            loginBtn.className = 'px-sm py-xs rounded-full font-label-md text-label-md bg-white shadow-sm text-primary transition-all';
            signupBtn.className = 'px-sm py-xs rounded-full font-label-md text-label-md text-secondary hover:text-primary transition-all';
            
            signupForm.classList.add('hidden');
            signupForm.classList.remove('block');
            
            loginForm.classList.remove('hidden');
            loginForm.classList.add('block');
            
            if (updateUrl) {
                history.pushState(null, '', '/login');
                document.title = 'Camagru - Entrar na sua Conta';
            }
        }

        function showSignup(updateUrl = true) {
            signupBtn.className = 'px-sm py-xs rounded-full font-label-md text-label-md bg-white shadow-sm text-primary transition-all';
            loginBtn.className = 'px-sm py-xs rounded-full font-label-md text-label-md text-secondary hover:text-primary transition-all';
            
            loginForm.classList.add('hidden');
            loginForm.classList.remove('block');
            
            signupForm.classList.remove('hidden');
            signupForm.classList.add('block');
            
            if (updateUrl) {
                history.pushState(null, '', '/register');
                document.title = 'Camagru - Criar uma Conta';
            }
        }

        loginBtn.addEventListener('click', () => {
            showLogin(true);
            const alertContainer = document.getElementById('authAlertContainer');
            if (alertContainer) {
                alertContainer.classList.add('hidden');
            }
        });
        signupBtn.addEventListener('click', () => {
            showSignup(true);
            const alertContainer = document.getElementById('authAlertContainer');
            if (alertContainer) {
                alertContainer.classList.add('hidden');
            }
        });

        // Define o estado inicial com base na URL
        <?php if ($action === 'register'): ?>
            showSignup(false);
        <?php else: ?>
            showLogin(false);
        <?php endif; ?>

        // Password Strength Logic
        const signupPassword = document.getElementById('signup-password');
        const strengthFill = document.getElementById('strength-fill');
        const strengthText = document.getElementById('strength-text');

        signupPassword.addEventListener('input', (e) => {
            const val = e.target.value;
            let strength = 0;
            
            if(val.length > 0) strength = 1;
            if(val.length > 5) strength = 2;
            if(val.length >= 8 && /[A-Z]/.test(val) && /[a-z]/.test(val) && /[0-9]/.test(val) && /[^a-zA-Z0-9]/.test(val)) strength = 3;

            strengthFill.className = 'strength-fill'; // reset
            
            switch(strength) {
                case 0:
                    strengthFill.style.width = '0%';
                    strengthText.textContent = 'Nenhuma';
                    break;
                case 1:
                    strengthFill.classList.add('strength-weak');
                    strengthText.textContent = 'Fraca';
                    strengthText.style.color = '#EF4444';
                    break;
                case 2:
                    strengthFill.classList.add('strength-medium');
                    strengthText.textContent = 'Média';
                    strengthText.style.color = '#F59E0B';
                    break;
                case 3:
                    strengthFill.classList.add('strength-strong');
                    strengthText.textContent = 'Forte';
                    strengthText.style.color = '#10B981';
                    break;
            }
        });
    });
</script>
