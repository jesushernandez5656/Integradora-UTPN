<?php
// Conexión a la base de datos
include "../../admin/recursos-aprendizaje/db_connect.php"; 

$query_categorias = "SELECT * FROM categorias ORDER BY nombre ASC";
$resultado_categorias = $conn->query($query_categorias);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos de Aprendizaje - UTPN</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="/integradora-UTPN/assets/css/main.css">   <link rel="stylesheet" href="/integradora-UTPN/assets/css/navbar.css"> <link rel="stylesheet" href="/integradora-UTPN/assets/css/footer.css"> <style>
        /* Estilos específicos para las tarjetas de esta página */
        .card-carrera {
            text-decoration: none; color: inherit; display: block; height: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-carrera:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body style="display: flex; flex-direction: column; min-height: 100vh; margin: 0;">

    <?php include "../../../includes/header.php"; ?>

    <main class="container mt-4" style="flex-grow: 1;">
        <div class="text-center mb-5">
            <h1>Recursos de Aprendizaje por Carrera</h1>
            <p class="lead">Explora cursos, libros, videos y más, organizados para tu programa educativo.</p>
        </div>

        <div class="row g-4 pb-5">
            <?php if ($resultado_categorias->num_rows > 0): ?>
                <?php while ($categoria = $resultado_categorias->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4 d-flex">
                        <a href="recursos.php?id_carrera=<?php echo $categoria['id_categoria']; ?>" class="card card-carrera w-100">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <h5 class="card-title text-center mb-0"><?php echo htmlspecialchars($categoria['nombre']); ?></h5>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-warning">No hay carreras disponibles en este momento.</div>
            <?php endif; ?>
        </div>
    </main>

    <?php 
    $conn->close();
    include "../../../includes/footer.php"; 
    ?>

</body>
</html>