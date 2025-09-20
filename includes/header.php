<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>header</title>
    <link rel="stylesheet" href="/UTPN/assets/css/navbar.css">
</head>
<body>
<header>
    <nav class="navbar">
        <div class="navbar-logo">
            <a href="/UTPN/index.php"><img src="/UTPN/assets/img/Logo.png" alt="Logo UTPN"></a>
        </div>
        <div class="navbar-title">
            <h1>Bienvenidos a la UTPN</h1>
        </div>
        <div class="navbar-login">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/UTPN/logout.php" class="btn-login">Cerrar sesión (<?= htmlspecialchars($_SESSION['user_name'] ?? 'Cuenta') ?>)</a>
            <?php else: ?>
                <a href="/UTPN/login_register.php" class="btn-login">Iniciar sesión</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
