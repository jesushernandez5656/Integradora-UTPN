<?php
// La conexión ahora está en la misma carpeta
include 'db_connect.php'; 

if (isset($_POST['guardar'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $id_categoria = $_POST['id_categoria'];
    $tipo = $_POST['tipo'];
    $enlace = $_POST['enlace'] ?? null;
    $archivo_pdf_nombre = null;

    if (in_array($tipo, ['Libro', 'Caso de Estudio']) && !empty($_FILES['archivo_pdf']['name'])) {
        // RUTA CORREGIDA: Subir dos niveles y luego bajar a la carpeta del alumno
        $directorio_subida = '../../alumno/recursos-aprendizaje/pdfs/'; 
        
        if (!is_dir($directorio_subida)) {
            mkdir($directorio_subida, 0777, true);
        }
        $nombre_unico = uniqid() . '-' . basename($_FILES['archivo_pdf']['name']);
        $ruta_archivo = $directorio_subida . $nombre_unico;

        if (move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], $ruta_archivo)) {
            $archivo_pdf_nombre = $nombre_unico;
        } else {
            die("Error al subir el archivo PDF.");
        }
    }

    $stmt = $conn->prepare("INSERT INTO recursos (id_categoria, titulo, descripcion, tipo, enlace, archivo_pdf) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $id_categoria, $titulo, $descripcion, $tipo, $enlace, $archivo_pdf_nombre);
    
    if ($stmt->execute()) {
        // REDIRECCIÓN CORREGIDA: a home_admin.php
        header('Location: home_admin.php?status=success'); 
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

if (isset($_GET['eliminar'])) {
    $id_recurso = $_GET['eliminar'];

    $stmt_select = $conn->prepare("SELECT archivo_pdf FROM recursos WHERE id_recurso = ?");
    $stmt_select->bind_param("i", $id_recurso);
    $stmt_select->execute();
    $recurso = $stmt_select->get_result()->fetch_assoc();

    if ($recurso && !empty($recurso['archivo_pdf'])) {
        // RUTA CORREGIDA: para encontrar el PDF a borrar
        $ruta_pdf = '../../alumno/recursos-aprendizaje/pdfs/' . $recurso['archivo_pdf']; 
        if (file_exists($ruta_pdf)) {
            unlink($ruta_pdf);
        }
    }
    $stmt_select->close();
    
    $stmt_delete = $conn->prepare("DELETE FROM recursos WHERE id_recurso = ?");
    $stmt_delete->bind_param("i", $id_recurso);
    if ($stmt_delete->execute()) {
        // REDIRECCIÓN CORREGIDA: a home_admin.php
        header('Location: home_admin.php?status=deleted'); 
    } else {
        echo "Error al eliminar: " . $stmt_delete->error;
    }
    $stmt_delete->close();
}

$conn->close();
?>