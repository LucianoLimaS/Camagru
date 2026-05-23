<?php
// PHP Script to test database connection and mail delivery
$db_connected = false;
$db_error = '';
try {
    $host = getenv('DB_HOST') ?: 'mariadb';
    $db   = getenv('DB_DATABASE') ?: 'camagru';
    $user = getenv('DB_USER') ?: 'camagru_user';
    $pass = getenv('DB_PASSWORD') ?: 'camagru_pass';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);
    $db_connected = true;
} catch (\PDOException $e) {
    $db_error = $e->getMessage();
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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru - Ambiente de Desenvolvimento</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --success: #10b981;
            --success-bg: rgba(16, 185, 129, 0.1);
            --danger: #ef4444;
            --danger-bg: rgba(239, 68, 68, 0.1);
            --border: #334155;
            --glow: rgba(99, 102, 241, 0.15);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(16, 185, 129, 0.06) 0%, transparent 40%);
        }

        .container {
            width: 100%;
            max-width: 800px;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        header {
            text-align: center;
            margin-bottom: 1rem;
        }

        header h1 {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #a5b4fc 0%, #6366f1 50%, #4338ca 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            letter-spacing: -1px;
        }

        header p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            font-weight: 300;
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 0 30px var(--glow);
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 0.75rem;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
        }

        .status-item {
            background-color: rgba(15, 23, 42, 0.4);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .status-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .status-value {
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        .indicator.success {
            background-color: var(--success);
            box-shadow: 0 0 10px var(--success);
        }

        .indicator.danger {
            background-color: var(--danger);
            box-shadow: 0 0 10px var(--danger);
        }

        .btn {
            background-color: var(--primary);
            color: var(--text-primary);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-family: inherit;
        }

        .btn:hover {
            background-color: var(--primary-hover);
        }

        .btn:active {
            transform: scale(0.98);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.95rem;
            margin-top: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .alert.success {
            background-color: var(--success-bg);
            border: 1px solid var(--success);
            color: #34d399;
        }

        .alert.danger {
            background-color: var(--danger-bg);
            border: 1px solid var(--danger);
            color: #f87171;
            font-family: monospace;
            font-size: 0.85rem;
            white-space: pre-wrap;
            overflow-x: auto;
        }

        .links-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .link-card {
            background-color: rgba(15, 23, 42, 0.4);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
        }

        .link-card:hover {
            border-color: var(--primary);
            background-color: rgba(99, 102, 241, 0.05);
            transform: translateY(-2px);
        }

        .link-card .title {
            font-weight: 600;
            font-size: 1rem;
        }

        .link-card .url {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        footer {
            text-align: center;
            margin-top: 2rem;
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        footer a {
            color: var(--primary);
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>Camagru</h1>
        <p>Ambiente de Desenvolvimento Docker ativado com sucesso 🚀</p>
    </header>

    <!-- Status da Infraestrutura -->
    <section class="card">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
            Status do Sistema
        </h2>
        <div class="status-grid">
            <div class="status-item">
                <span class="status-label">Servidor Web</span>
                <span class="status-value">
                    <span class="indicator success"></span>
                    Apache / PHP <?= phpversion() ?>
                </span>
            </div>

            <div class="status-item">
                <span class="status-label">Banco de Dados</span>
                <span class="status-value">
                    <?php if ($db_connected): ?>
                        <span class="indicator success"></span> Conectado
                    <?php else: ?>
                        <span class="indicator danger"></span> Desconectado
                    <?php endif; ?>
                </span>
            </div>

            <div class="status-item">
                <span class="status-label">Biblioteca GD</span>
                <span class="status-value">
                    <?php if ($gd_loaded): ?>
                        <span class="indicator success"></span> Ativa (<?= htmlspecialchars($gd_version) ?>)
                    <?php else: ?>
                        <span class="indicator danger"></span> Inativa
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <?php if (!$db_connected): ?>
            <div class="alert danger">
                <strong>Erro de Conexão com o Banco de Dados (MariaDB):</strong><br>
                <?= htmlspecialchars($db_error) ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Testador de E-mails (Mailpit) -->
    <section class="card">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            Teste de Envio de E-mails
        </h2>
        <form method="POST">
            <p style="color: var(--text-secondary); margin-bottom: 1rem; font-size: 0.95rem; line-height: 1.5;">
                O Camagru precisa enviar e-mails para confirmação de conta e recuperação de senha. Use o botão abaixo para enviar um e-mail de teste direto deste container. Você poderá visualizá-lo abrindo o painel do Mailpit.
            </p>
            <button type="submit" name="send_email" class="btn">
                Enviar E-mail de Teste
            </button>
        </form>

        <?php if ($email_sent === true): ?>
            <div class="alert success">
                <strong>E-mail enviado!</strong> Verifique a caixa de entrada em seu <a href="http://localhost:8025" target="_blank" style="color: #6ee7b7; font-weight: 600; text-decoration: underline;">Mailpit Dashboard</a>.
            </div>
        <?php elseif ($email_sent === false): ?>
            <div class="alert danger">
                <strong>Erro ao enviar e-mail:</strong><br>
                <?= htmlspecialchars($email_error) ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Atalhos Rápidos -->
    <section class="card">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
            Links de Acesso Rápido
        </h2>
        <div class="links-grid">
            <a href="http://localhost:8000" target="_blank" class="link-card">
                <span class="title">Website (Porta 8000)</span>
                <span class="url">localhost:8000</span>
            </a>
            <a href="http://localhost:8085" target="_blank" class="link-card">
                <span class="title">phpMyAdmin (Banco)</span>
                <span class="url">localhost:8085</span>
            </a>
            <a href="http://localhost:8025" target="_blank" class="link-card">
                <span class="title">Mailpit Dashboard</span>
                <span class="url">localhost:8025</span>
            </a>
        </div>
    </section>
</div>

<footer>
    Camagru - Desenvolvido para a <a href="https://www.42sp.org.br/" target="_blank">42 Rio/SP</a>. Ambiente Docker 🐳
</footer>

</body>
</html>
