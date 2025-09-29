<?php
// Incluimos header y conexión a la BD
include "../../../includes/header.php";
include '../../admin/recursos-aprendizaje/db_connect.php'; 

// 1. Validar que recibimos un ID
if (!isset($_GET['id_carrera']) || !is_numeric($_GET['id_carrera'])) {
    die("Error: No se especificó una carrera válida.");
}
$id_carrera = intval($_GET['id_carrera']);

// 2. Obtener el nombre de la carrera para el título
$stmt_cat = $conn->prepare("SELECT nombre FROM categorias WHERE id_categoria = ?");
$stmt_cat->bind_param("i", $id_carrera);
$stmt_cat->execute();
$resultado_cat = $stmt_cat->get_result();
if ($resultado_cat->num_rows === 0) {
    die("Error: La carrera seleccionada no existe.");
}
$carrera = $resultado_cat->fetch_assoc();
$nombre_carrera = $carrera['nombre'];
$stmt_cat->close();

// 3. Obtener los recursos de esa carrera
$stmt_rec = $conn->prepare("SELECT * FROM recursos WHERE id_categoria = ? ORDER BY fecha_creacion DESC");
$stmt_rec->bind_param("i", $id_carrera);
$stmt_rec->execute();
$resultado_recursos = $stmt_rec->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recursos de <?php echo htmlspecialchars($nombre_carrera); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .recurso-card:hover { transform: translateY(-5px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Recursos para: <?php echo htmlspecialchars($nombre_carrera); ?></h1>
            <a href="index.php" class="btn btn-outline-secondary">‹ Volver a Carreras</a>
        </div>

        <div class="row g-4">
            <?php if ($resultado_recursos->num_rows > 0): ?>
                <?php while ($recurso = $resultado_recursos->fetch_assoc()):
                    $icono = '';
                    $enlace_destino = '';
                    switch ($recurso['tipo']) {
                        case 'Curso':       $icono = '🎓'; $enlace_destino = htmlspecialchars($recurso['enlace']); break;
                        case 'Video':       $icono = '🎥'; $enlace_destino = htmlspecialchars($recurso['enlace']); break;
                        case 'Simulador':   $icono = '⚙️';  $enlace_destino = htmlspecialchars($recurso['enlace']); break;
                        case 'Libro':       $icono = '📚'; $enlace_destino = 'pdfs/' . htmlspecialchars($recurso['archivo_pdf']); break;
                        case 'Caso de Estudio': $icono = '📄'; $enlace_destino = 'pdfs/' . htmlspecialchars($recurso['archivo_pdf']); break;
                    }
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 recurso-card">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo $icono . ' ' . htmlspecialchars($recurso['titulo']); ?></h5>
                            <p class="card-text flex-grow-1"><?php echo htmlspecialchars($recurso['descripcion']); ?></p>
                            <a href="<?php echo $enlace_destino; ?>" target="_blank" class="btn btn-primary mt-auto">Acceder al Recurso</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <p class="mb-0">Aún no hay recursos disponibles para esta carrera. ¡Vuelve pronto!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
<?php 
$stmt_rec->close();
$conn->close();
include "../../../includes/footer.php"; 
?>
</html>