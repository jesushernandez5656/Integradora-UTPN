<?php
include "../../../includes/header.php";
include '../../admin/recursos-aprendizaje/db_connect.php'; 

$query_categorias = "SELECT * FROM categorias ORDER BY nombre ASC";
$resultado_categorias = $conn->query($query_categorias);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recursos de Aprendizaje</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        /* --- NUEVO CÓDIGO CSS --- */
        html, body {
            height: 100%; /* Asegura que el HTML y el Body ocupen toda la altura */
        }
        body {
            display: flex; /* Convierte el body en un contenedor flexible */
            flex-direction: column; /* Apila los elementos (header, contenido, footer) verticalmente */
        }
        .content-wrapper {
            flex-grow: 1; /* Esta es la clave: hace que el contenido principal crezca y empuje el footer hacia abajo */
        }
        /* --- FIN NUEVO CÓDIGO CSS --- */

        .card-carrera {
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            display: block;
        }
        .card-carrera:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <div class="container mt-4 content-wrapper">
        <div class="text-center mb-5">
            <h1>Recursos de Aprendizaje por Carrera</h1>
            <p class="lead">Explora cursos, libros, videos y más, organizados para tu programa educativo.</p>
        </div>

        <div class="row g-4">
            <?php if ($resultado_categorias->num_rows > 0): ?>
                <?php while ($categoria = $resultado_categorias->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4">
                        <a href="recursos.php?id_carrera=<?php echo $categoria['id_categoria']; ?>" class="card-carrera">
                            <div class="card h-100">
                                <div class="card-body d-flex align-items-center justify-content-center">
                                    <h5 class="card-title text-center mb-0"><?php echo htmlspecialchars($categoria['nombre']); ?></h5>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-warning">No hay carreras disponibles en este momento.</div>
            <?php endif; ?>
        </div>
    </div> </body>
<?php 
$conn->close();
// El footer ahora queda fuera del div principal, empujado hacia abajo
include "../../../includes/footer.php"; 
?>
</html>