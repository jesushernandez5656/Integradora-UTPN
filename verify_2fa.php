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
            // ✅ Borrar códigos viejos de este usuario
            $del = $conn->prepare("DELETE FROM twofa_codes WHERE user_id = ?");
            $del->execute([$user_id]);

            // ✅ Recuperar info del usuario para guardarla en sesión
            $user_sql = "SELECT id, name, user_type FROM users WHERE id = ? LIMIT 1";
            $user_stmt = $conn->prepare($user_sql);
            $user_stmt->execute([$user_id]);
            $usuario = $user_stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["rol"] = $usuario["user_type"];
                $_SESSION["usuario_nombre"] = $usuario["name"]; // ✅ Guardar el nombre

                // Eliminar variables temporales
                unset($_SESSION["pending_user_id"], $_SESSION["pending_user_type"]);

                // ✅ Redirigir según rol
                if ($usuario["user_type"] == "admin") {
                    header("Location: pages/admin/home_admin.php");
                } elseif ($usuario["user_type"] == "superadmin") {
                    header("Location: pages/superadmin/home_superadmin.php");
                } else {
                    header("Location: pages/alumno/home_alumno.php");
                }
                exit;
            } else {
                $_SESSION['alert'] = ["type" => "error", "message" => "⚠️ Usuario no encontrado."];
                header("Location: login_register.php");
                exit;
            }
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
</head>
<body>
   <style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, "Helvetica Neue", Arial, sans-serif;
  color: var(--txt);
  background-color: #EDE5D6; /* 🎨 crema claro, cálido y suave */

}

.container {
  position: relative;
  width: 380px;
  background: #fff;
  padding: 40px;
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0, 131, 127, 0.2); /* sombra teal suave */
  overflow: hidden;
}

.form {
  display: none;
  flex-direction: column;
  gap: 15px;
  animation: fadeIn 0.4s ease forwards;
}

.form h2 {
  text-align: center;
  margin-bottom: 10px;
  color: #00837F; /* Teal */
}

.input-box {
  position: relative;
}

.input-box input {
  width: 100%;
  padding: 10px;
  border: none;
  border-bottom: 2px solid #D0D1D1; /* Gris muy claro */
  outline: none;
  transition: 0.3s;
  color: #7E8080; /* Gris medio */
}

.input-box label {
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
  transition: 0.3s;
  color: #7E8080; /* Gris medio */
}

.input-box input:focus,
.input-box input:valid {
  border-bottom: 2px solid #00837F; /* Teal */
}

.input-box input:focus + label,
.input-box input:valid + label {
  top: -5px;
  font-size: 12px;
  color: #00837F; /* Teal */
}

.btn {
  padding: 10px;
  border: none;
  background: #00837F; /* Teal */
  color: #fff;
  font-size: 16px;
  cursor: pointer;
  border-radius: 8px;
  transition: 0.3s;
}

.btn:hover {
  background: #AE874C; /* Oro viejo */
}

.switch {
  text-align: center;
  font-size: 14px;
}

.switch a {
  color: #00837F; /* Teal */
  text-decoration: none;
  font-weight: bold;
}

.switch a:hover {
  color: #AE874C; /* Oro viejo */
}

/* Animaciones */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* 🔔 Alertas globales */
.alert-container {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  width: 90%;
  max-width: 400px;
  z-index: 9999;
}

.alert {
  padding: 12px 18px;
  margin-bottom: 12px;
  border-radius: 8px;
  font-size: 14px;
  text-align: center;
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
  animation: fadeSlide 0.5s ease forwards;
  font-weight: 500;
}

/* Tipos de alertas */
.alert.success { background-color: #00837F; color: #fff; border: 1px solid #00837F; }
.alert.error   { background-color: #AE874C; color: #fff; border: 1px solid #AE874C; }
.alert.info    { background-color: #7E8080; color: #fff; border: 1px solid #7E8080; }

@keyframes fadeSlide {
  from { opacity:0; transform: translate(-50%, -20px); }
  to { opacity:1; transform: translate(-50%, 0); }
}

/* 📱 Responsive Login/Registro */
@media (max-width: 768px) {
  .container {
    width: 90%;
    padding: 20px;
  }

  .form h2 {
    font-size: 20px;
  }

  .input-box input {
    font-size: 14px;
    padding: 8px;
  }

  .btn {
    font-size: 14px;
    padding: 8px;
  }
}

@media (max-width: 480px) {
  body {
    padding: 10px;
  }

  .container {
    width: 100%;
    border-radius: 10px;
    box-shadow: none;
    padding: 15px;
  }

  .form {
    gap: 10px;
  }

  .form h2 {
    font-size: 18px;
  }

  .switch {
    font-size: 12px;
  }
}

/* 🔹 Logo fijo arriba a la izquierda */
.logo-link {
  position: fixed;
  top: 20px;
  left: 20px;
  z-index: 1000;
  text-decoration: none;
}

.logo {
  height: 100px;
  width: auto;
  transition: transform 0.3s ease;
}

.logo:hover {
  transform: scale(1.05);
}

@media (max-width: 480px) {
  .logo {
    height: 45px;
  }
}

  </style>

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
