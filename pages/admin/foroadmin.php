<?php
// 1. INICIAR LA SESIÓN. Debe ser la primera línea ejecutada.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Incluir el encabezado.
include "../../includes/header.php";

// 3. Si no hay datos inicializados, redirigir al archivo que los inicializa (Foro.php o el index principal).
// He asumido que deben redirigir a Foro.php o index.php, ajústalo según tu estructura de carpetas.
if (!isset($_SESSION['grupos']) || !isset($_SESSION['publicaciones'])) {
    // Ajusta esta ruta de redirección si 'Foro.php' o 'index.php' están en otro lugar relativo.
    header('Location: ../alumno/Foro.php'); 
    exit;
}

// Manejo de formularios de administración
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar_publicacion'])) {
        foreach ($_SESSION['publicaciones'] as $key => $publicacion) {
            if ($publicacion['id'] == $_POST['publicacion_id']) {
                unset($_SESSION['publicaciones'][$key]);
                $_SESSION['publicaciones'] = array_values($_SESSION['publicaciones']);
                $mensaje = 'Publicación eliminada';
                break;
            }
        }
    }
    
    if (isset($_POST['eliminar_grupo'])) {
        foreach ($_SESSION['grupos'] as $key => $grupo) {
            if ($grupo['id'] == $_POST['grupo_id']) {
                unset($_SESSION['grupos'][$key]);
                $_SESSION['grupos'] = array_values($_SESSION['grupos']);
                
                // Eliminar también las publicaciones del grupo
                foreach ($_SESSION['publicaciones'] as $pkey => $publicacion) {
                    if ($publicacion['grupo_id'] == $_POST['grupo_id']) {
                        unset($_SESSION['publicaciones'][$pkey]);
                    }
                }
                $_SESSION['publicaciones'] = array_values($_SESSION['publicaciones']);
                
                $mensaje = 'Grupo eliminado';
                break;
            }
        }
    }
    
    if (isset($_POST['crear_grupo_admin'])) {
        $nuevo_id = count($_SESSION['grupos']) + 1;
        $_SESSION['grupos'][] = [
            'id' => $nuevo_id,
            'nombre' => $_POST['nombre_grupo'],
            'descripcion' => $_POST['descripcion_grupo'],
            'creador' => 'admin',
            'miembros' => ['admin']
        ];
        $mensaje = 'Grupo creado exitosamente';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Panel de Administración - Sistema de Grupos</title>
    <style>
        /* Estilos existentes */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 20px;
        }
        
        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        nav ul li a:hover {
            background-color: #495057;
        }
        
        .admin-panel {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        
        .admin-section {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .admin-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .admin-section h2 {
            color: #495057;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #495057;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 16px;
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #0069d9;
        }
        
        .btn-danger {
            background-color: #dc3545;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        .btn-success {
            background-color: #28a745;
        }
        
        .btn-success:hover {
            background-color: #218838;
        }
        
        .mensaje {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .tabla {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .tabla th, .tabla td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        .tabla th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            nav ul {
                margin-top: 15px;
                justify-content: center;
            }
            
            .tabla {
                display: block;
                overflow-x: auto;
            }
        }

        /* Footer Styles */
        footer#final {
            background-color: #008080; /* teal */
            text-align: center;
            color: white;
            padding: 40px 20px 30px 20px;
            position: relative;
            margin-top: 40px;
        }

        footer#final .contacto h1 {
            font-weight: bold;
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        footer#final .contacto p {
            font-size: 1.2rem;
            margin: 0 0 30px 0;
        }

        ul.social_icon {
            list-style: none;
            padding: 0;
            margin: 0 0 30px 0;
            display: flex;
            justify-content: center;
            gap: 50px;
        }

        ul.social_icon li a svg {
            stroke: #AE874C; /* dorado */
            width: 50px;
            height: 50px;
            transition: stroke 0.3s ease;
            vertical-align: middle;
        }

        ul.social_icon li a:hover svg {
            stroke: #FFD700; /* dorado brillante on hover */
        }

        .logo_footer img {
            width: 120px; /* tamaño del logo */
            margin-bottom: 20px;
        }

        footer#final p.final_text {
            font-size: 1rem;
            color: white;
            margin-top: 0;
        }
    </style>
