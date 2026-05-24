<?php
namespace App\Controllers;

use Core\Controller;

class ErrorController extends Controller {
    /**
     * Exibe a página de erro personalizada correspondente ao código HTTP
     * 
     * @param int $code Código HTTP (400, 404, 500)
     * @param string $message Mensagem descritiva do erro
     */
    public function show($code, $message = '') {
        // Define o cabeçalho HTTP correto
        http_response_code($code);

        $titles = [
            400 => '400 - Requisição Inválida',
            404 => '404 - Página Não Encontrada',
            500 => '500 - Erro Interno do Servidor'
        ];

        $title = isset($titles[$code]) ? $titles[$code] : 'Erro';

        // Renderiza a view específica dentro do layout global da aplicação
        $this->render("errors/{$code}", [
            'title' => $title,
            'code' => $code,
            'message' => $message
        ]);
    }
}
