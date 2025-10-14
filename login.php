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
        $_SESSION["usuario_nombre"] = $usuario["name"]; // 🔹 Guarda el nombre

        // ✅ Redirigir según rol
if ($usuario["user_type"] == "admin") {
    // Obtener permisos del admin
    $permQuery = $conn->prepare("SELECT page, allowed FROM admin_permissions WHERE admin_id = ?");
    $permQuery->execute([$usuario["id"]]);
    $permisos = $permQuery->fetchAll(PDO::FETCH_KEY_PAIR); // ['recursos'=>1, 'reportes'=>0, ...]

    $_SESSION["permisos"] = $permisos; // 🔹 Guardar permisos en sesión

    header("Location: pages/admin/home_admin.php");
    exit;
}
 elseif ($usuario["user_type"] == "superadmin") {
            header("Location: pages/superadmin/home_superadmin.php");
        } else {
            header("Location: pages/alumno/home_alumno.php");
        }
        exit;
    } else {
        $_SESSION['alert'] = ["type" => "error", "message" => "❌ Correo o contraseña incorrectos."];
        header("Location: login_register.php");
        exit;
    }
}
