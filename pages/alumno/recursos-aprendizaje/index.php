<?php
// Leemos los datos de nuestro archivo JSON
$datos_json = file_get_contents('../../admin/recursos-aprendizaje/datos.json');
$datos = json_decode($datos_json, true);
$categorias = $datos['categorias'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos de Aprendizaje - UTPN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-carrera { text-decoration: none; color: inherit; display: block; height: 100%; transition: transform 0.2s, box-shadow 0.2s; }
        .card-carrera:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); color: inherit; }
        body { display: flex; flex-direction: column; min-height: 100vh; }
        main { flex-grow: 1; }
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
    <?php include "../../../includes/footer.php"; ?>
</body>
</html>