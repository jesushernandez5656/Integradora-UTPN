<?php
session_start();
require_once "config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario["password"])) {
        if ($usuario["verified"] == 0) {
            $_SESSION['alert'] = ["type" => "info", "message" => "⚠️ Debes verificar tu cuenta antes de iniciar sesión."];
            header("Location: login_register.php");
            exit;
        }

        // ✅ Guardar sesión
        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["rol"] = $usuario["user_type"];

        // ✅ Redirigir según rol
        if ($usuario["user_type"] == "admin") {
            header("Location: pages/admin/home_admin.php");
        } elseif ($usuario["user_type"] == "superadmin") {
            header("Location: pages/superadmin/superadmin.php");
        } else {
            header("Location: pages/alumno/becas.php");
        }
        exit;
    } else {
        $_SESSION['alert'] = ["type" => "error", "message" => "❌ Correo o contraseña incorrectos."];
        header("Location: login_register.php");
        exit;
    }
}
