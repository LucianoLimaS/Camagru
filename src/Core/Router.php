<?php
namespace Core;

class Router {
    protected $routes = [];

    /**
     * Adiciona uma rota ao roteador
     * 
     * @param string $method Método HTTP (GET, POST, etc.)
     * @param string $path Caminho da rota (ex: '/user/{id}')
     * @param string|callable $handler Nome do controller/método ou função callback
     */
    public function add($method, $path, $handler) {
        // Converte curingas como {id} para expressões regulares nomeadas
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';
        
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    /**
     * Despacha a requisição para o controller correspondente
     * 
     * @param string $requestUri URI da requisição
     * @param string $requestMethod Método HTTP da requisição
     */
    public function dispatch($requestUri, $requestMethod) {
        $path = parse_url($requestUri, PHP_URL_PATH);
        
        // Remove barra no final se não for a home para padronizar
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }

        // Verifica autenticação global: se não logado, redireciona para /login
        // Exceções: rota home ('/'), rota admin (e suas sub-rotas) e as rotas de auth (/login e /register)
        $isLoggedIn = !empty($_SESSION['user']) || !empty($_SESSION['user_id']);
        if (!$isLoggedIn) {
            $isAdminRoute = ($path === '/admin' || strpos($path, '/admin/') === 0);
            $isAuthRoute = ($path === '/login' || $path === '/register');
            $isHomeRoute = ($path === '/');

            if (!$isAdminRoute && !$isAuthRoute && !$isHomeRoute) {
                header('Location: /login');
                exit();
            }
        }
        
        $method = strtoupper($requestMethod);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $path, $matches)) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                    }
                }
                
                $handler = $route['handler'];
                if (is_string($handler)) {
                    list($controllerClass, $action) = explode('@', $handler);
                    $controllerClass = "App\\Controllers\\" . $controllerClass;
                    
                    if (class_exists($controllerClass)) {
                        $controller = new $controllerClass();
                        if (method_exists($controller, $action)) {
                            call_user_func_array([$controller, $action], $params);
                            return;
                        }
                    }
                } elseif (is_callable($handler)) {
                    call_user_func_array($handler, $params);
                    return;
                }
            }
        }

        $this->handleNotFound();
    }

    protected function handleNotFound() {
        $controller = new \App\Controllers\ErrorController();
        $controller->show(404, 'A página que você está procurando não existe ou foi removida.');
    }
}
