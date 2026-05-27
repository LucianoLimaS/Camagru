<!-- Mobile Brand Name -->
<div class="md:hidden mb-lg text-center">
    <h1 class="font-headline-lg-mobile text-headline-lg-mobile text-primary font-bold tracking-tight">Camagru</h1>
</div>

<!-- Reset Password Form -->
<div class="w-full max-w-[400px] mx-auto">
    <div class="mb-lg">
        <h2 class="font-headline-md text-headline-md text-on-background mb-xs">Redefinir Senha</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant">Escolha uma nova senha forte para a sua conta.</p>
    </div>
    <form class="space-y-md" method="POST" action="/reset-password">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>"/>
        <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>"/>
        
        <div class="space-y-xs">
            <label class="block font-label-sm text-label-sm text-on-surface" for="reset-password">Nova Senha</label>
            <div class="relative">
                <input class="form-input w-full px-sm py-[10px] rounded text-body-md text-on-background bg-surface-container-lowest pr-10" id="reset-password" name="password" placeholder="Mínimo 8 caracteres" type="password" required/>
                <button class="absolute right-sm top-1/2 transform -translate-y-1/2 text-secondary hover:text-primary" type="button" onclick="togglePasswordVisibility('reset-password', this)">
                    <span class="material-symbols-outlined text-[20px]" id="reset-password-toggle-icon">visibility_off</span>
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

        <div class="space-y-xs">
            <label class="block font-label-sm text-label-sm text-on-surface" for="reset-confirm-password">Confirmar Nova Senha</label>
            <div class="relative">
                <input class="form-input w-full px-sm py-[10px] rounded text-body-md text-on-background bg-surface-container-lowest pr-10" id="reset-confirm-password" name="confirm_password" placeholder="Repita a senha" type="password" required/>
                <button class="absolute right-sm top-1/2 transform -translate-y-1/2 text-secondary hover:text-primary" type="button" onclick="togglePasswordVisibility('reset-confirm-password', this)">
                    <span class="material-symbols-outlined text-[20px]" id="reset-confirm-password-toggle-icon">visibility_off</span>
                </button>
            </div>
        </div>

        <button class="w-full bg-primary hover:bg-primary/90 text-white font-label-md text-label-md py-[12px] rounded-lg transition-colors flex justify-center items-center gap-xs" type="submit">
            <span class="material-symbols-outlined text-[20px]">lock_reset</span>
            Salvar Nova Senha
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
        const resetPassword = document.getElementById('reset-password');
        const strengthFill = document.getElementById('strength-fill');
        const strengthText = document.getElementById('strength-text');

        resetPassword.addEventListener('input', (e) => {
            const val = e.target.value;
            let strength = 0;
            
            if(val.length > 0) strength = 1;
            if(val.length > 5) {
                strength = 2;
            }
            if(val.length >= 8 && /[A-Z]/.test(val) && /[a-z]/.test(val) && /[0-9]/.test(val) && /[^a-zA-Z0-9]/.test(val)) {
                strength = 3;
            }

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
