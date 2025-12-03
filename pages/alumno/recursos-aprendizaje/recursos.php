<?php
// 1. Leemos los datos JSON
$datos_json = file_get_contents('../../admin/recursos-aprendizaje/datos.json');
$datos = json_decode($datos_json, true) ?: ['categorias' => [], 'recursos' => []];
$categorias = $datos['categorias'] ?? [];
$recursos_todos = $datos['recursos'] ?? [];

// 2. Validamos el ID
if (!isset($_GET['id_carrera']) || !isset($categorias[$_GET['id_carrera']])) {
    die("Error: Carrera no válida o no especificada.");
}

$id_carrera_actual = intval($_GET['id_carrera']);
$nombre_carrera = $categorias[$id_carrera_actual];

// 3. Filtramos los recursos para esta carrera
$recursos_filtrados = [];
foreach ($recursos_todos as $recurso) {
    if ($recurso['id_categoria'] == $id_carrera_actual) {
        $recursos_filtrados[] = $recurso;
    }
}

// 4. Generamos los botones de filtro
$tipos_disponibles = array_unique(array_column($recursos_filtrados, 'tipo'));
sort($tipos_disponibles);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos de <?php echo htmlspecialchars($nombre_carrera); ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/integradora-UTPN/assets/css/navbar.css">
    <link rel="stylesheet" href="/integradora-UTPN/assets/css/footer.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
        main { flex-grow: 1; }

        .recurso-card {
            transition: transform 0.2s, box-shadow 0.2s;
            background-color: #FFFFFF;
            border: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .recurso-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 8px 15px rgba(0,0,0,0.1); 
        }
        
        .filtros-container { 
            display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 2rem; 
        }
        .filtro-btn { 
            border-radius: 50px; padding-left: 1.5rem; padding-right: 1.5rem; font-weight: 500;
        }
    </style>
</head>
<body>

    <?php include "../../../includes/header.php"; ?>
    
    <main class="container mt-4" style="padding-bottom: 500px;">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 border-bottom pb-3 border-secondary">
            <h1 class="h2 mb-3 mb-md-0">Recursos: <?php echo htmlspecialchars($nombre_carrera); ?></h1>
            <a href="index.php" class="btn btn-outline-dark btn-sm">‹ Volver a Carreras</a>
        </div>

        <div class="filtros-container">
            <button type="button" class="btn btn-primary filtro-btn active" data-filtro="todo">Todo</button>
            <?php foreach ($tipos_disponibles as $tipo): ?>
                <button type="button" class="btn btn-outline-primary filtro-btn" data-filtro="<?php echo htmlspecialchars($tipo); ?>">
                    <?php echo htmlspecialchars($tipo); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="row g-4" id="lista-recursos">
            <?php if (!empty($recursos_filtrados)): ?>
                <?php foreach ($recursos_filtrados as $recurso):
                    $icono = '🔗';
                    $tipo = $recurso['tipo'];
                    // Lógica simple de iconos
                    if (stripos($tipo, 'Cursos') !== false) $icono = '🎓';
                    elseif (stripos($tipo, 'Libros') !== false) $icono = '📚';
                    elseif (stripos($tipo, 'Tutoriales') !== false) $icono = '🎥';
                    elseif (stripos($tipo, 'Tesis') !== false) $icono = '📄';
                    elseif (stripos($tipo, 'Articulos') !== false) $icono = '🔬';
                    elseif (stripos($tipo, 'pdf') !== false) $icono = '📎';
                    elseif (stripos($tipo, 'Simuladores') !== false) $icono = '⚙️';
                ?>
                
                <div class="col-md-6 col-lg-4 d-flex recurso-item" data-tipo="<?php echo htmlspecialchars($recurso['tipo']); ?>">
                    <div class="card h-100 recurso-card w-100">
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill">
                                    <?php echo htmlspecialchars($recurso['tipo']); ?>
                                </span>
                            </div>
                            <h5 class="card-title text-primary"><?php echo $icono . ' ' . htmlspecialchars($recurso['titulo']); ?></h5>
                            <p class="card-text flex-grow-1 text-muted small mt-2"><?php echo htmlspecialchars($recurso['descripcion']); ?></p>
                            <a href="<?php echo htmlspecialchars($recurso['enlace']); ?>" target="_blank" class="btn btn-primary mt-3 w-100 rounded-pill">Acceder al Recurso</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center shadow-sm">
                        <h4 class="alert-heading">¡Ups!</h4>
                        <p class="mb-0">Aún no hay recursos disponibles para esta carrera.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include "../../../includes/footer.php"; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const botonesFiltro = document.querySelectorAll('.filtro-btn');
            const recursos = document.querySelectorAll('.recurso-item');

            botonesFiltro.forEach(function (boton) {
                boton.addEventListener('click', function () {
                    // 1. Estilos de botones (Azul vs Blanco)
                    botonesFiltro.forEach(btn => {
                        btn.classList.remove('btn-primary', 'active');
                        btn.classList.add('btn-outline-primary');
                    });
                    this.classList.remove('btn-outline-primary');
                    this.classList.add('btn-primary', 'active');

                    // 2. Lógica de Filtrado (CORREGIDA)
                    const filtroSeleccionado = this.getAttribute('data-filtro');

                    recursos.forEach(function (recurso) {
                        const tipoRecurso = recurso.getAttribute('data-tipo');
                        
                        if (filtroSeleccionado === 'todo' || tipoRecurso === filtroSeleccionado) {
                            // MOSTRAR: Quitamos cualquier display inline para que regrese a d-flex (Bootstrap)
                            recurso.style.removeProperty('display');
                            
                            // Pequeña animación opcional
                            recurso.style.opacity = '0';
                            setTimeout(() => recurso.style.opacity = '1', 50);
                        } else {
                            // OCULTAR: Usamos 'important' para ganarle a Bootstrap
                            recurso.style.setProperty('display', 'none', 'important');
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>