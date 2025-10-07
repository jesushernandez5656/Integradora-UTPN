<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "admin") {
    header("Location: ../../login_register.php");
    exit;
}

include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Recursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 1rem 0;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">UTPN - Panel de Administración</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Hola, <?= htmlspecialchars($_SESSION["user_name"]) ?>
                </span>
                <a class="btn btn-outline-light btn-sm" href="../../logout.php">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Administrar Recursos de Aprendizaje</h1>
        
        <!-- Mostrar mensajes de éxito o error -->
        <?php
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'success') {
                echo '<div class="alert alert-success">Recurso agregado exitosamente.</div>';
            } elseif ($_GET['status'] == 'deleted') {
                echo '<div class="alert alert-success">Recurso eliminado exitosamente.</div>';
            }
        }
        ?>

        <!-- Formulario para agregar recursos -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Agregar Nuevo Recurso</h5>
            </div>
            <div class="card-body">
                <form action="gestionar-recu.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="titulo" class="form-label">Título del Recurso</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_categoria" class="form-label">Carrera</label>
                            <select class="form-select" id="id_categoria" name="id_categoria" required>
                                <option value="">Selecciona una carrera</option>
                                <?php
                                $sql = "SELECT id_categoria, nombre FROM categorias ORDER BY nombre";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo "<option value='".$row['id_categoria']."'>".$row['nombre']."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="form-label">Tipo de Recurso</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Selecciona un tipo</option>
                                <option value="Curso">Curso</option>
                                <option value="Caso de Estudio">Caso de Estudio</option>
                                <option value="Simulador">Simulador</option>
                                <option value="Video">Video</option>
                                <option value="Libro">Libro</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="enlace" class="form-label">Enlace (URL)</label>
                            <input type="url" class="form-control" id="enlace" name="enlace" placeholder="https://...">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="archivo_pdf" class="form-label">Archivo PDF (opcional)</label>
                        <input type="file" class="form-control" id="archivo_pdf" name="archivo_pdf" accept=".pdf">
                        <div class="form-text">Solo se permiten archivos PDF. Tamaño máximo: 10MB</div>
                    </div>
                    
                    <button type="submit" class="btn btn-success" name="guardar">Agregar Recurso</button>
                </form>
            </div>
        </div>
        
        <!-- Tabla de recursos existentes -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Recursos Existentes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Carrera</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT r.id_recurso, r.titulo, r.tipo, c.nombre as carrera 
                                    FROM recursos r 
                                    INNER JOIN categorias c ON r.id_categoria = c.id_categoria 
                                    ORDER BY c.nombre, r.titulo";
                            $result = $conn->query($sql);
                            
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>".htmlspecialchars($row['titulo'])."</td>
                                        <td>".htmlspecialchars($row['carrera'])."</td>
                                        <td>".htmlspecialchars($row['tipo'])."</td>
                                        <td>
                                            <a href='gestionar-recu.php?eliminar=".$row['id_recurso']."' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que quieres eliminar este recurso?\")'>Eliminar</a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>No hay recursos registrados</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2024 Universidad UTPN. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>Departamento de Recursos Educativos</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>