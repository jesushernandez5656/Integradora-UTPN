<?php
session_start();
require __DIR__ . '/config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT id, name, email, password, user_type, verified FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["user_type"] = $user["user_type"];

        // Redirigir por rol
        if ($user["user_type"] === "superadmin") {
            header("Location: pages/superadmin/superadmin.php");
        } elseif ($user["user_type"] === "admin") {
            header("Location: pages/admin/home_admin.php");
        } else {
            header("Location: pages/alumno/becas.php");
        }
        exit;
    } else {
        echo "❌ Correo o contraseña incorrectos.";
    }
}
