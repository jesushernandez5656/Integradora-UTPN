<?php
session_start();
require_once "config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // ✅ Consulta preparada para evitar SQL Injection
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // ✅ Verificar credenciales
    if ($usuario && password_verify($password, $usuario["password"])) {
        // ⚠️ Verificar si la cuenta está verificada
        if ($usuario["verified"] == 0) {
            $_SESSION['alert'] = ["type" => "info", "message" => "⚠️ Debes verificar tu cuenta antes de iniciar sesión."];
            header("Location: login_register.php");
            exit;
        }

        // ✅ Guardar sesión segura
        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["rol"] = $usuario["user_type"];
        $_SESSION["usuario_nombre"] = htmlspecialchars($usuario["name"], ENT_QUOTES, 'UTF-8');

        // 🚨 Redirigir según rol
        switch ($usuario["user_type"]) {
            case "admin":
                // Obtener permisos del admin
                $permQuery = $conn->prepare("SELECT page, allowed FROM admin_permissions WHERE admin_id = ?");
                $permQuery->execute([$usuario["id"]]);
                $permisos = $permQuery->fetchAll(PDO::FETCH_KEY_PAIR); // ['recursos'=>1, 'reportes'=>0, ...]

                $_SESSION["permisos"] = $permisos;
                header("Location: pages/admin/home_admin.php");
                break;

            case "superadmin":
                // 🟡 Podrías cargar permisos globales si aplica
                $_SESSION["permisos"] = ["global" => true];
                header("Location: pages/superadmin/home_superadmin.php");
                break;

            default:
                header("Location: pages/alumno/home_alumno.php");
                break;
        }
        exit;

    } else {
        $_SESSION['alert'] = ["type" => "error", "message" => "❌ Correo o contraseña incorrectos."];
        header("Location: login_register.php");
        exit;
    }
}
?>
