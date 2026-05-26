# Passo 5: Sistema de Envio de E-mails Baseado em Templates

Para gerenciar o envio de e-mails transacionais de forma robusta, segura e sem utilizar bibliotecas de terceiros (como PHPMailer), implementamos a classe core **`Core\Mailer`** integrada a um sistema simplificado de templates HTML.

O envio de e-mails é essencial para as três principais interações do Camagru:
1. **Confirmação de Conta**: Link de verificação enviado logo após o cadastro do usuário.
2. **Notificação de Novo Comentário**: Alerta enviado ao autor de uma foto sempre que outro usuário comentar nela (respeitando as preferências de notificação do usuário).
3. **Recuperação de Senha**: Link seguro para redefinição de credenciais de acesso.

---

## Integração de Infraestrutura (msmtp e Mailpit)

O sistema de envio utiliza o utilitário nativo de e-mail do PHP e do ambiente Docker configurado:
- **msmtp**: Instalado no container de aplicação `camagru_web` e configurado no arquivo `/etc/msmtprc` para atuar como agente SMTP local.
- **PHP `mail()`**: Configurado no arquivo `sendmail.ini` para apontar diretamente para a linha de comando do `msmtp` (`sendmail_path = /usr/bin/msmtp -t`).
- **Mailpit**: Executa como servidor SMTP mock local (porta 1025) recebendo todos os despachos do `msmtp` e disponibilizando um painel Web no endereço `http://localhost:8025` para visualização e depuração dos e-mails em ambiente de desenvolvimento.

Dessa forma, chamadas simples à função padrão do PHP `mail()` são roteadas instantaneamente e de forma assíncrona para o Mailpit.

---

## Estrutura de Templates HTML (`src/App/Views/emails/`)

Os e-mails utilizam os layouts de design premium fornecidos na pasta de design do projeto. Eles foram organizados no diretório de visualizações de e-mail e parametrizados com marcadores de substituição dinâmicos no formato `{{nome_variavel}}`:

1. **[confirmation.html](file:///e:/42 rio/Camagru/src/App/Views/emails/confirmation.html)**:
   - Variáveis: `{{username}}` (saudação personalizada) e `{{confirmation_link}}` (link de ativação).
2. **[comment_notification.html](file:///e:/42 rio/Camagru/src/App/Views/emails/comment_notification.html)**:
   - Variáveis: `{{owner_username}}` (saudação ao autor), `{{commenter_username}}` (autor do comentário), `{{comment_text}}` (conteúdo do comentário), `{{comment_link}}` (link para visualizar a foto), `{{image_path}}` (miniatura da foto comentada) e `{{profile_settings_link}}` (link de opt-out).
3. **[password_recovery.html](file:///e:/42 rio/Camagru/src/App/Views/emails/password_recovery.html)**:
   - Variáveis: `{{username}}` (saudação personalizada) e `{{recovery_link}}` (link de redefinição de senha).

---

## Classe Core `Mailer` (`src/Core/Mailer.php`)

A classe **[Mailer.php](file:///e:/42 rio/Camagru/src/Core/Mailer.php)** possui os seguintes métodos estáticos prontos para uso:

### 1. Método Geral de Processamento e Envio
Carrega o arquivo do template do disco, substitui as variáveis informadas, define os cabeçalhos apropriados de e-mail HTML (UTF-8) e dispara a chamada do PHP:
```php
public static function send(string $to, string $subject, string $templateName, array $vars = []): bool
```

### 2. Métodos Helpers Específicos
Envelopam o método geral mapeando as variáveis corretas para cada template:
- `sendConfirmation(string $to, string $username, string $confirmationLink): bool`
- `sendCommentNotification(string $to, string $ownerUsername, string $commenterUsername, string $commentText, string $imagePath, string $commentLink): bool`
- `sendPasswordRecovery(string $to, string $username, string $recoveryLink): bool`

---

## Exemplos de Chamada no Código (Controllers)

Abaixo estão exemplos práticos de como invocar o envio de e-mails a partir dos Controllers da aplicação.

### 1. No Registro de Usuário (Confirmação de Conta)
```php
use Core\Mailer;

// Após salvar o novo usuário no banco com status inativo (active = 0)
$to = $newUser->email;
$username = $newUser->username;
$activationToken = $newUser->activation; // Token de ativação gerado no backend
$confirmationLink = "http://localhost:8000/activate?token=" . $activationToken;

$enviado = Mailer::sendConfirmation($to, $username, $confirmationLink);
if ($enviado) {
    // E-mail despachado para entrega
}
```

### 2. Na Publicação de um Comentário (Notificação)
```php
use Core\Mailer;
use Core\TableRegistry;

// Obter os dados da imagem e do autor para verificar se ele deseja receber notificações
$imagesTable = TableRegistry::get('Images');
$image = $imagesTable->get($imageId, ['contain' => ['Users']]);
$author = $image->user;

// Se o autor tiver a preferência ativa no banco (campo notify = 1)
if ($author->notify == 1) {
    $to = $author->email;
    $ownerUsername = $author->username;
    $commenterUsername = $currentSessionUser->username;
    $commentLink = "http://localhost:8000/photo/view/" . $image->id;
    $imagePath = $image->path; // Caminho relativo ou URL da foto

    Mailer::sendCommentNotification(
        $to,
        $ownerUsername,
        $commenterUsername,
        $commentText,
        $imagePath,
        $commentLink
    );
}
```

### 3. Na Solicitação de Esqueci Minha Senha (Recuperação)
```php
use Core\Mailer;

// Após gerar o token de redefinição e salvar no banco para o usuário solicitado
$to = $user->email;
$username = $user->username;
$resetToken = $user->reset_token; // Token temporário gerado
$recoveryLink = "http://localhost:8000/reset-password?token=" . $resetToken;

Mailer::sendPasswordRecovery($to, $username, $recoveryLink);
```
