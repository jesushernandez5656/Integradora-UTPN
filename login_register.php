<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema Multiusuario</title>
  <link rel="stylesheet" href="/UTPN/assets/css/login.css">
</head>
<body>

  <!-- 🔔 Alertas globales -->
  <?php include "includes/alerts.php"; ?>

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
          <input type="text" name="phone" required>
          <label>Teléfono</label>
        </div>
        <button type="submit" class="btn">Registrar</button>
        <p class="switch">¿Ya tienes cuenta? <a href="#" id="showLogin">Inicia Sesión</a></p>
      </form>
    </div>
  </div>

  <script src="/UTPN/assets/js/login.js"></script>

  <!-- Auto-cierre de alertas -->
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
