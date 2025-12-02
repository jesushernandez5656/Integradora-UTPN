<?php
// Lee los datos del archivo JSON
$datos_json = file_get_contents('datos.json');
$datos = json_decode($datos_json, true) ?: ['categorias' => [], 'recursos' => []];
$categorias = $datos['categorias'] ?? [];
$recursos = $datos['recursos'] ?? [];
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
            margin: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, "Helvetica Neue", Arial, sans-serif;
            color: var(--txt, #212529); 
            background-color: #EDE5D6; 
            
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex-grow: 1; 
        }
        section.border, .card {
            background-color: #FFFFFF;
        }
    </style>
</head>
<body> 
    <?php include "../../../includes/header.php"; ?>

    <main class="container mt-4" style="padding-bottom: 500px;"> 
    
        <h1 class="mb-4">Panel de Administración de Recursos</h1>

        <section class="mb-5 p-4 border rounded shadow-sm">
            <div class="row g-3 align-items-center mb-3">
                <div class="col-12 col-md">
                    <h2 class="h4 mb-0">Gestionar Carreras</h2>
                </div>
                <div class="col-12 col-md-auto">
                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalCarreraAgregar">
                        Agregar Carrera
                    </button>
                </div>
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
            
            <div class="row g-3 align-items-center mb-3">
                <div class="col-12 col-md-auto">
                    <h2 class="h4 mb-0">Gestionar Recursos</h2>
                </div>
                
                <div class="col-12 col-md">
                    <select class="form-select form-select-sm" id="filtroCarreraAdmin">
                        <option value="todos">Mostrar Todas las Carreras</option>
                        <?php if (!empty($categorias)): ?>
                            <?php foreach ($categorias as $id => $nombre): ?>
                                <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($nombre); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-12 col-md-auto">
                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalRecursoAgregar">
                        Agregar Recurso
                    </button>
                </div>
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

    </main> 
    <div class="modal fade" id="modalCarreraAgregar" tabindex="-1">
        <div class="modal-dialog"><div class="modal-content">
            <form action="gestionar_datos.php" method="POST">
                <div class="modal-header"><h5 class="modal-title">Agregar Carrera</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_carrera" class="form-label">Nombre de la Carrera</label>
                        <input type="text" class="form-control" id="nombre_carrera" name="nombre_carrera" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" name="accion" value="agregar_carrera" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div></div>
    </div>

    <div class="modal fade" id="modalRecursoAgregar" tabindex="-1">
        <div class="modal-dialog"><div class="modal-content">
            <form action="gestionar_datos.php" method="POST">
                <div class="modal-header"><h5 class="modal-title">Agregar Recurso</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label for="add_titulo" class="form-label">Título</label><input type="text" class="form-control" id="add_titulo" name="titulo" required></div>
                    <div class="mb-3"><label for="add_descripcion" class="form-label">Descripción</label><textarea class="form-control" id="add_descripcion" name="descripcion" rows="3"></textarea></div>
                    <div class="mb-3">
                        <label for="add_id_categoria" class="form-label">Carrera</label>
                        <select class="form-select" id="add_id_categoria" name="id_categoria" required>
                            <option value="">Selecciona una carrera...</option>
                            <?php foreach ($categorias as $id => $nombre): ?><option value="<?php echo $id; ?>"><?php echo htmlspecialchars($nombre); ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_tipo" class="form-label">Tipo de Recurso</label>
                        <select class="form-select" id="add_tipo" name="tipo" required>
                            <option value="">Selecciona un tipo...</option>
                            <option value="Tesis">Tesis</option><option value="Articulos de investigacion">Artículo de Investigación</option><option value="Cursos">Cursos</option><option value="Libros">Libros</option><option value="Tutoriales">Tutoriales</option><option value="pdf adjuntos">PDF Adjunto</option><option value="Simuladores">Simuladores</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_enlace" class="form-label">Enlace</label>
                        <input type="url" class="form-control" id="add_enlace" name="enlace" placeholder="https://..." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" name="accion" value="agregar_recurso" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div></div>
    </div>

    <div class="modal fade" id="modalRecursoEditar" tabindex="-1">
        <div class="modal-dialog"><div class="modal-content">
            <form action="gestionar_datos.php" method="POST">
                <input type="hidden" id="edit_id_recurso" name="id_recurso">
                <div class="modal-header"><h5 class="modal-title">Editar Recurso</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label for="edit_titulo" class="form-label">Título</label><input type="text" class="form-control" id="edit_titulo" name="titulo" required></div>
                    <div class="mb-3"><label for="edit_descripcion" class="form-label">Descripción</label><textarea class="form-control" id="edit_descripcion" name="descripcion" rows="3"></textarea></div>
                    <div class="mb-3">
                        <label for="edit_id_categoria" class="form-label">Carrera</label>
                        <select class="form-select" id="edit_id_categoria" name="id_categoria" required>
                            <?php foreach ($categorias as $id => $nombre): ?><option value="<?php echo $id; ?>"><?php echo htmlspecialchars($nombre); ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tipo" class="form-label">Tipo de Recurso</label>
                        <select class="form-select" id="edit_tipo" name="tipo" required>
                            <option value="Tesis">Tesis</option><option value="Articulos de investigacion">Artículo de Investigación</option><option value="Cursos">Cursos</option><option value="Libros">Libros</option><option value="Tutoriales">Tutoriales</option><option value="pdf adjuntos">PDF Adjunto</option><option value="Simuladores">Simuladores</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_enlace" class="form-label">Enlace</label>
                        <input type="url" class="form-control" id="edit_enlace" name="enlace" placeholder="https://..." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" name="accion" value="editar_recurso" class="btn btn-primary">Actualizar Cambios</button>
                </div>
            </form>
        </div></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- Script Modal Editar ---
            try {
                const modalEditar = document.getElementById('modalRecursoEditar');
                if (modalEditar) { 
                    modalEditar.addEventListener('show.bs.modal', function (event) {
                        const button = event.relatedTarget;
                        if (button) {
                            const id = button.getAttribute('data-id');
                            const titulo = button.getAttribute('data-titulo');
                            const desc = button.getAttribute('data-desc');
                            const catId = button.getAttribute('data-cat-id');
                            const tipo = button.getAttribute('data-tipo');
                            const enlace = button.getAttribute('data-enlace');
                            
                            modalEditar.querySelector('#edit_id_recurso').value = id || '';
                            modalEditar.querySelector('#edit_titulo').value = titulo || '';
                            modalEditar.querySelector('#edit_descripcion').value = desc || '';
                            modalEditar.querySelector('#edit_id_categoria').value = catId || '';
                            modalEditar.querySelector('#edit_tipo').value = tipo || '';
                            modalEditar.querySelector('#edit_enlace').value = enlace || '';
                        }
                    });
                }
            } catch (error) { console.error(error); }

            // --- Script Filtro Carreras ---
            try {
                const filtroSelect = document.getElementById('filtroCarreraAdmin');
                const contenedorTarjetas = document.getElementById('lista-recursos-admin'); 

                if (filtroSelect && contenedorTarjetas) {
                    const todasLasTarjetas = contenedorTarjetas.querySelectorAll('.recurso-card-admin');
                    filtroSelect.addEventListener('change', function () {
                        const idCarreraSeleccionada = this.value;
                        todasLasTarjetas.forEach(function (tarjeta) {
                            const idCarreraTarjeta = tarjeta.getAttribute('data-id-carrera');
                            if (idCarreraSeleccionada === 'todos' || idCarreraSeleccionada === idCarreraTarjeta) {
                                tarjeta.style.display = ''; 
                            } else {
                                tarjeta.style.display = 'none'; 
                            }
                        });
                    });
                }
            } catch (error) { console.error(error); }
        });
    </script>

    <?php 
    // Incluye el footer
    include "../../../includes/footer.php"; 
    ?>
</body>
</html>