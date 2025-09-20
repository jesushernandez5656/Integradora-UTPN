<?php
session_start();
require_once "config/db.php";
require_once "config/mailer.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario["password"])) {
        if ($usuario["verified"] == 0) {
            $_SESSION['error'] = "⚠️ Debes verificar tu cuenta antes de iniciar sesión.";
            header("Location: login_register.php");
            exit;
        }

        // ✅ Generar código 2FA
        $code = rand(100000, 999999); // código de 6 dígitos
        $expires = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        // Guardar en la tabla twofa_codes
        $stmt = $conn->prepare("INSERT INTO twofa_codes (user_id, code, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$usuario["id"], $code, $expires]);

        // Enviar por correo
        $subject = "Código de verificación - UTPN";
        $body = "<h3>Hola {$usuario['name']},</h3>
                 <p>Tu código de verificación es:</p>
                 <h2>$code</h2>
                 <p>Este código expira en 5 minutos.</p>";

        send_email($usuario["email"], $usuario["name"], $subject, $body);

        // Guardar en sesión los datos pendientes
        $_SESSION["pending_user_id"] = $usuario["id"];
        $_SESSION["pending_user_type"] = $usuario["user_type"];

        // Redirigir a verify_2fa
        header("Location: verify_2fa.php");
        exit;

    } else {
        $_SESSION['error'] = "❌ Correo o contraseña incorrectos.";
        header("Location: login_register.php");
        exit;
    }
}
