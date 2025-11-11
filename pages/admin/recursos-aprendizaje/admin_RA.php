<?php
// Lee los datos del archivo JSON
$datos_json = file_get_contents('datos.json');
// Decodifica el JSON a un array PHP, manejando posible error de archivo vacío o inválido
$datos = json_decode($datos_json, true) ?: ['categorias' => [], 'recursos' => []]; // Si falla, usa arrays vacíos
$categorias = $datos['categorias'] ?? []; // Usa ?? para evitar error si 'categorias' no existe
$recursos = $datos['recursos'] ?? [];   // Usa ?? para evitar error si 'recursos' no existe
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="/integradora-UTPN/assets/css/navbar.css">
    <link rel="stylesheet" href="/integradora-UTPN/assets/css/footer.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            /* Tus nuevos estilos */
            margin: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, "Helvetica Neue", Arial, sans-serif;
            color: var(--txt, #212529); /* Color de fallback por si --txt no está definido */
            background-color: #EDE5D6; /* 🎨 crema claro */
            
            /* Estilos de layout necesarios */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex-grow: 1; /* Asegura que el contenido principal crezca */
        }
        /* Añadido para que las secciones tengan contraste con el fondo crema */
        section.border {
            background-color: #FFFFFF;
        }
    </style>
</head>
<body> <?php 
    // Incluye el header
    include "../../../includes/header.php"; 
    ?>

    <main class="container mt-4" style="flex-grow: 1; padding-bottom: 500px;"> 
    
        <h1 class="mb-4">Panel de Administración de Recursos</h1>

        <section class="mb-5 p-4 border rounded shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4">Gestionar Carreras</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCarreraAgregar">
                    Agregar Carrera
                </button>
            </div>
            <ul class="list-group">
                <?php if (!empty($categorias)): ?>
                    <?php foreach ($categorias as $id => $nombre): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($nombre); ?>
                            <div>
                                <form action="gestionar_datos.php" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que quieres eliminar esta carrera? Se borrarán TODOS sus recursos.');">
                                    <input type="hidden" name="id_categoria" value="<?php echo $id; ?>">
                                    <button type="submit" name="accion" value="eliminar_carrera" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item">No hay carreras agregadas.</li>
                <?php endif; ?>
            </ul>
        </section>

        <section class="p-4 border rounded shadow-sm">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-3">
                    <h2 class="h4 mb-0">Gestionar Recursos</h2>
                    <select class="form-select form-select-sm" id="filtroCarreraAdmin" style="width: auto;">
                        <option value="todos">Mostrar Todas las Carreras</option>
                        <?php if (!empty($categorias)): ?>
                            <?php foreach ($categorias as $id => $nombre): ?>
                                <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($nombre); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRecursoAgregar">
                    Agregar Recurso
                </button>
            </div>

            <div class="row g-3" id="lista-recursos-admin">
                <?php if (!empty($recursos)): ?>
                    <?php foreach ($recursos as $recurso): ?>
                        <?php if (isset($categorias[$recurso['id_categoria']])): ?>
                            <div class="col-md-6 col-lg-4 recurso-card-admin" data-id-carrera="<?php echo $recurso['id_categoria']; ?>">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($recurso['titulo']); ?></h5>
                                        <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($recurso['tipo']); ?></h6>
                                        <p class="card-text"><small><?php echo htmlspecialchars($recurso['descripcion']); ?></small></p>
                                        <p class="card-text"><strong>Carrera:</strong> <?php echo htmlspecialchars($categorias[$recurso['id_categoria']]); ?></p>
                                        <p class="card-text" style="word-break: break-all;"><strong>Enlace:</strong> <a href="<?php echo htmlspecialchars($recurso['enlace']); ?>" target="_blank">Ver enlace</a></p>
                                    </div>
                                    <div class="card-footer bg-white border-top-0 pt-0">
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalRecursoEditar" 
                                            data-id="<?php echo $recurso['id_recurso']; ?>"
                                            data-titulo="<?php echo htmlspecialchars($recurso['titulo']); ?>"
                                            data-desc="<?php echo htmlspecialchars($recurso['descripcion']); ?>"
                                            data-cat-id="<?php echo $recurso['id_categoria']; ?>"
                                            data-tipo="<?php echo htmlspecialchars($recurso['tipo']); ?>"
                                            data-enlace="<?php echo htmlspecialchars($recurso['enlace']); ?>">
                                            Editar
                                        </button>
                                        <form action="gestionar_datos.php" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que quieres eliminar este recurso?');">
                                            <input type="hidden" name="id_recurso" value="<?php echo $recurso['id_recurso']; ?>">
                                            <button type="submit" name="accion" value="eliminar_recurso" class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                     <div class="col-12"><div class="alert alert-info">No hay recursos agregados todavía.</div></div>
                <?php endif; ?>
            </div>
        </section>

        <div class="modal fade" id="modalCarreraAgregar" tabindex="-1">...</div>
        <div class="modal fade" id="modalRecursoAgregar" tabindex="-1">...</div>
        <div class="modal fade" id="modalRecursoEditar" tabindex="-1">...</div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Espera a que el DOM esté completamente cargado
            document.addEventListener('DOMContentLoaded', function () {
                // ... (Tu script de modal y filtro va aquí) ...
            });
        </script>

    </main> <?php 
    // Incluye el footer (Debe ser solo un fragmento HTML)
    include "../../../includes/footer.php"; 
    ?>
</body>
</html>