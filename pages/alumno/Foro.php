<?php
 include "../../includes/header.php";

// Inicializar datos de ejemplo

// Si no hay datos inicializados, crearlos
if (!isset($_SESSION['grupos'])) {
    $_SESSION['grupos'] = [
        [
            'id' => 1,
            'nombre' => 'Programadores PHP',
            'descripcion' => 'Grupo para compartir conocimientos sobre PHP',
            'creador' => 'admin',
            'miembros' => ['admin', 'usuario1']
        ],
        [
            'id' => 2,
            'nombre' => 'Amantes de la Música',
            'descripcion' => 'Comparte tu música favorita',
            'creador' => 'usuario1',
            'miembros' => ['usuario1', 'admin']
        ]
    ];
}

if (!isset($_SESSION['publicaciones'])) {
    $_SESSION['publicaciones'] = [
        [
            'id' => 1,
            'grupo_id' => 1,
            'autor' => 'admin',
            'contenido' => '¡Bienvenidos al grupo de Programadores PHP!',
            'fecha' => '2023-10-15 10:30:00'
        ],
        [
            'id' => 2,
            'grupo_id' => 1,
            'autor' => 'usuario1',
            'contenido' => '¿Alguien tiene algún consejo para optimizar consultas en MySQL?',
            'fecha' => '2023-10-16 14:22:00'
        ],
        [
            'id' => 3,
            'grupo_id' => 2,
            'autor' => 'usuario1',
            'contenido' => 'Escuché esta canción increíble hoy, ¡se las recomiendo!',
            'fecha' => '2023-10-17 09:15:00'
        ]
    ];
}

if (!isset($_SESSION['usuarios'])) {
    $_SESSION['usuarios'] = [
        'admin' => [
            'nombre' => 'Administrador',
            'rol' => 'admin'
        ],
        'usuario1' => [
            'nombre' => 'Juan Pérez',
            'rol' => 'usuario'
        ]
    ];
}

// Manejo de formularios
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear_grupo'])) {
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
    
    if (isset($_POST['publicar'])) {
        $nuevo_id = count($_SESSION['publicaciones']) + 1;
        $_SESSION['publicaciones'][] = [
            'id' => $nuevo_id,
            'grupo_id' => $_POST['grupo_id'],
            'autor' => 'usuario1',
            'contenido' => $_POST['contenido'],
            'fecha' => date('Y-m-d H:i:s')
        ];
        $mensaje = 'Publicación creada exitosamente';
    }
}

// Obtener grupo seleccionado
$grupo_seleccionado = null;
if (isset($_GET['grupo_id'])) {
    foreach ($_SESSION['grupos'] as $grupo) {
        if ($grupo['id'] == $_GET['grupo_id']) {
            $grupo_seleccionado = $grupo;
            break;
        }
    }
}

