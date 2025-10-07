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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-carrera {
            text-decoration: none; 
            color: inherit; 
            display: block; 
            height: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-carrera:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            color: inherit;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex-grow: 1;
        }
    </style>
</head>
<body>

    <?php include "../../../includes/header.php"; ?>

    <main class="container mt-4" role="main">
        <div class="text-center mb-5">
            <h1>Recursos de Aprendizaje por Carrera</h1>
            <p class="lead">Explora cursos, libros, videos y más, organizados para tu programa educativo.</p>
        </div>

        <div class="row g-4 pb-5">
            <?php if ($resultado_categorias->num_rows > 0): ?>
                <?php while ($categoria = $resultado_categorias->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4 d-flex">
                        <a href="recursos.php?id_carrera=<?php echo $categoria['id_categoria']; ?>" 
                           class="card card-carrera w-100" 
                           title="Ver recursos para <?php echo htmlspecialchars($categoria['nombre']); ?>">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <h5 class="card-title text-center mb-0"><?php echo htmlspecialchars($categoria['nombre']); ?></h5>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">No hay carreras disponibles en este momento.</div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php 
    $conn->close();
    include "../../../includes/footer.php"; 
    ?>

</body>
</html>