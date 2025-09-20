<?php
session_start();
require_once "config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code = trim($_POST["code"]);
    $user_id = $_SESSION["pending_user_id"] ?? null;

    if ($user_id) {
        $sql = "SELECT * FROM twofa_codes 
                WHERE user_id = ? AND code = ? AND expires_at > NOW() 
                ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id, $code]);

        if ($stmt->rowCount() > 0) {
            // ✅ Borrar código usado
            $del = $conn->prepare("DELETE FROM twofa_codes WHERE user_id = ?");
            $del->execute([$user_id]);

            // ✅ Permitir el reseteo de contraseña
            $_SESSION["reset_allowed"] = true;

            header("Location: reset_password.php");
            exit;
        } else {
            $_SESSION['alert'] = ["type" => "error", "message" => "❌ Código inválido o caducado."];
            header("Location: verify_reset.php");
            exit;
        }
    } else {
        $_SESSION['alert'] = ["type" => "error", "message" => "⚠️ No hay solicitud activa de recuperación."];
        header("Location: forgot_password.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verificar código</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #1a73e8, #0f2027);
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .container {
      width: 100%;
      max-width: 380px;
      padding: 20px;
    }
    .form-box {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }
    .form h2 {
      margin: 0 0 20px;
      font-size: 22px;
      text-align: center;
      color: #333;
    }
    .input-box {
      position: relative;
      margin-bottom: 20px;
    }
    .input-box input {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      outline: none;
      font-size: 14px;
    }
    .input-box label {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      background: #fff;
      padding: 0 4px;
      font-size: 13px;
      color: #777;
      transition: 0.3s;
      pointer-events: none;
    }
    .input-box input:focus + label,
    .input-box input:valid + label {
      top: -8px;
      left: 8px;
      font-size: 12px;
      color: #1a73e8;
    }
    .btn {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      background: #1a73e8;
      color: #fff;
      font-size: 15px;
      cursor: pointer;
      transition: 0.3s;
    }
    .btn:hover {
      background: #0d47a1;
    }
    .switch {
      text-align: center;
      margin-top: 15px;
    }
    .switch a {
      color: #1a73e8;
      text-decoration: none;
      font-size: 14px;
    }
    .switch a:hover {
      text-decoration: underline;
    }
    .alert-container {
      margin-bottom: 15px;
    }
    .alert {
      padding: 10px;
      border-radius: 8px;
      font-size: 14px;
      margin-bottom: 10px;
      text-align: center;
      animation: fadeIn 0.5s;
    }
    .alert.success { background: #d4edda; color: #155724; }
    .alert.error   { background: #f8d7da; color: #721c24; }
    .alert.info    { background: #d1ecf1; color: #0c5460; }

    @keyframes fadeIn {
      from {opacity: 0;}
      to {opacity: 1;}
    }
  </style>
</head>
<body>

  <!-- Alertas -->
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
        <h2>Verificación de código</h2>
        <div class="input-box">
          <input type="text" name="code" required>
          <label>Código recibido</label>
        </div>
        <button type="submit" class="btn">Verificar</button>
        <p class="switch"><a href="forgot_password.php">⬅ Volver</a></p>
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
