<?php
$datos_json = file_get_contents('../../admin/recursos-aprendizaje/datos.json');
$datos = json_decode($datos_json, true) ?: ['categorias' => [], 'recursos' => []];
$categorias = $datos['categorias'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos de Aprendizaje - UTPN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="/integradora-UTPN/assets/css/navbar.css">
    <link rel="stylesheet" href="/integradora-UTPN/assets/css/footer.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">

    <style>
        /* NUEVO: Estilos del Body y Layout */
        body {
            /* Tus nuevos estilos */
            margin: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, "Helvetica Neue", Arial, sans-serif;
            color: var(--txt, #212529);
            background-color: #EDE5D6; /* 🎨 crema claro */

            /* Estilos de layout que estaban en el tag <body> */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex-grow: 1;
        }
    
        /* Estilos específicos de esta página (ya los tenías) */
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
    </style>
</head>
<body> <?php include "../../../includes/header.php"; ?>

    <main class="container mt-4" style="padding-bottom: 500px;">
        <div class="text-center mb-5">
            <h1>Recursos de Aprendizaje por Carrera</h1>
            <p class="lead">Explora cursos, libros, videos y más, organizados para tu programa educativo.</p>
        </div>
        <div class="row g-4 pb-5">
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $id_carrera => $nombre_carrera): ?>
                    <div class="col-md-6 col-lg-4 d-flex">
                        <a href="recursos.php?id_carrera=<?php echo $id_carrera; ?>" class="card card-carrera w-100" title="Ver recursos para <?php echo htmlspecialchars($nombre_carrera); ?>">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <h5 class="card-title text-center mb-0"><?php echo htmlspecialchars($nombre_carrera); ?></h5>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12"><div class="alert alert-warning text-center">No hay carreras disponibles.</div></div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php 
    // $conn->close(); // Esta línea ya no es necesaria
    include "../../../includes/footer.php"; 
    ?>
</body>
</html>