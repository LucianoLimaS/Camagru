<?php
// Inicializa a sessão para o aplicativo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurações de exibição de erros (habilitado para desenvolvimento)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Registra o autoloader da nossa estrutura MVC
require_once __DIR__ . '/Core/Autoloader.php';
Core\Autoloader::register();

// Carrega o arquivo .env do diretório raiz
Core\Env::load(__DIR__ . '/../.env');

// Inicializa o banco de dados (e executa migrações se necessário)
Core\Database::getInstance();

// Inicializa o roteador customizado
$router = new Core\Router();

// Registro de rotas
$router->add('GET', '/', 'HomeController@index');
$router->add('GET', '/admin', 'AdminController@index');
$router->add('POST', '/admin', 'AdminController@index');
$router->add('GET', '/login', 'AuthController@login');
$router->add('GET', '/register', 'AuthController@register');

// Despacha a requisição HTTP para o respectivo Controller
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
