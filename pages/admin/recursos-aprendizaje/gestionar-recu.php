<?php
include 'db_connect.php';

// --- AGREGAR RECURSO ---
if (isset($_POST['guardar'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $id_categoria = $_POST['id_categoria'];
    $tipo = $_POST['tipo'];
    $enlace = $_POST['enlace'] ?? null;
    $archivo_pdf_nombre = null;

    // Validar que se provea enlace o archivo según el tipo
    if (in_array($tipo, ['Libro', 'Caso de Estudio']) && empty($_FILES['archivo_pdf']['name'])) {
        die("Error: Para Libros o Casos de Estudio, debes subir un archivo PDF.");
    }
    if (in_array($tipo, ['Curso', 'Video', 'Simulador']) && empty($enlace)) {
        die("Error: Para Cursos, Videos o Simuladores, debes proporcionar un enlace.");
    }

    // Procesar subida de PDF
    if (isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] == 0) {
        // El directorio donde se guardarán los PDFs, relativo a la carpeta del ALUMNO
        $directorio_subida = '../alumno/pdfs/';
        // Crear el directorio si no existe
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

    // Insertar en la base de datos usando sentencias preparadas para seguridad
    $stmt = $conn->prepare("INSERT INTO recursos (id_categoria, titulo, descripcion, tipo, enlace, archivo_pdf) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $id_categoria, $titulo, $descripcion, $tipo, $enlace, $archivo_pdf_nombre);
    
    if ($stmt->execute()) {
        header('Location: index.php?status=success');
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// --- ELIMINAR RECURSO ---
if (isset($_GET['eliminar'])) {
    $id_recurso = $_GET['eliminar'];

    // Primero, obtener el nombre del archivo PDF para borrarlo del servidor
    $stmt_select = $conn->prepare("SELECT archivo_pdf FROM recursos WHERE id_recurso = ?");
    $stmt_select->bind_param("i", $id_recurso);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $recurso = $result->fetch_assoc();

    if ($recurso && !empty($recurso['archivo_pdf'])) {
        $ruta_pdf = '../alumno/pdfs/' . $recurso['archivo_pdf'];
        if (file_exists($ruta_pdf)) {
            unlink($ruta_pdf); // Borrar el archivo
        }
    }
    $stmt_select->close();
    
    // Ahora, eliminar el registro de la base de datos
    $stmt_delete = $conn->prepare("DELETE FROM recursos WHERE id_recurso = ?");
    $stmt_delete->bind_param("i", $id_recurso);
    if ($stmt_delete->execute()) {
        header('Location: index.php?status=deleted');
    } else {
        echo "Error al eliminar: " . $stmt_delete->error;
    }
    $stmt_delete->close();
}

$conn->close();
?>