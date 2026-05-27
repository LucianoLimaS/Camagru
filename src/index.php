<?php
// Inicializa a sessão para o aplicativo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializa o token CSRF na sessão caso ainda não exista
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Configurações de exibição de erros (habilitado para desenvolvimento)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Registra o autoloader da nossa estrutura MVC
require_once __DIR__ . '/Core/Autoloader.php';
Core\Autoloader::register();

// Carrega o arquivo .env do diretório raiz
Core\Env::load(__DIR__ . '/../.env');

// Define a constante global de desenvolvimento (DEV_MODE)
define('DEV_MODE', filter_var(getenv('DEV_MODE') ?: 'true', FILTER_VALIDATE_BOOLEAN));

// Inicializa o banco de dados (e executa migrações se necessário)
Core\Database::getInstance();

// Inicializa o roteador customizado
$router = new Core\Router();

// Registro de rotas
$router->add('GET', '/', 'HomeController@index');
$router->add('GET', '/admin', 'AdminController@index');
$router->add('POST', '/admin', 'AdminController@index');

// Rotas de Autenticação e Recuperação de Senha
$router->add('GET', '/login', 'AuthController@login');
$router->add('POST', '/login', 'AuthController@postLogin');
$router->add('GET', '/register', 'AuthController@register');
$router->add('POST', '/register', 'AuthController@postRegister');
$router->add('GET', '/logout', 'AuthController@logout');
$router->add('GET', '/confirm', 'AuthController@confirm');
$router->add('GET', '/forgot-password', 'AuthController@forgotPassword');
$router->add('POST', '/forgot-password', 'AuthController@postForgotPassword');
$router->add('GET', '/reset-password', 'AuthController@resetPassword');
$router->add('POST', '/reset-password', 'AuthController@postResetPassword');

// Despacha a requisição HTTP para o respectivo Controller
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
