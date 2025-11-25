<?php
session_start();
require_once "config/db.php";
require_once "config/mailer.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validar campos vacios
    if (empty($nombre) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['alert'] = ["type" => "error", "message" => "❌ Todos los campos son obligatorios."];
        header("Location: login_register.php");
        exit;
    }

    // Validar contrasenas
    if ($password !== $confirm_password) {
        $_SESSION['alert'] = ["type" => "error", "message" => "❌ Las contrasenias no coinciden."];
        header("Location: login_register.php");
        exit;
    }

    // Validar dominio institucional
    if (!preg_match("/@utpn\.edu\.mx$/", $email)) {
        $_SESSION['alert'] = ["type" => "error", "message" => "❌ Solo se permiten correos institucionales (@utpn.edu.mx)."];
        header("Location: login_register.php");
        exit;
    }

    // Verificar si ya existe el correo
    $check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $check->execute([$email]);

    if ($check->fetch()) {
        $_SESSION['alert'] = ["type" => "error", "message" => "❌ Este correo ya esta registrado."];
        header("Location: login_register.php");
        exit;
    }

    // Hashear contrasena
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Crear token de verificacion
    $verification_code = bin2hex(random_bytes(16));

    // Asignar rol
    $superadmin_email = "tucorreo@utpn.edu.mx"; 
    $user_type = ($email === $superadmin_email) ? "superadmin" : "user";

    // Insertar usuario nuevo
    $sql = "INSERT INTO users (name, email, password, user_type, verified, verification_code, created_at)
            VALUES (?, ?, ?, ?, 0, ?, NOW())";

    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$nombre, $email, $hashed_password, $user_type, $verification_code])) {

        // Obtener IP del servidor
        $server_ip = $_SERVER['SERVER_ADDR']; 
        // Si falla, puedes ponerlo manual: $server_ip = "172.16.112.201";

        // Crear link de verificacion
        $verify_link = "http://$server_ip/Integradora-UTPN/verify.php?code=$verification_code&email=" . urlencode($email);

        // Cuerpo del correo sin acentos para evitar simbolos raros
        $body = "
            <h3>Hola $nombre</h3>
            <p>Gracias por registrarte en la plataforma UTPN.</p>
            <p>Haz clic en el siguiente enlace para verificar tu cuenta:</p>
            <p><a href='$verify_link' target='_blank'>$verify_link</a></p>
            <p>Si no fuiste tu, puedes ignorar este mensaje.</p>
        ";

        send_email($email, $nombre, $subject = "Verifica tu cuenta - UTPN", $body);

        $_SESSION['alert'] = ["type" => "success", "message" => "✅ Registro exitoso. Revisa tu correo para verificar la cuenta."];
        header("Location: login_register.php");
        exit;

    } else {
        $_SESSION['alert'] = ["type" => "error", "message" => "❌ Error al registrar usuario. Intenta nuevamente."];
        header("Location: login_register.php");
        exit;
    }
}
?>
