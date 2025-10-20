<?php
include('../../config/db.php');
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'superadmin') {
  echo json_encode(['success' => false, 'message' => 'No autorizado']);
  exit;
}

$id = $_POST['id'] ?? null;
$rol = $_POST['rol'] ?? null;

if (!$id || !$rol) {
  echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
  exit;
}

if (!in_array($rol, ['user', 'admin'])) {
  echo json_encode(['success' => false, 'message' => 'Rol inválido']);
  exit;
}

try {
  $stmt = $conn->prepare("UPDATE users SET user_type = :rol WHERE id = :id");
  $stmt->execute([':rol' => $rol, ':id' => $id]);
  echo json_encode(['success' => true, 'message' => 'Rol actualizado correctamente']);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()]);
}
