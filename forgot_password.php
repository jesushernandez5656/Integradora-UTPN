<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar contraseña</title>
  <style>
  /* Fondo */
body {
  margin: 0;
  padding: 0;
  font-family: Arial, sans-serif;
  background: #EDE5D6; /* Crema claro */
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
  box-shadow: 0 8px 20px rgba(0, 131, 127, 0.2); /* sombra teal suave */
  text-align: center;
}

h2 {
  margin-bottom: 20px;
  color: #00837F; /* Teal */
  font-weight: 600;
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
  border-bottom: 2px solid #D0D1D1; /* Gris muy claro */
  outline: none;
  font-size: 16px;
  background: transparent;
  color: #7E8080; /* Gris medio */
}

.input-box label {
  position: absolute;
  left: 0;
  top: 10px;
  pointer-events: none;
  transition: 0.3s ease all;
  color: #7E8080; /* Gris medio */
}

.input-box input:focus ~ label,
.input-box input:valid ~ label {
  top: -15px;
  font-size: 12px;
  color: #00837F; /* Teal */
}

/* Botón */
.btn {
  width: 100%;
  padding: 10px;
  border: none;
  background: #00837F; /* Teal */
  color: #fff;
  border-radius: 8px;
  font-size: 16px;
  cursor: pointer;
  transition: 0.3s;
  font-weight: 500;
}

.btn:hover {
  background: #AE874C; /* Oro viejo */
}

/* Link volver / switch */
.switch {
  margin-top: 15px;
}

.switch a {
  color: #00837F; /* Teal */
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
}

.switch a:hover {
  color: #AE874C; /* Oro viejo */
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
  font-weight: 500;
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

/* Tipos de alertas */
.alert.success { background: #00837F; } /* Teal */
.alert.error   { background: #AE874C; } /* Oro viejo */
.alert.info    { background: #7E8080; } /* Gris medio */

/* Animación */
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
      <form method="post" action="process_forgot.php" class="form">
        <h2>Recuperar contraseña</h2>
        <div class="input-box">
          <input type="email" name="email" required>
          <label>Correo institucional</label>
        </div>
        <button type="submit" class="btn">Enviar código</button>
        <p class="switch"><a href="login_register.php">⬅ Volver</a></p>
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
