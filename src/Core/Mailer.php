<?php
namespace Core;

class Mailer {
    /**
     * Send a template-based email.
     *
     * @param string $to Recipient email address.
     * @param string $subject Email subject.
     * @param string $templateName Name of the HTML template file (without extension).
     * @param array $vars Key-value pairs to replace in the template.
     * @return bool True on success, false on failure.
     * @throws \Exception if the template file does not exist.
     */
    public static function send(string $to, string $subject, string $templateName, array $vars = []): bool {
        $templateFile = __DIR__ . '/../App/Views/emails/' . $templateName . '.html';
        if (!file_exists($templateFile)) {
            throw new \Exception("Email template file not found: " . $templateFile);
        }

        $body = file_get_contents($templateFile);

        // Replace placeholders (e.g. {{variable_name}} with its value)
        foreach ($vars as $key => $val) {
            $body = str_replace('{{' . $key . '}}', (string)$val, $body);
        }

        // Get sender address from environment or default to no-reply@localhost
        $fromEmail = getenv('MAIL_FROM') ?: 'no-reply@localhost';
        $fromName = getenv('MAIL_FROM_NAME') ?: 'Camagru';

        // Prepare email headers for HTML content
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . $fromName . ' <' . $fromEmail . '>',
            'Reply-To: ' . $fromEmail,
            'X-Mailer: PHP/' . phpversion()
        ];

        return mail($to, $subject, $body, implode("\r\n", $headers));
    }

    /**
     * Send account confirmation email.
     *
     * @param string $to Recipient email.
     * @param string $username Recipient username.
     * @param string $confirmationLink Activation link URL.
     * @return bool
     */
    public static function sendConfirmation(string $to, string $username, string $confirmationLink): bool {
        return self::send($to, 'Confirme sua conta no Camagru', 'confirmation', [
            'username' => $username,
            'confirmation_link' => $confirmationLink
        ]);
    }

    /**
     * Send comment notification email.
     *
     * @param string $to Recipient email (photo owner).
     * @param string $ownerUsername Photo owner username.
     * @param string $commenterUsername Commenter username.
     * @param string $commentText Comment text.
     * @param string $imagePath Path or URL to the commented image.
     * @param string $commentLink Link to the post/comment URL.
     * @return bool
     */
    public static function sendCommentNotification(
        string $to,
        string $ownerUsername,
        string $commenterUsername,
        string $commentText,
        string $imagePath,
        string $commentLink
    ): bool {
        // Resolve profile settings URL
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost:8000';
        $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $profileSettingsLink = $scheme . '://' . $host . '/profile/settings';

        return self::send($to, 'Novo comentário em sua foto!', 'comment_notification', [
            'owner_username' => $ownerUsername,
            'commenter_username' => $commenterUsername,
            'comment_text' => htmlspecialchars($commentText, ENT_QUOTES, 'UTF-8'),
            'image_path' => $imagePath,
            'comment_link' => $commentLink,
            'profile_settings_link' => $profileSettingsLink
        ]);
    }

    /**
     * Send password recovery email.
     *
     * @param string $to Recipient email.
     * @param string $username Recipient username.
     * @param string $recoveryLink Password redefinition URL.
     * @return bool
     */
    public static function sendPasswordRecovery(string $to, string $username, string $recoveryLink): bool {
        return self::send($to, 'Redefinição de Senha - Camagru', 'password_recovery', [
            'username' => $username,
            'recovery_link' => $recoveryLink
        ]);
    }
}
