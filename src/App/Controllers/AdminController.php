<?php
namespace App\Controllers;

use Core\Controller;
use Core\Database;

class AdminController extends Controller {
    public function index() {
        $db_connected = false;
        try {
            $db = Database::getInstance()->getConnection();
            $db_connected = ($db !== null);
        } catch (\Exception $e) {
            // Conexão falhou
        }

        $gd_loaded = extension_loaded('gd');
        $gd_version = 'Não instalado';
        if ($gd_loaded) {
            $gd_info = gd_info();
            $gd_version = $gd_info['GD Version'];
        }

        $email_sent = null;
        $email_error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
            $to = 'test@example.com';
            $subject = 'Teste de E-mail - Camagru';
            $message = "Olá! Este é um e-mail de teste enviado a partir do ambiente Docker do Camagru para o Mailpit.\r\n\r\n" .
                       "Se você recebeu esta mensagem na interface do Mailpit, a integração de envio de e-mails (msmtp) está configurada corretamente e pronta para o desenvolvimento!";
            $headers = 'From: noreply@camagru.local' . "\r\n" .
                       'Reply-To: noreply@camagru.local' . "\r\n" .
                       'X-Mailer: PHP/' . phpversion();

            if (mail($to, $subject, $message, $headers)) {
                $email_sent = true;
            } else {
                $email_sent = false;
                $email_error = 'A função mail() do PHP retornou um erro.';
            }
        }

        // Renderiza a view admin/index com o layout padrão
        $this->render('admin/index', [
            'title' => 'Camagru - Painel de Controle e Status',
            'db_connected' => $db_connected,
            'gd_loaded' => $gd_loaded,
            'gd_version' => $gd_version,
            'email_sent' => $email_sent,
            'email_error' => $email_error
        ]);
    }
}