</head>
<body>

    
    <div class="container">
        <?php if ($mensaje): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <div class="admin-panel">
            <h1>Panel de Administración</h1>
            <p>Gestiona grupos y publicaciones del sistema</p>
            
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($_SESSION['grupos']); ?></div>
                    <div class="stat-label">Grupos Totales</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($_SESSION['publicaciones']); ?></div>
                    <div class="stat-label">Publicaciones Totales</div>
                </div>
                <div class="stat-card">
                    <?php
                    $total_miembros = 0;
                    foreach ($_SESSION['grupos'] as $grupo) {
                        $total_miembros += count($grupo['miembros']);
                    }
                    ?>
                    <div class="stat-number"><?php echo $total_miembros; ?></div>
                    <div class="stat-label">Total de Miembros</div>
                </div>
            </div>
            
            <div class="admin-section">
                <h2>Gestión de Grupos</h2>
                
                <div style="margin-bottom: 20px;">
                    <h3>Crear Nuevo Grupo</h3>
                    <form method="post">
                        <div class="form-group">
                            <label for="nombre_grupo">Nombre del Grupo</label>
                            <input type="text" id="nombre_grupo" name="nombre_grupo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion_grupo">Descripción</label>
                            <textarea id="descripcion_grupo" name="descripcion_grupo" class="form-control" required></textarea>
                        </div>
                        <button type="submit" name="crear_grupo_admin" class="btn btn-success">Crear Grupo</button>
                    </form>
                </div>
                
                <?php if (count($_SESSION['grupos']) > 0): ?>
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Creador</th>
                                <th>Miembros</th>
                                <th>Publicaciones</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['grupos'] as $grupo): 
                                $publicaciones_grupo = 0;
                                foreach ($_SESSION['publicaciones'] as $publicacion) {
                                    if ($publicacion['grupo_id'] == $grupo['id']) {
                                        $publicaciones_grupo++;
                                    }
                                }
                            ?>
                                <tr>
                                    <td><?php echo $grupo['id']; ?></td>
                                    <td><?php echo htmlspecialchars($grupo['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($grupo['descripcion']); ?></td>
                                    <td><?php echo htmlspecialchars($grupo['creador']); ?></td>
                                    <td><?php echo count($grupo['miembros']); ?></td>
                                    <td><?php echo $publicaciones_grupo; ?></td>
                                    <td>
                                        <form method="post" style="display: inline;">
                                            <input type="hidden" name="grupo_id" value="<?php echo $grupo['id']; ?>">
                                            <button type="submit" name="eliminar_grupo" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este grupo y todas sus publicaciones?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <i>👥</i>
                        <p>No hay grupos creados.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="admin-section">
                <h2>Gestión de Publicaciones</h2>
                
                <?php if (count($_SESSION['publicaciones']) > 0): ?>
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Grupo</th>
                                <th>Autor</th>
                                <th>Contenido</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['publicaciones'] as $publicacion): 
                                $nombre_grupo = '';
                                foreach ($_SESSION['grupos'] as $grupo) {
                                    if ($grupo['id'] == $publicacion['grupo_id']) {
                                        $nombre_grupo = $grupo['nombre'];
                                        break;
                                    }
                                }
                            ?>
                                <tr>
                                    <td><?php echo $publicacion['id']; ?></td>
                                    <td><?php echo htmlspecialchars($nombre_grupo); ?></td>
                                    <td><?php echo htmlspecialchars($publicacion['autor']); ?></td>
                                    <td><?php echo substr(htmlspecialchars($publicacion['contenido']), 0, 100); ?><?php echo strlen($publicacion['contenido']) > 100 ? '...' : ''; ?></td>
                                    <td><?php echo $publicacion['fecha']; ?></td>
                                    <td>
                                        <form method="post" style="display: inline;">
                                            <input type="hidden" name="publicacion_id" value="<?php echo $publicacion['id']; ?>">
                                            <button type="submit" name="eliminar_publicacion" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta publicación?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <i>📝</i>
                        <p>No hay publicaciones.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer id="final">
        <div class="contacto">
            <h1>Contáctanos</h1>
            <p>656-221-5597</p>
        </div>

        <ul class="social_icon">
            <li>
                <a href="https://www.facebook.com/doitfitneess" target="_blank" aria-label="Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#AE874C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                    </svg>
                </a>
            </li>
            <li>
                <a href="https://www.instagram.com/doitfitnessjrz/" target="_blank" aria-label="Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#AE874C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                    </svg>
                </a>
            </li>
            <li>
                <a href="https://goo.gl/maps/wMgmVw5xVX8qPRHG7" target="_blank" aria-label="Ubicación">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#AE874C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"/>
                        <path d="M15 5.764v15"/>
                        <path d="M9 3.236v15"/>
                    </svg>
                </a>
            </li>
        </ul>

        <div class="logo_footer">
            <a href="/">
                <img src="/integradora-UTPN/assets/img/Logo.png" alt="Logo Universidad" />
            </a>
        </div>

        <p class="final_text">@2025 IRCM41 | Reservados todos los derechos.</p>
    </footer>

</body>
</html>