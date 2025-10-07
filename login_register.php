<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema Multiusuario</title>
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
  background: linear-gradient(135deg, #4facfe, #00f2fe);
}

.container {
  position: relative;
  width: 380px;
  background: #fff;
  padding: 40px;
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
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
}

.input-box {
  position: relative;
}

.input-box input {
  width: 100%;
  padding: 10px;
  border: none;
  border-bottom: 2px solid #ccc;
  outline: none;
  transition: 0.3s;
}

.input-box label {
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
  transition: 0.3s;
  color: #aaa;
}

.input-box input:focus,
.input-box input:valid {
  border-bottom: 2px solid #4facfe;
}

.input-box input:focus + label,
.input-box input:valid + label {
  top: -5px;
  font-size: 12px;
  color: #4facfe;
}

.btn {
  padding: 10px;
  border: none;
  background: #4facfe;
  color: #fff;
  font-size: 16px;
  cursor: pointer;
  border-radius: 8px;
  transition: 0.3s;
}

.btn:hover {
  background: #00c6ff;
}

.switch {
  text-align: center;
  font-size: 14px;
}

.switch a {
  color: #4facfe;
  text-decoration: none;
  font-weight: bold;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* 🔔 Alertas globales arriba */
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
}

.alert.success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
.alert.error   { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
.alert.info    { background:#d1ecf1; color:#0c5460; border:1px solid #bee5eb; }

/* Animación */
@keyframes fadeSlide {
  from { opacity:0; transform: translate(-50%, -20px); }
  to { opacity:1; transform: translate(-50%, 0); }
}

/* 🔔 Contenedor global de alertas */
.alert-container {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  width: 90%;
  max-width: 400px;
  z-index: 9999;
}

/* 🎨 Estilos base */
.alert {
  padding: 12px 18px;
  margin-bottom: 10px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  text-align: center;
  box-shadow: 0 4px 8px rgba(0,0,0,0.15);
  opacity: 1;
  transition: opacity 0.4s ease;
}

/* ✅ Éxito */
.alert.success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

/* ❌ Error */
.alert.error {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

/* ℹ️ Información */
.alert.info {
  background-color: #d1ecf1;
  color: #0c5460;
  border: 1px solid #bee5eb;
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

/* 📱 Ajuste para pantallas pequeñas */
@media (max-width: 480px) {
  .logo {
    height: 45px;
  }
}


  </style>

  <!-- 🔹 Logo superior izquierdo -->
<a href="index.php" class="logo-link">
  <img src="/Integradora-UTPN/assets/img/logo.png" alt="UTPN Logo" class="logo">
</a>


  <div class="container">
    <div class="form-box">

<!-- LOGIN -->
<form class="form login-form" method="POST" action="login.php">
  <h2>Iniciar Sesión</h2>
  <div class="input-box">
    <input type="email" name="email" required>
    <label>Correo</label>
  </div>
  <div class="input-box">
    <input type="password" name="password" required>
    <label>Contraseña</label>
  </div>
  <button type="submit" class="btn">Ingresar</button>
  <p class="switch">¿No tienes cuenta? <a href="#" id="showRegister">Regístrate</a></p>
  <p class="switch"><a href="forgot_password.php">¿Olvidaste tu contraseña?</a></p> <!-- 🔹 Nuevo -->
</form>


      <!-- REGISTRO -->
      <form class="form register-form" method="POST" action="register.php">
        <h2>Registrarse</h2>
        <div class="input-box">
          <input type="text" name="nombre" required>
          <label>Nombre</label>
        </div>
        <div class="input-box">
          <input type="email" name="email" required>
          <label>Correo</label>
        </div>
        <div class="input-box">
          <input type="password" name="password" required>
          <label>Contraseña</label>
        </div>
        <div class="input-box">
          <input type="password" name="confirm_password" required>
          <label>Verifica tu contraseña</label>
        </div>
        <div class="input-box">
          <input type="text" name="phone" required>
          <label>Teléfono</label>
        </div>
        <button type="submit" class="btn">Registrar</button>
        <p class="switch">¿Ya tienes cuenta? <a href="#" id="showLogin">Inicia Sesión</a></p>
      </form>
    </div>
  </div>

  <script src="/integradora-UTPN/assets/js/login.js"></script>

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
