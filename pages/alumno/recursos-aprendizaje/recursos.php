<?php
// 1. Leemos los datos JSON
$datos_json = file_get_contents('../../admin/recursos-aprendizaje/datos.json');
$datos = json_decode($datos_json, true);
$categorias = $datos['categorias'];
$recursos_todos = $datos['recursos'];

// 2. Validamos el ID
if (!isset($_GET['id_carrera']) || !isset($categorias[$_GET['id_carrera']])) {
    die("Error: Carrera no válida.");
}
$id_carrera_actual = intval($_GET['id_carrera']);
$nombre_carrera = $categorias[$id_carrera_actual];

// 3. Filtramos los recursos
$recursos_filtrados = [];
foreach ($recursos_todos as $recurso) {
    if ($recurso['id_categoria'] == $id_carrera_actual) {
        $recursos_filtrados[] = $recurso;
    }
}
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
    
    <style>
        /* Estilos específicos de esta página */
        .recurso-card:hover { transform: translateY(-5px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .filtros-container { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 1.5rem; }
        .filtro-btn { border-radius: 20px; }
    </style>
</head>
<body> <?php include "../../../includes/header.php"; ?>
    
    <main class="container mt-4" style="padding-bottom: 500px;">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h1 class="mb-0 fs-2">Recursos: <?php echo htmlspecialchars($nombre_carrera); ?></h1>
            <a href="index.php" class="btn btn-outline-secondary">‹ Volver a Carreras</a>
        </div>

        <div class="filtros-container">
            <button type="button" class="btn btn-primary filtro-btn active" data-filtro="todo">Todo</button>
            <?php foreach ($tipos_disponibles as $tipo): ?>
                <button type="button" class="btn btn-outline-primary filtro-btn" data-filtro="<?php echo htmlspecialchars($tipo); ?>">
                    <?php echo htmlspecialchars($tipo); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="row g-4 pb-5" id="lista-recursos">
            <?php if (!empty($recursos_filtrados)): ?>
                <?php foreach ($recursos_filtrados as $recurso):
                    $icono = '🔗';
                    if ($recurso['tipo'] == 'Cursos') $icono = '🎓';
                    if ($recurso['tipo'] == 'Libros') $icono = '📚';
                    if ($recurso['tipo'] == 'Tutoriales') $icono = '🎥';
                    if ($recurso['tipo'] == 'Tesis') $icono = '📄';
                    if ($recurso['tipo'] == 'Articulos de investigacion') $icono = '🔬';
                    if ($recurso['tipo'] == 'pdf adjuntos') $icono = '📎';
                    if ($recurso['tipo'] == 'Simuladores') $icono = '⚙️';
                ?>
                <div class="col-md-6 col-lg-4 d-flex recurso-item" data-tipo="<?php echo htmlspecialchars($recurso['tipo']); ?>">
                    <div class="card h-100 recurso-card w-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo $icono . ' ' . htmlspecialchars($recurso['titulo']); ?></h5>
                            <p class="card-text flex-grow-1"><?php echo htmlspecialchars($recurso['descripcion']); ?></p>
                            <a href="<?php echo htmlspecialchars($recurso['enlace']); ?>" target="_blank" class="btn btn-primary mt-auto">Acceder al Recurso</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12"><div class="alert alert-info text-center"><p class="mb-0">Aún no hay recursos para esta carrera.</p></div></div>
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
                    botonesFiltro.forEach(btn => btn.classList.remove('btn-primary', 'active'));
                    botonesFiltro.forEach(btn => btn.classList.add('btn-outline-primary'));
                    this.classList.add('btn-primary', 'active');
                    this.classList.remove('btn-outline-primary');
                    const filtro = this.getAttribute('data-filtro');
                    recursos.forEach(function (recurso) {
                        if (filtro === 'todo' || recurso.getAttribute('data-tipo') === filtro) {
                            recurso.style.display = 'block';
                        } else {
                            recurso.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>