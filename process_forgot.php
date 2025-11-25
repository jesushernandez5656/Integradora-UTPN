<?php
session_start();
require_once "config/db.php";
require_once "config/mailer.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    $stmt = $conn->prepare("SELECT id, name FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $code = rand(100000, 999999);
        $expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        $insert = $conn->prepare("INSERT INTO twofa_codes (user_id, code, expires_at) VALUES (?, ?, ?)");
        $insert->execute([$user['id'], $code, $expires]);

        $_SESSION["pending_user_id"] = $user['id'];
        $_SESSION["reset_mode"] = true; // 🔹 diferenciamos que es recuperación

        $subject = "Recuperacion de contraseña - UTPN";
        $body = "<p>Hola {$user['name']},</p>
                 <p>Tu codigo de recuperacion es: <b>$code</b></p>
                 <p>Este codigo vence en 10 minutos.</p>";

        send_email($email, $user['name'], $subject, $body);

        $_SESSION['alert'] = ["type" => "success", "message" => "📧 Código enviado a tu correo institucional."];

        header("Location: verify_reset.php");
        exit;
    } else {
        $_SESSION['alert'] = ["type" => "error", "message" => "❌ No encontramos ese correo."];
        header("Location: forgot_password.php");
        exit;
    }
}
