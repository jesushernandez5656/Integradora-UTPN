<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION["pending_user_id"], $_SESSION["reset_allowed"])) {
    $_SESSION['alert'] = ["type" => "error", "message" => "⚠️ Acceso inválido."];
    header("Location: login_register.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if ($password !== $confirm) {
        $_SESSION['alert'] = ["type" => "error", "message" => "❌ Las contraseñas no coinciden."];
        header("Location: reset_password.php");
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['alert'] = ["type" => "error", "message" => "⚠️ La contraseña debe tener al menos 8 caracteres."];
        header("Location: reset_password.php");
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashed, $_SESSION["pending_user_id"]]);

    unset($_SESSION["pending_user_id"], $_SESSION["reset_mode"], $_SESSION["reset_allowed"]);

    $_SESSION['alert'] = ["type" => "success", "message" => "✅ Contraseña actualizada. Ya puedes iniciar sesión."];
    header("Location: login_register.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nueva contraseña</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #0f0f0f;
      margin: 0;
      padding: 0;
      display: flex;
      height: 100vh;
      align-items: center;
      justify-content: center;
      color: #fff;
    }

    .container {
      width: 100%;
      max-width: 400px;
    }

    .form-box {
      background: #1c1c1c;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.6);
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
      color: #f5f5f5;
    }

    .input-box {
      position: relative;
      margin: 20px 0;
    }

    .input-box input {
      width: 100%;
      padding: 12px;
      background: #2a2a2a;
      border: none;
      outline: none;
      border-radius: 8px;
      color: #fff;
      font-size: 14px;
    }

    .input-box label {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      pointer-events: none;
      transition: 0.3s ease;
    }

    .input-box input:focus + label,
    .input-box input:valid + label {
      top: -8px;
      left: 8px;
      font-size: 12px;
      color: #ff9800;
    }

    .btn {
      width: 100%;
      padding: 12px;
      background: #ff9800;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      color: #fff;
      font-size: 15px;
      font-weight: bold;
      transition: background 0.3s;
    }

    .btn:hover {
      background: #e68900;
    }

    .switch {
      margin-top: 15px;
      font-size: 14px;
    }

    .switch a {
      color: #ff9800;
      text-decoration: none;
    }

    .switch a:hover {
      text-decoration: underline;
    }

    .alert-container {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 9999;
    }

    .alert {
      padding: 12px 20px;
      border-radius: 6px;
      margin-bottom: 10px;
      animation: fadeIn 0.5s ease;
    }

    .alert.success { background: #4caf50; color: #fff; }
    .alert.error { background: #f44336; color: #fff; }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <?php if (!empty($_SESSION['alert'])): ?>
    <div class="alert-container">
      <div class="alert <?= $_SESSION['alert']['type'] ?>">
        <?= htmlspecialchars($_SESSION['alert']['message']) ?>
      </div>
    </div>
    <?php unset($_SESSION['alert']); ?>
  <?php endif; ?>

  <div class="container">
    <div class="form-box">
      <form method="post" class="form">
        <h2>Nueva contraseña</h2>
        <div class="input-box">
          <input type="password" name="password" required>
          <label>Nueva contraseña</label>
        </div>
        <div class="input-box">
          <input type="password" name="confirm" required>
          <label>Confirmar contraseña</label>
        </div>
        <button type="submit" class="btn">Actualizar</button>
        <p class="switch"><a href="login_register.php">⬅ Volver al inicio</a></p>
      </form>
    </div>
  </div>

  <script>
    setTimeout(() => {
      document.querySelectorAll('.alert').forEach(a => {
        a.style.opacity = '0';
        setTimeout(() => a.remove(), 400);
      });
    }, 4000);
  </script>
</body>
</html>
