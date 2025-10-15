<?php

namespace Utilities;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function sendRecoveryEmail(string $toEmail, string $link): bool
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = getenv("SMTP_HOST");
            $mail->SMTPAuth = true;
            $mail->Username = getenv("SMTP_USER");
            $mail->Password = getenv("SMTP_SECRET");
            $mail->SMTPSecure = 'tls';
            $mail->Port = getenv("SMTP_PORT");

            $mail->setFrom('no-reply@software-espresso.com', 'Software Espresso');
            $mail->addAddress($toEmail);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña';
            $mail->Body = "
                <p>Hola,</p>
                <p>Recibimos tu solicitud para restablecer la contraseña.</p>
                <p><a href='$link'>Haz clic aquí para restablecer tu contraseña</a></p>
                <p>Este enlace expirará en 1 hora. Si no solicitaste este cambio, puedes ignorar este mensaje.</p>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer error: " . $e->getMessage());
            return false;
        }
    }
}