<?php
session_start();
require_once "config/db.php";
require_once "config/mailer.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = trim($_POST["nombre"]);
    $email    = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    // Validar dominio
    if (!preg_match("/@utpn\.edu\.mx$/", $email)) {
        $_SESSION['error'] = "❌ Solo se permiten correos institucionales (@utpn.edu.mx).";
        header("Location: login_register.php");
        exit;
    }

    // Generar código de verificación
    $code = bin2hex(random_bytes(32));

    // Insertar usuario con verification_code
    $sql = "INSERT INTO users (name, email, password, user_type, verified, verification_code, created_at) 
            VALUES (?, ?, ?, 'alumno', 0, ?, NOW())";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$nombre, $email, $password, $code])) {
        // Enlace de verificación
        $link = "http://localhost/UTPN/verify.php?code=$code&email=$email";

        // Enviar correo
        $subject = "Verifica tu cuenta - UTPN";
        $body = "<h3>Hola $nombre,</h3>
                 <p>Gracias por registrarte en UTPN.</p>
                 <p>Por favor verifica tu cuenta haciendo clic en el siguiente enlace:</p>
                 <a href='$link'>$link</a>";

        send_email($email, $nombre, $subject, $body);

        $_SESSION['success'] = "✅ Registro exitoso, revisa tu correo institucional para verificar la cuenta.";
        header("Location: login_register.php");
        exit;
    } else {
        $_SESSION['error'] = "❌ Error al registrar usuario.";
        header("Location: login_register.php");
        exit;
    }
}
