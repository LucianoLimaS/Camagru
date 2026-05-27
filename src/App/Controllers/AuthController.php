<?php
namespace App\Controllers;

use Core\Controller;
use Core\TableRegistry;

class AuthController extends Controller {

    /**
     * Exibe a tela de login
     */
    public function login() {
        // Se já estiver logado, redireciona para a home
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $this->render('auth/index', [
            'title' => 'Camagru - Entrar na sua Conta',
            'action' => 'login'
        ], 'auth_layout');
    }

    /**
     * Exibe a tela de cadastro
     */
    public function register() {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $this->render('auth/index', [
            'title' => 'Camagru - Criar uma Conta',
            'action' => 'register'
        ], 'auth_layout');
    }

    /**
     * Processa a requisição de cadastro (POST)
     */
    public function postRegister() {
        $this->checkCsrf();

        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // 1. Validações básicas de preenchimento
        if (empty($email) || empty($username) || empty($password)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Por favor, preencha todos os campos obrigatórios.'];
            $this->redirect('/register');
        }

        // 2. Validação do formato do e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Formato de e-mail inválido.'];
            $this->redirect('/register');
        }

        // 3. Validação do formato do nome de usuário
        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'O nome de usuário deve conter de 3 a 20 caracteres alfanuméricos ou sublinhado (_).'];
            $this->redirect('/register');
        }

        // 4. Validação da força da senha (ignorada em DEV_MODE)
        if (!DEV_MODE && (strlen($password) < 8 || 
            !preg_match('/[A-Z]/', $password) || 
            !preg_match('/[a-z]/', $password) || 
            !preg_match('/[0-9]/', $password) || 
            !preg_match('/[^a-zA-Z0-9]/', $password))
        ) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'A senha deve conter no mínimo 8 caracteres, incluindo pelo menos uma letra maiúscula, uma minúscula, um número e um caractere especial.'];
            $this->redirect('/register');
        }

        $usersTable = TableRegistry::get('Users');

        // 5. Verifica se o e-mail já está cadastrado
        $existingEmail = $usersTable->find()->where(['email' => $email])->first();
        if ($existingEmail) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Este endereço de e-mail já está cadastrado.'];
            $this->redirect('/register');
        }

        // 6. Verifica se o nome de usuário já está em uso
        $existingUsername = $usersTable->find()->where(['username' => $username])->first();
        if ($existingUsername) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Este nome de usuário já está em uso.'];
            $this->redirect('/register');
        }

        // 7. Gera UUID v4
        $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        // 8. Gera token de ativação
        $activationToken = bin2hex(random_bytes(32));

        // 9. Cria a entidade usuário
        $newUser = $usersTable->newEntity([
            'uuid' => $uuid,
            'username' => $username,
            'email' => $email,
            'password' => $password, // Será hasheado automaticamente no setter da Entidade User
            'active' => 0,
            'activation' => $activationToken,
            'notify' => 1
        ]);

        if ($usersTable->save($newUser)) {
            // 10. Envia e-mail de confirmação
            $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost:8000';
            $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
            $confirmationLink = $scheme . '://' . $host . '/confirm?token=' . $activationToken;

            \Core\Mailer::sendConfirmation($email, $username, $confirmationLink);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Conta criada com sucesso! Por favor, verifique seu e-mail para ativar sua conta.'];
            $this->redirect('/login');
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Ocorreu um erro ao salvar a conta. Por favor, tente novamente.'];
            $this->redirect('/register');
        }
    }

    /**
     * Processa a requisição de login (POST)
     */
    public function postLogin() {
        $this->checkCsrf();

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Por favor, preencha o nome de usuário e a senha.'];
            $this->redirect('/login');
        }

        $usersTable = TableRegistry::get('Users');
        $user = $usersTable->find()->where(['username' => $username])->first();

        // Verifica a existência do usuário e valida a senha
        if (!$user || !password_verify($password, $user->password)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Nome de usuário ou senha incorretos.'];
            $this->redirect('/login');
        }

        // Verifica se a conta está ativa
        if (!(bool)$user->active) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Esta conta ainda não foi ativada. Verifique seu e-mail para confirmá-la.'];
            $this->redirect('/login');
        }

        // Regenera ID de sessão por segurança
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['user'] = $user->toArray();

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Bem-vindo de volta, ' . htmlspecialchars($user->username) . '!'];
        $this->redirect('/');
    }

    /**
     * Confirmação da conta via link de e-mail (GET)
     */
    public function confirm() {
        $token = trim($_GET['token'] ?? '');

        if (empty($token)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token de confirmação inválido ou ausente.'];
            $this->redirect('/login');
        }

        $usersTable = TableRegistry::get('Users');
        $user = $usersTable->find()->where(['activation' => $token])->first();

        if (!$user) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Link de ativação inválido ou já utilizado.'];
            $this->redirect('/login');
        }

        // Ativa a conta e limpa o token de ativação
        $user->active = 1;
        $user->activation = null;

        if ($usersTable->save($user)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Conta ativada com sucesso! Você já pode fazer login.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Não foi possível ativar sua conta no momento. Tente novamente mais tarde.'];
        }

        $this->redirect('/login');
    }

    /**
     * Exibe a tela de solicitação de recuperação de senha (GET)
     */
    public function forgotPassword() {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $this->render('auth/forgot_password', [
            'title' => 'Camagru - Recuperar Senha'
        ], 'auth_layout');
    }

    /**
     * Processa a solicitação de redefinição de senha (POST)
     */
    public function postForgotPassword() {
        $this->checkCsrf();

        $email = trim($_POST['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Por favor, informe um endereço de e-mail válido.'];
            $this->redirect('/forgot-password');
        }

        $usersTable = TableRegistry::get('Users');
        $user = $usersTable->find()->where(['email' => $email])->first();

        if ($user) {
            // Gera token de redefinição
            $resetToken = bin2hex(random_bytes(32));
            // Expira em 1 hora
            $resetExpires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $user->reset_token = $resetToken;
            $user->reset_expires = $resetExpires;

            if ($usersTable->save($user)) {
                $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost:8000';
                $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
                $recoveryLink = $scheme . '://' . $host . '/reset-password?token=' . $resetToken;

                \Core\Mailer::sendPasswordRecovery($email, $user->username, $recoveryLink);
            }
        }

        // Mensagem genérica para evitar User Enumeration
        $_SESSION['flash'] = [
            'type' => 'success', 
            'message' => 'Se o e-mail informado estiver cadastrado, você receberá um link para redefinir sua senha em instantes. Verifique sua caixa de entrada.'
        ];
        $this->redirect('/login');
    }

    /**
     * Exibe a tela de redefinição de senha (GET)
     */
    public function resetPassword() {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $token = trim($_GET['token'] ?? '');

        if (empty($token)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token de redefinição ausente.'];
            $this->redirect('/forgot-password');
        }

        $usersTable = TableRegistry::get('Users');
        $user = $usersTable->find()->where([
            'reset_token' => $token,
            'reset_expires >=' => date('Y-m-d H:i:s')
        ])->first();

        if (!$user) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token de redefinição inválido ou expirado. Solicite um novo link.'];
            $this->redirect('/forgot-password');
        }

        $this->render('auth/reset_password', [
            'title' => 'Camagru - Escolher Nova Senha',
            'token' => $token
        ], 'auth_layout');
    }

    /**
     * Processa a redefinição de senha (POST)
     */
    public function postResetPassword() {
        $this->checkCsrf();

        $token = trim($_POST['token'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($token)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Token inválido. Solicite um novo link.'];
            $this->redirect('/forgot-password');
        }

        if (empty($password) || empty($confirmPassword)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Preencha todos os campos.'];
            $this->redirect('/reset-password?token=' . urlencode($token));
        }

        if ($password !== $confirmPassword) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'As senhas não coincidem.'];
            $this->redirect('/reset-password?token=' . urlencode($token));
        }

        // Validação da força da nova senha (ignorada em DEV_MODE)
        if (!DEV_MODE && (strlen($password) < 8 || 
            !preg_match('/[A-Z]/', $password) || 
            !preg_match('/[a-z]/', $password) || 
            !preg_match('/[0-9]/', $password) || 
            !preg_match('/[^a-zA-Z0-9]/', $password))
        ) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'A nova senha não atende aos critérios de complexidade mínima de segurança.'];
            $this->redirect('/reset-password?token=' . urlencode($token));
        }

        $usersTable = TableRegistry::get('Users');
        $user = $usersTable->find()->where([
            'reset_token' => $token,
            'reset_expires >=' => date('Y-m-d H:i:s')
        ])->first();

        if (!$user) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Link de redefinição de senha inválido ou expirado.'];
            $this->redirect('/forgot-password');
        }

        // Atualiza a senha (será hasheada no setter da entidade User) e limpa os campos de token
        $user->password = $password;
        $user->reset_token = null;
        $user->reset_expires = null;

        if ($usersTable->save($user)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Senha redefinida com sucesso! Você já pode entrar com a nova senha.'];
            $this->redirect('/login');
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Ocorreu um erro ao salvar a nova senha. Tente novamente.'];
            $this->redirect('/reset-password?token=' . urlencode($token));
        }
    }

    /**
     * Executa o logout do usuário (GET)
     */
    public function logout() {
        // Limpa a sessão
        $_SESSION = [];

        // Expirar o cookie de sessão se houver
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destrói a sessão
        session_destroy();

        // Inicia uma nova sessão limpa para mensagens flash de logout se necessário
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Você foi desconectado com sucesso.'];
        $this->redirect('/');
    }

    /**
     * Helper para checar a validade do Token CSRF
     */
    protected function checkCsrf() {
        $token = $_POST['csrf_token'] ?? '';
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Validação de token de segurança (CSRF) falhou. Por favor, recarregue a página e tente novamente.'];
            $this->redirect('/login');
        }
    }
}
