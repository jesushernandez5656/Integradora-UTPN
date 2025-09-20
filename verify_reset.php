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
    /* Fondo degradado */
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    /* Contenedor principal */
    .container {
      width: 100%;
      max-width: 400px;
      padding: 20px;
    }

    .form-box {
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
      color: #333;
    }

    /* Input */
    .input-box {
      position: relative;
      margin-bottom: 20px;
    }

    .input-box input {
      width: 100%;
      padding: 10px;
      border: none;
      border-bottom: 2px solid #ccc;
      outline: none;
      font-size: 16px;
      background: transparent;
    }

    .input-box label {
      position: absolute;
      left: 0;
      top: 10px;
      pointer-events: none;
      transition: 0.3s ease all;
      color: #666;
    }

    .input-box input:focus ~ label,
    .input-box input:valid ~ label {
      top: -15px;
      font-size: 12px;
      color: #4facfe;
    }

    /* Botón */
    .btn {
      width: 100%;
      padding: 10px;
      border: none;
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      color: #fff;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn:hover {
      background: linear-gradient(135deg, #00f2fe, #4facfe);
    }

    /* Link volver */
    .switch {
      margin-top: 15px;
    }

    .switch a {
      color: #4facfe;
      text-decoration: none;
      font-size: 14px;
    }

    .switch a:hover {
      text-decoration: underline;
    }

    /* Alertas */
    .alert-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1000;
    }

    .alert {
      padding: 12px 20px;
      border-radius: 8px;
      color: #fff;
      margin-bottom: 10px;
      animation: fadeIn 0.5s ease;
    }

    .alert.success { background: #4caf50; }
    .alert.error { background: #f44336; }
    .alert.info { background: #2196f3; }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
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
