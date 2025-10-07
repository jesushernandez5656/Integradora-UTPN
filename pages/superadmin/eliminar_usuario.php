<?php
include('../../config/db.php');

$id = $_POST['id'] ?? null;

if (!$id) {
  echo json_encode(['success' => false, 'message' => 'ID no válido.']);
  exit;
}

try {
  // 🔹 Eliminar el usuario
  $delete = $conn->prepare("DELETE FROM users WHERE id = ?");
  $delete->execute([$id]);

  // 🔹 Reordenar IDs para mantenerlos consecutivos
  $conn->exec("SET @count = 0");
  $conn->exec("UPDATE users SET id = (@count := @count + 1) ORDER BY id");
  $conn->exec("ALTER TABLE users AUTO_INCREMENT = 1");

  echo json_encode(['success' => true]);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
