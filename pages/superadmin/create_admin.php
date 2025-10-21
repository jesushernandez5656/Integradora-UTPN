<?php
// pages/superadmin/create_admin.php
header('Content-Type: application/json; charset=utf-8');
include('../../config/db.php');
include('../../config/admin_pages.php'); // <-- la lista de páginas
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'superadmin') {
  echo json_encode(['success' => false, 'message' => 'No autorizado']);
  exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$name || !$email || !$password) {
  echo json_encode(['success' => false, 'message' => 'Faltan datos']);
  exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(['success' => false, 'message' => 'Correo inválido']);
  exit;
}

try {
  // Revisar si ya existe
  $check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
  $check->execute([$email]);
  if ($check->fetch()) {
    echo json_encode(['success' => false, 'message' => 'El correo ya está registrado']);
    exit;
  }

  // Insertar admin
  $hash = password_hash($password, PASSWORD_DEFAULT);
  $insert = $conn->prepare("INSERT INTO users (name, email, password, user_type, verified, created_at) VALUES (?, ?, ?, 'admin', 1, NOW())");
  $insert->execute([$name, $email, $hash]);
  $admin_id = $conn->lastInsertId();

  // Inicializar permisos (allowed = 0 por defecto)
  $insPerm = $conn->prepare("INSERT INTO admin_permissions (admin_id, page, allowed) VALUES (?, ?, 0)");
  foreach ($ADMIN_PAGES as $key => $label) {
    $insPerm->execute([$admin_id, $key]);
  }

  echo json_encode(['success' => true, 'message' => 'Administrador creado y permisos inicializados']);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
