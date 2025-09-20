<?php
session_start();
require_once "config/db.php";

$code  = $_GET['code']  ?? '';
$email = $_GET['email'] ?? '';

if ($code === '' || $email === '') {
    $_SESSION['error'] = "Enlace inválido.";
    header("Location: login_register.php");
    exit;
}

// Buscar usuario con ese código y sin verificar
$stmt = $conn->prepare("SELECT id, verified FROM users WHERE email = ? AND verification_code = ? LIMIT 1");
$stmt->execute([$email, $code]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error'] = "Enlace inválido o ya usado.";
    header("Location: login_register.php");
    exit;
}

if ((int)$user['verified'] === 1) {
    $_SESSION['info'] = "La cuenta ya está verificada. Puedes iniciar sesión.";
    header("Location: login_register.php");
    exit;
}

// Activar cuenta
$upd = $conn->prepare("UPDATE users SET verified = 1, verification_code = NULL WHERE id = ?");
$upd->execute([$user['id']]);

$_SESSION['success'] = "Cuenta verificada correctamente. Ahora puedes iniciar sesión.";
header("Location: login_register.php");
exit;
?>
