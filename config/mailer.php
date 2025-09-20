<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Función para enviar correos
 */
function send_email($to, $toName, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP (Office 365 institucional)
        $mail->isSMTP();
        $mail->Host       = 'smtp.office365.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = '24110010@utpn.edu.mx'; 
        $mail->Password   = 'Jenifer16'; // ⚠️ usa un app password si puedes
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Remitente
        $mail->setFrom('24110010@utpn.edu.mx', 'UTPN - Sistema');

        // Destinatario
        $mail->addAddress($to, $toName);

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo: {$mail->ErrorInfo}");
        return false;
    }
}
