<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" href="/Integradora-UTPN/assets/css/navbar.css">
<header>
    <nav class="navbar">
        <div class="navbar-logo">
            <a href="/integradora-UTPN/index.php"><img src="/integradora-UTPN/assets/img/Logo.png" alt="Logo UTPN"></a>
        </div>
        <div class="navbar-title">
            <h1>Bienvenidos a la UTPN</h1>
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <span class="user-name"> <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></span>
            <?php endif; ?>
        </div>
        <div class="navbar-login">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="/integradora-UTPN/logout.php" class="btn-login">Cerrar sesión</a>
            <?php else: ?>
                <a href="/integradora-UTPN/login_register.php" class="btn-login">Iniciar sesión</a>
            <?php endif; ?>
        </div>
    </nav>
</header>