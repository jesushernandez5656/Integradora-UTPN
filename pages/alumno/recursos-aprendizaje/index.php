<?php
// Usamos la misma conexión de la carpeta admin
include '../../admin/db_connect.php';

// Consulta para obtener todas las categorías (carreras) que tienen al menos un recurso
$query_categorias = "SELECT c.* FROM categorias c JOIN recursos r ON c.id_categoria = r.id_categoria GROUP BY c.id_categoria ORDER BY c.nombre ASC";
$resultado_categorias = $conn->query($query_categorias);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recursos de Aprendizaje</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .recurso-card { transition: transform 0.2s; }
        .recurso-card:hover { transform: translateY(-5px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="text-center mb-4">
            <h1>Recursos de Aprendizaje por Carrera</h1>
            <p class="lead">Explora cursos, libros, videos y más, organizados para tu programa educativo.</p>
        </div>

        <?php while ($categoria = $resultado_categorias->fetch_assoc()): ?>
            <section class="mb-5">
                <h2 class="border-bottom pb-2 mb-3"><?php echo htmlspecialchars($categoria['nombre']); ?></h2>

                <div class="row g-4">
                    <?php
                    // Consulta para obtener los recursos de esta categoría
                    $id_cat = $categoria['id_categoria'];
                    $stmt = $conn->prepare("SELECT * FROM recursos WHERE id_categoria = ? ORDER BY fecha_creacion DESC");
                    $stmt->bind_param("i", $id_cat);
                    $stmt->execute();
                    $resultado_recursos = $stmt->get_result();

                    if ($resultado_recursos->num_rows > 0):
                        while ($recurso = $resultado_recursos->fetch_assoc()):
                            // Determinar el icono y el enlace según el tipo de recurso
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
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $icono . ' ' . htmlspecialchars($recurso['titulo']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($recurso['descripcion']); ?></p>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <a href="<?php echo $enlace_destino; ?>" target="_blank" class="btn btn-primary w-100">Acceder al Recurso</a>
                            </div>
                        </div>
                    </div>
                    <?php
                        endwhile;
                    else:
                        echo "<p>No hay recursos disponibles para esta carrera por el momento.</p>";
                    endif;
                    $stmt->close();
                    ?>
                </div>
            </section>
        <?php endwhile; $conn->close(); ?>
    </div>
</body>
</html>