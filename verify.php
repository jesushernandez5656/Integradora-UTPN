<?php
session_start();
require_once "config/db.php";
require_once "config/mailer.php";

$code  = $_GET['code']  ?? '';
$email = $_GET['email'] ?? '';

if ($code === '' || $email === '') {
    $_SESSION['alert'] = ["type" => "error", "message" => "Enlace inválido."];
    header("Location: login_register.php");
    exit;
}

// Buscar usuario
$stmt = $conn->prepare("SELECT id, name, user_type, verified FROM users WHERE email = ? AND verification_code = ? LIMIT 1");
$stmt->execute([$email, $code]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['alert'] = ["type" => "error", "message" => "Enlace inválido o ya usado."];
    header("Location: login_register.php");
    exit;
}

if ((int)$user['verified'] === 1) {
    $_SESSION['alert'] = ["type" => "info", "message" => "La cuenta ya está verificada. Puedes iniciar sesión."];
    header("Location: login_register.php");
    exit;
}

// ✅ Activar cuenta
$upd = $conn->prepare("UPDATE users SET verified = 1, verification_code = NULL WHERE id = ?");
$upd->execute([$user['id']]);

// ✅ Generar código 2FA para primera vez
$code2fa = rand(100000, 999999);
$expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));
$conn->prepare("INSERT INTO twofa_codes (user_id, code, expires_at) VALUES (?, ?, ?)")
     ->execute([$user['id'], $code2fa, $expires]);

// Guardar en sesión
$_SESSION["pending_user_id"] = $user["id"];
$_SESSION["pending_user_type"] = $user["user_type"];

// ✅ Enviar correo con el código
$subject = "Código de verificación 2FA - UTPN";
$body = "<p>Hola {$user['name']},</p>
<p>Tu codigo de verificacion es: <b>$code2fa</b></p>
<p>Este codigo vence en 10 minutos.</p>"
;
send_email($email, $user["name"], $subject, $body);

$_SESSION['alert'] = ["type" => "success", "message" => "✅ Cuenta verificada, introduce el código enviado a tu correo."];

// 👉 Redirigir a verify_2fa.php
header("Location: verify_2fa.php");
exit;
?>
