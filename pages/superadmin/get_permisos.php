<?php
// pages/superadmin/get_permisos.php
header('Content-Type: application/json; charset=utf-8');
include('../../config/db.php');
include('../../config/admin_pages.php');
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'superadmin') {
  echo json_encode(['success' => false, 'message' => 'No autorizado']);
  exit;
}

$admin_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$admin_id) {
  echo json_encode(['success' => false, 'message' => 'ID inválido']);
  exit;
}

try {
  // Asegurar que existan filas para todas las pages
  $checkStmt = $conn->prepare("SELECT id FROM admin_permissions WHERE admin_id = ? AND page = ? LIMIT 1");
  $insertStmt = $conn->prepare("INSERT INTO admin_permissions (admin_id, page, allowed) VALUES (?, ?, 0)");
  foreach ($ADMIN_PAGES as $key => $label) {
    $checkStmt->execute([$admin_id, $key]);
    if (!$checkStmt->fetch()) {
      $insertStmt->execute([$admin_id, $key]);
    }
  }

  // Obtener permisos
  $stmt = $conn->prepare("SELECT page, allowed FROM admin_permissions WHERE admin_id = ?");
  $stmt->execute([$admin_id]);
  $permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode(['success' => true, 'permisos' => $permisos, 'pages' => $ADMIN_PAGES]);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
