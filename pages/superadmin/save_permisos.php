<?php
// pages/superadmin/save_permisos.php
header('Content-Type: application/json; charset=utf-8');
include('../../config/db.php');
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'superadmin') {
  echo json_encode(['success' => false, 'message' => 'No autorizado']);
  exit;
}

// Leer JSON
$input = json_decode(file_get_contents('php://input'), true);
$admin_id = isset($input['admin_id']) ? (int)$input['admin_id'] : 0;
$permisos = $input['permisos'] ?? [];

if (!$admin_id) {
  echo json_encode(['success' => false, 'message' => 'ID inválido']);
  exit;
}

try {
  $up = $conn->prepare("UPDATE admin_permissions SET allowed = ? WHERE admin_id = ? AND page = ?");
  foreach ($permisos as $p) {
    // $p ['page'] y ['allowed']
    $up->execute([(int)$p['allowed'], $admin_id, $p['page']]);
  }
  echo json_encode(['success' => true, 'message' => 'Permisos guardados']);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
