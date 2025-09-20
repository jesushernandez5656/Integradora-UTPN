<?php
session_start();
require_once "config/db.php";
require_once "config/mailer.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validar campos vacíos
    if (empty($nombre) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['alert'] = ["type" => "error", "message" => "❌ Todos los campos son obligatorios."];
        header("Location: login_register.php");
        exit;
    }

    // Validar contraseñas
    if ($password !== $confirm_password) {
        $_SESSION['alert'] = ["type" => "error", "message" => "❌ Las contraseñas no coinciden."];
        header("Location: login_register.php");
        exit;
    }

    // Validar dominio
    if (!preg_match("/@utpn\.edu\.mx$/", $email)) {
        $_SESSION['alert'] = ["type" => "error", "message" => "❌ Solo se permiten correos institucionales (@utpn.edu.mx)."];
        header("Location: login_register.php");
        exit;
    }

    // Verificar si ya existe ese correo
    $check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $check->execute([$email]);
    if ($check->fetch()) {
        $_SESSION['alert'] = ["type" => "error", "message" => "❌ Este correo ya está registrado."];
        header("Location: login_register.php");
        exit;
    }

    // Hashear contraseña
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Generar token de verificación
    $verification_code = bin2hex(random_bytes(16));

    // Insertar usuario
    $sql = "INSERT INTO users (name, email, password, user_type, verified, verification_code, created_at) 
            VALUES (?, ?, ?, 'user', 0, ?, NOW())";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$nombre, $email, $hashed_password, $verification_code])) {
        // Enviar correo de verificación
        $subject = "Verifica tu cuenta - UTPN";
        $verify_link = "http://localhost/UTPN/verify.php?code=$verification_code&email=" . urlencode($email);

        $body = "
            <h3>Hola $nombre,</h3>
            <p>Gracias por registrarte en la plataforma UTPN.</p>
            <p>Haz clic en el siguiente enlace para verificar tu cuenta:</p>
            <p><a href='$verify_link' target='_blank'>$verify_link</a></p>
            <p>Si no fuiste tú, ignora este correo.</p>
        ";

        send_email($email, $nombre, $subject, $body);

        $_SESSION['alert'] = ["type" => "success", "message" => "✅ Registro exitoso. Revisa tu correo institucional para verificar tu cuenta."];
        header("Location: login_register.php");
        exit;
    } else {
        $_SESSION['alert'] = ["type" => "error", "message" => "❌ Error al registrar usuario. Intenta nuevamente."];
        header("Location: login_register.php");
        exit;
    }
}
