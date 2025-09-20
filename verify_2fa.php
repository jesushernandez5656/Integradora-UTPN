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
            $del = $conn->prepare("DELETE FROM twofa_codes WHERE user_id = ?");
            $del->execute([$user_id]);

            $_SESSION["usuario_id"] = $user_id;
            $_SESSION["rol"] = $_SESSION["pending_user_type"];

            unset($_SESSION["pending_user_id"], $_SESSION["pending_user_type"]);

            if ($_SESSION["rol"] == "admin") {
                header("Location: pages/admin/home_admin.php");
            } elseif ($_SESSION["rol"] == "superadmin") {
                header("Location: pages/superadmin/superadmin.php");
            } else {
                header("Location: pages/alumno/becas.php");
            }
            exit;
        } else {
            $_SESSION['alert'] = ["type" => "error", "message" => "❌ Código inválido o caducado."];
            header("Location: verify_2fa.php");
            exit;
        }
    } else {
        $_SESSION['alert'] = ["type" => "error", "message" => "⚠️ No hay sesión pendiente. Intenta iniciar sesión de nuevo."];
        header("Location: login_register.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verificación 2FA</title>
  <link rel="stylesheet" href="/UTPN/assets/css/login.css">
</head>
<body>

  <div class="container">
    <div class="form-box">
      <form method="post" class="form" style="display:flex; flex-direction:column; gap:15px;">
        <h2>Verificación en dos pasos</h2>
        <div class="input-box">
          <input type="text" name="code" required>
          <label>Código</label>
        </div>
        <button type="submit" class="btn">Verificar</button>
      </form>
    </div>
  </div>

  <!-- 📢 Alertas dinámicas -->
  <script>
    function showAlert(message, type = "info") {
      const container = document.createElement("div");
      container.className = "alert-container";
      container.innerHTML = `<div class="alert ${type}">${message}</div>`;
      document.body.appendChild(container);

      setTimeout(() => {
        container.style.opacity = "0";
        setTimeout(() => container.remove(), 400);
      }, 4000);
    }

    <?php if (isset($_SESSION['alert'])): ?>
      showAlert("<?= $_SESSION['alert']['message'] ?>", "<?= $_SESSION['alert']['type'] ?>");
      <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>
  </script>
</body>
</html>