// Obtener publicaciones del grupo seleccionado
$publicaciones_grupo = [];
if ($grupo_seleccionado) {
    foreach ($_SESSION['publicaciones'] as $publicacion) {
        if ($publicacion['grupo_id'] == $grupo_seleccionado['id']) {
            $publicaciones_grupo[] = $publicacion;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sistema de Grupos Sociales</title>
    <style>
        /* Estilos existentes */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f0f2f5;
            color: #1c1e21;
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
            background-color: #365899;
        }
        
        /* Botón de recursos */
        .resources-button-container {
            display: flex;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(to right, #667eea, #764ba2);
            margin-bottom: 20px;
        }
        
        .resources-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            border: 2px solid white;
        }
        
        .resources-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        
        .resources-btn:active {
            transform: translateY(0);
        }
        
        .btn-icon {
            font-size: 18px;
        }
        
        .main-content {
            display: flex;
            margin-top: 20px;
            gap: 20px;
        }
        
        .sidebar {
            flex: 1;
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .content {
            flex: 3;
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .grupo-item {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .grupo-item:hover {
            background-color: #f0f2f5;
        }
        
        .grupo-item.active {
            background-color: #e7f3ff;
            border-left: 3px solid #4267B2;
        }
        
        .publicacion {
            border-bottom: 1px solid #e5e5e5;
            padding: 15px 0;
        }
        
        .publicacion:last-child {
            border-bottom: none;
        }
        
        .publicacion-autor {
            font-weight: bold;
            color: #4267B2;
        }
        
        .publicacion-fecha {
            color: #65676b;
            font-size: 14px;
        }
        
        .publicacion-contenido {
            margin-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #dddfe2;
            border-radius: 4px;
            font-size: 16px;
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        .btn {
            background-color: #4267B2;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #365899;
        }
        
        .mensaje {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #65676b;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }
        
        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
            }
            
            nav ul {
                margin-top: 15px;
                justify-content: center;
            }
            
            .resources-btn {
                padding: 12px 25px;
                font-size: 14px;
            }
        }

        /* Estilos footer (específicos para no afectar otros) */
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
            stroke: #AE874C; /* color dorado */
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
    
    <!-- Botón de Recursos - Colocado debajo del header -->
    <div class="resources-button-container">
        <a href="ChatBot.php" class="resources-btn">
            <span class="btn-icon">📚</span>
            Ayuda Psicológica
        </a>
    </div>

    <div class="container">
        <?php if ($mensaje): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <div class="main-content">
            <div class="sidebar">
                <h2>Grupos</h2>
                <?php foreach ($_SESSION['grupos'] as $grupo): ?>
                    <div class="grupo-item <?php echo ($grupo_seleccionado && $grupo_seleccionado['id'] == $grupo['id']) ? 'active' : ''; ?>"
                         onclick="window.location.href='?grupo_id=<?php echo $grupo['id']; ?>'">
                        <h3><?php echo htmlspecialchars($grupo['nombre']); ?></h3>
                        <p><?php echo htmlspecialchars($grupo['descripcion']); ?></p>
                        <small>Miembros: <?php echo count($grupo['miembros']); ?></small>
                    </div>
                <?php endforeach; ?>
                
                <div style="margin-top: 20px;">
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
                        <button type="submit" name="crear_grupo" class="btn">Crear Grupo</button>
                    </form>
                </div>
            </div>
            
            <div class="content">
                <?php if ($grupo_seleccionado): ?>
                    <h2><?php echo htmlspecialchars($grupo_seleccionado['nombre']); ?></h2>
                    <p><?php echo htmlspecialchars($grupo_seleccionado['descripcion']); ?></p>
                    
                    <div style="margin: 20px 0;">
                        <h3>Publicar en este grupo</h3>
                        <form method="post">
                            <input type="hidden" name="grupo_id" value="<?php echo $grupo_seleccionado['id']; ?>">
                            <div class="form-group">
                                <textarea name="contenido" class="form-control" placeholder="¿Qué quieres compartir?" required></textarea>
                            </div>
                            <button type="submit" name="publicar" class="btn">Publicar</button>
                        </form>
                    </div>
                    
                    <h3>Publicaciones</h3>
                    <?php if (count($publicaciones_grupo) > 0): ?>
                        <?php foreach ($publicaciones_grupo as $publicacion): ?>
                            <div class="publicacion">
                                <div class="publicacion-header">
                                    <div class="publicacion-autor"><?php echo htmlspecialchars($publicacion['autor']); ?></div>
                                    <div class="publicacion-fecha"><?php echo $publicacion['fecha']; ?></div>
                                </div>
                                <div class="publicacion-contenido">
                                    <?php echo nl2br(htmlspecialchars($publicacion['contenido'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i>📝</i>
                            <p>No hay publicaciones en este grupo todavía.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i>👥</i>
                        <h2>Bienvenido al Sistema de Grupos</h2>
                        <p>Selecciona un grupo de la barra lateral para ver sus publicaciones o crea uno nuevo.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- FOOTER INTEGRADO -->
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
                <!-- Cambia la ruta si tu logo está en otro lugar -->
            </a>
        </div>

        <p class="final_text">@2025 IRCM41 | Reservados todos los derechos.</p>
    </footer>
</body>
</html>