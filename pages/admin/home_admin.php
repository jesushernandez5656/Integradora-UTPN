<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "admin") {
    header("Location: ../../login_register.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin</title>
</head>
<body>
    <h1>Bienvenido Admin, <?= htmlspecialchars($_SESSION["user_name"]) ?> 👋</h1>
    <p>Este es tu panel de administración.</p>
    <a href="../../logout.php">Cerrar sesión</a>
</body>
</html>
