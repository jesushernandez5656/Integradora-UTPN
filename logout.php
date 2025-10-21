<?php
// logout.php
session_start();

// Limpiar arreglo de sesión
$_SESSION = [];

// Borrar cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// Destruir sesión
session_destroy();

header('Location: index.php');
exit;
