<?php
header('Content-Type: application/json; charset=utf-8');
include('../../config/db.php');
session_start();

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
  // Revisamos si el correo ya existe
  $check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
  $check->execute([$email]);
  if ($check->fetch()) {
    echo json_encode(['success' => false, 'message' => 'El correo ya está registrado']);
    exit;
  }

  // Hash de la contraseña
  $hash = password_hash($password, PASSWORD_DEFAULT);

  // Insertar nuevo administrador en la misma tabla `users`
  $insert = $conn->prepare("INSERT INTO users (name, email, password, user_type, verified, created_at) VALUES (?, ?, ?, 'admin', 1, NOW())");
  $insert->execute([$name, $email, $hash]);

  echo json_encode(['success' => true]);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => 'Admin agregado con exito']);
}
