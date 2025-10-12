<?php
include('../../config/db.php');
session_start();

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Falta el ID.']);
    exit;
}

$id = intval($_GET['id']);

try {
    // ✅ Eliminar permisos asociados al admin
    $deletePerms = $conn->prepare("DELETE FROM admin_permissions WHERE admin_id = :id");
    $deletePerms->bindParam(':id', $id);
    $deletePerms->execute();

    // ✅ Eliminar al admin
    $deleteAdmin = $conn->prepare("DELETE FROM users WHERE id = :id AND user_type = 'admin'");
    $deleteAdmin->bindParam(':id', $id);
    $deleteAdmin->execute();

    echo json_encode(['success' => true, 'message' => 'Administrador eliminado correctamente.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
