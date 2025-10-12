<?php
include('../../config/db.php');

$id = $_POST['id'] ?? null;

if (!$id) {
  echo json_encode(['success' => false, 'message' => 'ID no válido.']);
  exit;
}

try {
  // ✅ Eliminar al alumno directamente
  $delete = $conn->prepare("DELETE FROM users WHERE id = ?");
  $delete->execute([$id]);

  echo json_encode(['success' => true, 'message' => 'Alumno eliminado correctamente.']);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
