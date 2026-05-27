<?php
namespace Core;

class Controller {
    /**
     * Renderiza uma view dentro de um layout
     * 
     * @param string $view Nome do arquivo da view (ex: 'home/index')
     * @param array $data Dados que estarão disponíveis na view
     * @param string $layout Nome do arquivo do layout principal
     */
    protected function render($view, $data = [], $layout = 'layout') {
        extract($data);

        ob_start();
        $viewFile = __DIR__ . '/../App/Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "View: {$view} não encontrada.";
        }
        $content = ob_get_clean();

        $layoutFile = __DIR__ . '/../App/Views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    /**
     * Redireciona para uma URL
     * 
     * @param string $url URL de destino
     */
    protected function redirect($url) {
        header('Location: ' . $url);
        exit();
    }

    /**
     * Retorna uma resposta em formato JSON
     * 
     * @param mixed $data Dados a serem codificados
     * @param int $statusCode Código de status HTTP
     */
    protected function json($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit();
    }
}
