<?php
include "../../../includes/header.php";
include '../../admin/recursos-aprendizaje/db_connect.php'; 

if (!isset($_GET['id_carrera']) || !is_numeric($_GET['id_carrera'])) {
    die("Error: No se especificó una carrera válida.");
}
$id_carrera = intval($_GET['id_carrera']);

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

$stmt_rec = $conn->prepare("SELECT * FROM recursos WHERE id_categoria = ? ORDER BY tipo, titulo ASC");
$stmt_rec->bind_param("i", $id_carrera);
$stmt_rec->execute();
$resultado_recursos = $stmt_rec->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos de <?php echo htmlspecialchars($nombre_carrera); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .recurso-card { 
            transition: transform 0.2s, box-shadow 0.2s; 
            height: 100%;
        }
        .recurso-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
        }
        body { 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
            margin: 0; 
        }
        main { 
            flex-grow: 1; 
        }
    </style>
</head>
<body>

    <main class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h1 class="mb-0 fs-2">Recursos: <?php echo htmlspecialchars($nombre_carrera); ?></h1>
            <a href="index.php" class="btn btn-outline-secondary">‹ Volver a Carreras</a>
        </div>

        <div class="row g-4 pb-5">
            <?php if ($resultado_recursos->num_rows > 0): ?>
                <?php while ($recurso = $resultado_recursos->fetch_assoc()):
                    $icono = '';
                    $enlace_destino = '#';
                    $target = '_blank';
                    $texto_boton = 'Acceder al Recurso';
                    $disabled = '';
                    
                    // Determinar ícono según el tipo
                    switch ($recurso['tipo']) {
                        case 'Curso':       $icono = '🎓'; break;
                        case 'Video':       $icono = '🎥'; break;
                        case 'Simulador':   $icono = '⚙️'; break;
                        case 'Libro':       $icono = '📚'; break;
                        case 'Caso de Estudio': $icono = '📄'; break;
                        default:            $icono = '📁';
                    }
                    
                    // Determinar enlace según el tipo de recurso
                    if (!empty($recurso['enlace'])) {
                        // Recurso con enlace externo
                        $enlace_destino = htmlspecialchars($recurso['enlace']);
                        $target = '_blank';
                    } elseif (!empty($recurso['archivo_pdf'])) {
                        // Recurso con archivo PDF
                        $ruta_pdf = 'pdfs/' . $recurso['archivo_pdf'];
                        // Verificar si el archivo existe
                        if (file_exists($ruta_pdf)) {
                            $enlace_destino = $ruta_pdf;
                            $target = '_blank';
                        } else {
                            $enlace_destino = '#';
                            $texto_boton = 'PDF no disponible';
                            $disabled = 'disabled';
                        }
                    } else {
                        // Recurso sin enlace ni archivo
                        $enlace_destino = '#';
                        $texto_boton = 'Recurso no disponible';
                        $disabled = 'disabled';
                    }
                ?>
                <div class="col-md-6 col-lg-4 d-flex">
                    <div class="card h-100 recurso-card w-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <span style="font-size: 1.5rem;"><?php echo $icono; ?></span>
                                <span class="badge bg-secondary ms-2"><?php echo htmlspecialchars($recurso['tipo']); ?></span>
                            </div>
                            <h5 class="card-title"><?php echo htmlspecialchars($recurso['titulo']); ?></h5>
                            <p class="card-text flex-grow-1"><?php echo htmlspecialchars($recurso['descripcion']); ?></p>
                            <div class="mt-auto">
                                <a href="<?php echo $enlace_destino; ?>" 
                                   target="<?php echo $target; ?>" 
                                   class="btn btn-primary w-100 <?php echo $disabled; ?>">
                                   <?php echo $texto_boton; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <p class="mb-0">Aún no hay recursos disponibles para esta carrera. ¡Vuelve pronto!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php 
    $stmt_rec->close();
    $conn->close();
    include "../../../includes/footer.php"; 
    ?>
</body>
</html>