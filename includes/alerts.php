<?php
// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$alerts = [];

// ✅ Mensajes desde sesión
if (!empty($_SESSION['success'])) { 
    $alerts[] = ['type' => 'success', 'msg' => $_SESSION['success']];
    unset($_SESSION['success']); 
}
if (!empty($_SESSION['error'])) { 
    $alerts[] = ['type' => 'error', 'msg' => $_SESSION['error']];
    unset($_SESSION['error']); 
}
if (!empty($_SESSION['info'])) { 
    $alerts[] = ['type' => 'info', 'msg' => $_SESSION['info']];
    unset($_SESSION['info']); 
}

// ✅ Mensaje adicional para ?registro=ok
if (isset($_GET['registro']) && $_GET['registro'] === 'ok') {
    $alerts[] = ['type' => 'success', 'msg' => '✅ Registro exitoso. Ahora puedes iniciar sesión.'];
}
?>

<?php if (!empty($alerts)): ?>
  <div class="alert-container">
    <?php foreach ($alerts as $a): ?>
      <div class="alert <?= $a['type'] ?>">
        <?= htmlspecialchars($a['msg'], ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
