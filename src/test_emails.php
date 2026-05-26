<?php
// e:\42 rio\Camagru\src\test_emails.php

// Configura o PHP para enviar e-mails diretamente para o Mailpit no Docker se rodado no Windows
if (PHP_OS_FAMILY === 'Windows') {
    ini_set('SMTP', '127.0.0.1');
    ini_set('smtp_port', '1025');
}

require_once __DIR__ . '/Core/Autoloader.php';
\Core\Autoloader::register();
\Core\Env::load(__DIR__ . '/../.env');

// Mock data
$to = 'teste@example.com';
if ($argc > 1) {
    $to = $argv[1];
}

$mockUsername = 'UserTest';
$mockLink = 'http://localhost:8000/mock-link-route';

// 1. Generate local preview HTML files
$previewDir = __DIR__ . '/../email_previews';
if (!is_dir($previewDir)) {
    mkdir($previewDir, 0777, true);
}

// Render confirmation email
$confirmationTemplate = file_get_contents(__DIR__ . '/App/Views/emails/confirmation.html');
$confirmationRendered = str_replace(
    ['{{username}}', '{{confirmation_link}}'],
    [$mockUsername, $mockLink],
    $confirmationTemplate
);
file_put_contents($previewDir . '/confirmation_preview.html', $confirmationRendered);

// Render password recovery email
$recoveryTemplate = file_get_contents(__DIR__ . '/App/Views/emails/password_recovery.html');
$recoveryRendered = str_replace(
    ['{{username}}', '{{recovery_link}}'],
    [$mockUsername, $mockLink],
    $recoveryTemplate
);
file_put_contents($previewDir . '/password_recovery_preview.html', $recoveryRendered);

// Render comment notification email
$commentTemplate = file_get_contents(__DIR__ . '/App/Views/emails/comment_notification.html');
$commentRendered = str_replace(
    [
        '{{owner_username}}',
        '{{commenter_username}}',
        '{{comment_text}}',
        '{{image_path}}',
        '{{comment_link}}',
        '{{profile_settings_link}}'
    ],
    [
        $mockUsername,
        'VisitorCam',
        'Nossa, que foto minimalista incrível! Parabéns!',
        'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=600',
        'http://localhost:8000/post/42',
        'http://localhost:8000/profile/settings'
    ],
    $commentTemplate
);
file_put_contents($previewDir . '/comment_notification_preview.html', $commentRendered);

echo "=============================================\n";
echo "1. PREVIEW DE LAYOUT (HTML LOCAL):\n";
echo "=============================================\n";
echo "Arquivos HTML de preview gerados em: \n";
echo realpath($previewDir) . "\n\n";
echo "Voce pode abrir estes arquivos diretamente no Chrome ou Firefox para verificar o layout:\n";
echo "- confirmation_preview.html\n";
echo "- password_recovery_preview.html\n";
echo "- comment_notification_preview.html\n\n";

echo "=============================================\n";
echo "2. ENVIO DE E-MAILS REAIS PARA O MAILPIT:\n";
echo "=============================================\n";
if ($argc <= 1) {
    echo "Para enviar os e-mails de verdade para o Mailpit, execute:\n";
    echo "php src/test_emails.php seu-email@dominio.com\n\n";
} else {
    echo "Enviando e-mails para: $to...\n";
    
    // Send Confirmation
    $ok1 = \Core\Mailer::sendConfirmation($to, $mockUsername, $mockLink);
    echo "- E-mail de confirmacao: " . ($ok1 ? "ENVIADO" : "FALHOU") . "\n";
    
    // Send Password Recovery
    $ok2 = \Core\Mailer::sendPasswordRecovery($to, $mockUsername, $mockLink);
    echo "- E-mail de recuperacao de senha: " . ($ok2 ? "ENVIADO" : "FALHOU") . "\n";
    
    // Send Comment Notification
    $ok3 = \Core\Mailer::sendCommentNotification(
        $to,
        $mockUsername,
        'VisitorCam',
        'Nossa, que foto minimalista incrivel! Parabens!',
        'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=600',
        $mockLink
    );
    echo "- E-mail de notificacao de comentario: " . ($ok3 ? "ENVIADO" : "FALHOU") . "\n";
    
    echo "\nConcluido! Se o container do Mailpit no Docker estiver ativo,\n";
    echo "os e-mails poderao ser visualizados no painel do Mailpit em: http://localhost:8025/\n";
}
