<?php
include "../../includes/header.php";

// Ruta del archivo JSON
$json_file = '../../assets/js/becas.json';

// Cargar datos del JSON
$data = [];
if (file_exists($json_file)) {
    $json_data = file_get_contents($json_file);
    $data = json_decode($json_data, true);
}

// Determinar qué página editar
$pagina_activa = isset($_GET['pagina']) ? $_GET['pagina'] : 'pagina_becas_universitarias';
$pagina = $data[$pagina_activa] ?? [];

// Si hay error cargando el JSON, usar datos por defecto
if (!$data || !$pagina) {
    $data = [
        "pagina_becas_universitarias" => [
            "titulo_pagina" => "Becas Universitarias | Impulsa tu camino",
            "hero" => [
                "chip_texto" => "Convocatorias limitadas",
                "titulo_principal" => "Consigue tu <span class=\"grad\">beca</span><br> de <span class=\"grad alt\">Acceso a la Universidad</span>",
                "descripcion" => "Solo disponible para estudiantes sin registro.",
                "insignias" => [
                    ["texto" => "Beca disponible", "clase" => "ok"],
                    ["texto" => "Acceso restringido", "clase" => "warn"]
                ],
                "tarjeta_ejemplo" => [
                    "titulo" => "Beca Acceso a la Universidad",
                    "descripcion" => "Primer paso hacia tu futuro académico.",
                    "metadata" => ["🇲🇽 México", "Nuevo ingreso"],
                    "enlace_texto" => "Solicitar",
                    "enlace_url" => "https://www.juarez.gob.mx/becas-de-acceso-a-la-universidad"
                ]
            ],
            "seccion_becas" => [
                "titulo" => "Becas disponibles",
                "subtitulo" => "Inicia sesión para desbloquear todas las becas.",
                "becas" => []
            ],
            "seccion_asesorias" => [
                "titulo_seccion" => "Asesorías",
                "caracteristicas" => []
            ],
            "chatbot" => [
                "nombre" => "UTPN-BOT",
                "mensaje_bienvenida" => "¡Hola! 👋 Selecciona una opción escribiendo el número:",
                "opciones_iniciales" => [],
                "respuestas_pregrabadas" => []
            ]
        ]
    ];
    $pagina = $data[$pagina_activa];
}

// Obtener lista de PDFs disponibles
$pdf_folder = '../../assets/PDF/';
$pdf_files = [];
if (is_dir($pdf_folder)) {
    $files = scandir($pdf_folder);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
            $pdf_files[] = $file;
        }
    }
}

// Procesar guardado de datos
$success = "";
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['section'])) {
        $section = $_POST['section'];
        
        try {
            switch($section) {
                case 'general':
                    $data[$pagina_activa]['titulo_pagina'] = $_POST['titulo_pagina'];
                    break;
                    
                case 'hero':
                    $data[$pagina_activa]['hero']['chip_texto'] = $_POST['chip_texto'];
                    
                    // Procesar título principal
                    $titulo_texto = $_POST['titulo_principal'];
                    $titulo_html = $titulo_texto;
                    
                    if ($pagina_activa === 'pagina_becas_universitarias') {
                        $titulo_html = preg_replace('/(^|\s)(beca)($|\s)/', '$1<span class="grad">$2</span>$3', $titulo_html);
                        $titulo_html = preg_replace('/(^|\s)(Acceso a la Universidad)($|\s)/', '$1<span class="grad alt">$2</span>$3', $titulo_html);
                    } else {
                        $titulo_html = preg_replace('/(^|\s)(beca)($|\s)/', '$1<span class="grad">$2</span>$3', $titulo_html);
                        $titulo_html = preg_replace('/(^|\s)(información)($|\s)/', '$1<span class="grad alt">$2</span>$3', $titulo_html);
                    }
                    
                    $titulo_html = str_replace("\n", '<br>', $titulo_html);
                    $data[$pagina_activa]['hero']['titulo_principal'] = $titulo_html;
                    
                    $data[$pagina_activa]['hero']['descripcion'] = $_POST['descripcion'];
                    
                    // Procesar insignias
                    $data[$pagina_activa]['hero']['insignias'] = [];
                    if (isset($_POST['insignia_texto'])) {
                        foreach ($_POST['insignia_texto'] as $index => $texto) {
                            if (!empty($texto)) {
                                $data[$pagina_activa]['hero']['insignias'][] = [
                                    'texto' => $texto,
                                    'clase' => $_POST['insignia_clase'][$index] ?? ''
                                ];
                            }
                        }
                    }
                    
                    // Procesar tarjetas
                    if ($pagina_activa === 'pagina_becas_universitarias') {
                        if (isset($_POST['tarjeta_titulo'][0]) && !empty($_POST['tarjeta_titulo'][0])) {
                            $metadata = isset($_POST['tarjeta_metadata'][0]) ? 
                                array_filter(array_map('trim', explode(',', $_POST['tarjeta_metadata'][0]))) : [];
                            
                            $data[$pagina_activa]['hero']['tarjeta_ejemplo'] = [
                                'titulo' => $_POST['tarjeta_titulo'][0],
                                'descripcion' => $_POST['tarjeta_descripcion'][0] ?? '',
                                'metadata' => $metadata,
                                'enlace_texto' => $_POST['tarjeta_enlace_texto'][0] ?? '',
                                'enlace_url' => $_POST['tarjeta_enlace_url'][0] ?? ''
                            ];
                        }
                    }
                    break;
                    
                case 'becas_destacadas':
                    if ($pagina_activa === 'pagina_becas_universitarias') {
                        $data[$pagina_activa]['seccion_becas']['titulo'] = $_POST['becas_titulo'];
                        $data[$pagina_activa]['seccion_becas']['subtitulo'] = $_POST['becas_subtitulo'];
                        
                        $data[$pagina_activa]['seccion_becas']['becas'] = [];
                        if (isset($_POST['beca_nombre'])) {
                            foreach ($_POST['beca_nombre'] as $index => $nombre) {
                                if (!empty($nombre)) {
                                    $requisitos = isset($_POST['beca_requisitos'][$index]) ? 
                                        array_filter(array_map('trim', explode(',', $_POST['beca_requisitos'][$index]))) : [];
                                    
                                    $enlace_requisitos = $_POST['beca_enlace_requisitos'][$index] ?? '';
                                    if (!empty($enlace_requisitos) && !str_contains($enlace_requisitos, '://')) {
                                        $enlace_requisitos = '../../assets/PDF/' . $enlace_requisitos;
                                    }
                                    
                                    $beca_data = [
                                        'id' => $_POST['beca_id'][$index] ?? time() + $index,
                                        'nombre' => $nombre,
                                        'monto' => $_POST['beca_monto'][$index] ?? '',
                                        'resumen' => $_POST['beca_resumen'][$index] ?? '',
                                        'requisitos' => $requisitos,
                                        'bloqueada' => isset($_POST['beca_bloqueada'][$index]) ? true : false
                                    ];
                                    
                                    if (!$beca_data['bloqueada']) {
                                        $beca_data['enlace_postular'] = $_POST['beca_enlace_postular'][$index] ?? '';
                                        $beca_data['enlace_descarga_requisitos'] = $enlace_requisitos;
                                    }
                                    
                                    $data[$pagina_activa]['seccion_becas']['becas'][] = $beca_data;
                                }
                            }
                        }
                    }
                    break;
                    
                case 'asesorias':
                    $data[$pagina_activa]['seccion_asesorias']['titulo_seccion'] = $_POST['asesorias_titulo'];
                    
                    $data[$pagina_activa]['seccion_asesorias']['caracteristicas'] = [];
                    if (isset($_POST['caracteristica_titulo'])) {
                        foreach ($_POST['caracteristica_titulo'] as $index => $titulo) {
                            if (!empty($titulo)) {
                                $data[$pagina_activa]['seccion_asesorias']['caracteristicas'][] = [
                                    'titulo' => $titulo,
                                    'descripcion' => $_POST['caracteristica_descripcion'][$index] ?? ''
                                ];
                            }
                        }
                    }
                    break;
                    
                case 'chatbot':
                    $data[$pagina_activa]['chatbot']['nombre'] = $_POST['chatbot_nombre'];
                    
                    if ($pagina_activa === 'pagina_becas_universitarias') {
                        $data[$pagina_activa]['chatbot']['mensaje_bienvenida'] = $_POST['chatbot_mensaje_bienvenida'] ?? '';
                    }
                    
                    $data[$pagina_activa]['chatbot']['opciones_iniciales'] = [];
                    if (isset($_POST['opcion_texto'])) {
                        foreach ($_POST['opcion_texto'] as $texto) {
                            if (!empty($texto)) {
                                $data[$pagina_activa]['chatbot']['opciones_iniciales'][] = $texto;
                            }
                        }
                    }
                    
                    $data[$pagina_activa]['chatbot']['respuestas_pregrabadas'] = [];
                    if (isset($_POST['respuesta_clave'])) {
                        foreach ($_POST['respuesta_clave'] as $index => $clave) {
                            if (!empty($clave)) {
                                $data[$pagina_activa]['chatbot']['respuestas_pregrabadas'][$clave] = $_POST['respuesta_texto'][$index] ?? '';
                            }
                        }
                    }
                    
                    if (!isset($data[$pagina_activa]['chatbot']['respuestas_pregrabadas']['default'])) {
                        $data[$pagina_activa]['chatbot']['respuestas_pregrabadas']['default'] = '🤖 No entendí tu pregunta. Por favor, reformula tu pregunta. 😊';
                    }
                    break;
            }
            
            // Guardar en el archivo JSON
            if (file_put_contents($json_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                $success = "¡Cambios guardados correctamente!";
                // Recargar los datos actualizados
                $json_data = file_get_contents($json_file);
                $data = json_decode($json_data, true);
                $pagina = $data[$pagina_activa];
            } else {
                $error = "Error: No se pudo guardar el archivo JSON. Verifica los permisos.";
            }
        } catch (Exception $e) {
            $error = "Error al procesar los datos: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Becas Universitarias</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --light: #f8f9fa;
            --dark: #212529;
            --bg: #f1CBA5;
            --bg-2: #c79c4dff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fb;
            color: #333;
            line-height: 1.6;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, var(--bg-2), var(--bg));
            color: white;
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            border-left: 4px solid white;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-header {
            padding: 15px 20px;
            background: var(--primary);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-body {
            padding: 20px;
        }

        /* Forms */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.2);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* Dynamic List */
        .dynamic-list {
            margin-bottom: 15px;
        }

        .list-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        /* Alerts */
        .alert {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Tabs */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .grad {
            background: linear-gradient(90deg, #AE874C, #AE874C);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: bold;
        }

        .grad.alt {
            filter: saturate(140%);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .sidebar-menu {
                display: flex;
                overflow-x: auto;
            }
            
            .list-item {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-cogs"></i> Panel Admin</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="#" class="active" data-tab="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="#" data-tab="general"><i class="fas fa-cog"></i> General</a></li>
                <li><a href="#" data-tab="hero"><i class="fas fa-home"></i> Sección Hero</a></li>
                <li><a href="#" data-tab="becas"><i class="fas fa-graduation-cap"></i> Becas</a></li>
                <li><a href="#" data-tab="asesorias"><i class="fas fa-hands-helping"></i> Asesorías</a></li>
                <li><a href="#" data-tab="chatbot"><i class="fas fa-robot"></i> Chatbot</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Panel de Administración - Becas Universitarias</h1>
            </div>

            <!-- Alertas -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Dashboard -->
            <div id="dashboard" class="tab-content active">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-tachometer-alt"></i> Resumen del Sistema</h3>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                            <div class="card">
                                <div class="card-body">
                                    <h4>Becas Disponibles</h4>
                                    <p><?php echo count($pagina['seccion_becas']['becas']); ?> becas registradas</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4>Características de Asesorías</h4>
                                    <p><?php echo count($pagina['seccion_asesorias']['caracteristicas']); ?> características</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4>Opciones del Chatbot</h4>
                                    <p><?php echo count($pagina['chatbot']['opciones_iniciales']); ?> opciones iniciales</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-rocket"></i> Acciones Rápidas</h3>
                    </div>
                    <div class="card-body">
                        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                            <button class="btn btn-primary" data-tab="general">
                                <i class="fas fa-cog"></i> Configuración General
                            </button>
                            <button class="btn btn-success" data-tab="hero">
                                <i class="fas fa-edit"></i> Editar Hero
                            </button>
                            <button class="btn btn-warning" data-tab="becas">
                                <i class="fas fa-plus"></i> Gestionar Becas
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuración General -->
            <div id="general" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-cog"></i> Configuración General</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="section" value="general">
                            <div class="form-group">
                                <label for="titulo_pagina">Título de la Página</label>
                                <input type="text" id="titulo_pagina" name="titulo_pagina" class="form-control" 
                                       value="<?php echo htmlspecialchars($pagina['titulo_pagina']); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sección Hero -->
            <div id="hero" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-home"></i> Sección Hero</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="section" value="hero">
                            
                            <div class="form-group">
                                <label for="chip_texto">Texto del Chip</label>
                                <input type="text" id="chip_texto" name="chip_texto" class="form-control" 
                                       value="<?php echo htmlspecialchars($pagina['hero']['chip_texto']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="titulo_principal">Título Principal</label>
                                <textarea id="titulo_principal" name="titulo_principal" class="form-control" rows="3"><?php 
                                    $titulo = $pagina['hero']['titulo_principal'];
                                    $titulo = preg_replace('/<span class="grad">(.*?)<\/span>/', '$1', $titulo);
                                    $titulo = preg_replace('/<span class="grad alt">(.*?)<\/span>/', '$1', $titulo);
                                    $titulo = str_replace('<br>', "\n", $titulo);
                                    echo htmlspecialchars($titulo);
                                ?></textarea>
                                <small>Usa "beca" y "Acceso a la Universidad" para aplicar estilos especiales</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea id="descripcion" name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($pagina['hero']['descripcion']); ?></textarea>
                            </div>
                            
                            <h4>Insignias</h4>
                            <div id="insignias-list" class="dynamic-list">
                                <?php foreach ($pagina['hero']['insignias'] as $index => $insignia): ?>
                                <div class="list-item">
                                    <input type="text" name="insignia_texto[]" class="form-control" placeholder="Texto de la insignia" 
                                           value="<?php echo htmlspecialchars($insignia['texto']); ?>">
                                    <select name="insignia_clase[]" class="form-control">
                                        <option value="" <?php echo $insignia['clase'] === '' ? 'selected' : ''; ?>>Normal</option>
                                        <option value="ok" <?php echo $insignia['clase'] === 'ok' ? 'selected' : ''; ?>>Éxito</option>
                                        <option value="warn" <?php echo $insignia['clase'] === 'warn' ? 'selected' : ''; ?>>Advertencia</option>
                                    </select>
                                    <button type="button" class="btn btn-danger btn-sm remove-item">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="add-insignia">
                                <i class="fas fa-plus"></i> Agregar Insignia
                            </button>
                            
                            <h4 style="margin-top: 20px;">Tarjeta de Ejemplo</h4>
                            <div id="tarjetas-list">
                                <?php $tarjeta = $pagina['hero']['tarjeta_ejemplo']; ?>
                                <div class="card" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa;">
                                    <div class="form-group">
                                        <label>Título</label>
                                        <input type="text" name="tarjeta_titulo[]" class="form-control" 
                                               value="<?php echo htmlspecialchars($tarjeta['titulo']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Descripción</label>
                                        <textarea name="tarjeta_descripcion[]" class="form-control" rows="2"><?php echo htmlspecialchars($tarjeta['descripcion']); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Metadata (separar con comas)</label>
                                        <input type="text" name="tarjeta_metadata[]" class="form-control" 
                                               value="<?php echo htmlspecialchars(implode(', ', $tarjeta['metadata'])); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Texto del Enlace</label>
                                        <input type="text" name="tarjeta_enlace_texto[]" class="form-control" 
                                               value="<?php echo htmlspecialchars($tarjeta['enlace_texto']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>URL del Enlace</label>
                                        <input type="url" name="tarjeta_enlace_url[]" class="form-control" 
                                               value="<?php echo htmlspecialchars($tarjeta['enlace_url']); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div style="margin-top: 20px;">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Guardar Cambios Hero
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Becas -->
            <div id="becas" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-graduation-cap"></i> Becas Disponibles</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="section" value="becas_destacadas">
                            
                            <div class="form-group">
                                <label for="becas_titulo">Título de la Sección</label>
                                <input type="text" id="becas_titulo" name="becas_titulo" class="form-control" 
                                       value="<?php echo htmlspecialchars($pagina['seccion_becas']['titulo']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="becas_subtitulo">Subtítulo</label>
                                <input type="text" id="becas_subtitulo" name="becas_subtitulo" class="form-control" 
                                       value="<?php echo htmlspecialchars($pagina['seccion_becas']['subtitulo']); ?>">
                            </div>
                            
                            <h4>Becas</h4>
                            <div id="becas-list" class="dynamic-list">
                                <?php foreach ($pagina['seccion_becas']['becas'] as $index => $beca): ?>
                                <div class="card" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa;">
                                    <input type="hidden" name="beca_id[]" value="<?php echo $beca['id']; ?>">
                                    <div class="form-group">
                                        <label>Nombre de la Beca</label>
                                        <input type="text" name="beca_nombre[]" class="form-control" 
                                               value="<?php echo htmlspecialchars($beca['nombre']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Monto/Estímulo</label>
                                        <input type="text" name="beca_monto[]" class="form-control" 
                                               value="<?php echo htmlspecialchars($beca['monto']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Resumen/Descripción</label>
                                        <textarea name="beca_resumen[]" class="form-control" rows="2"><?php echo htmlspecialchars($beca['resumen']); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Requisitos (separar con comas)</label>
                                        <input type="text" name="beca_requisitos[]" class="form-control" 
                                               value="<?php echo htmlspecialchars(implode(', ', $beca['requisitos'])); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="beca_bloqueada[]" value="1" <?php echo $beca['bloqueada'] ? 'checked' : ''; ?>>
                                            ¿Beca bloqueada? (solo disponible para estudiantes registrados)
                                        </label>
                                    </div>
                                    
                                    <?php if (!$beca['bloqueada']): ?>
                                        <div class="form-group">
                                            <label>Enlace para Postular</label>
                                            <input type="url" name="beca_enlace_postular[]" class="form-control" 
                                                   value="<?php echo htmlspecialchars($beca['enlace_postular'] ?? ''); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Enlace de Requisitos (PDF)</label>
                                            <select name="beca_enlace_requisitos[]" class="form-control">
                                                <option value="">Seleccionar PDF...</option>
                                                <?php foreach ($pdf_files as $pdf): ?>
                                                    <?php 
                                                    $current_pdf = basename($beca['enlace_descarga_requisitos'] ?? '');
                                                    $selected = ($current_pdf === $pdf) ? 'selected' : '';
                                                    ?>
                                                    <option value="<?php echo $pdf; ?>" <?php echo $selected; ?>>
                                                        <?php echo $pdf; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="text-muted">PDFs disponibles en assets/PDF/</small>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <button type="button" class="btn btn-danger btn-sm remove-beca">
                                        <i class="fas fa-trash"></i> Eliminar Beca
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="add-beca">
                                <i class="fas fa-plus"></i> Agregar Beca
                            </button>
                            
                            <div style="margin-top: 20px;">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Guardar Becas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Asesorías -->
            <div id="asesorias" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-hands-helping"></i> Asesorías</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="section" value="asesorias">
                            
                            <div class="form-group">
                                <label for="asesorias_titulo">Título de la Sección</label>
                                <input type="text" id="asesorias_titulo" name="asesorias_titulo" class="form-control" 
                                       value="<?php echo htmlspecialchars($pagina['seccion_asesorias']['titulo_seccion']); ?>">
                            </div>
                            
                            <h4>Características</h4>
                            <div id="caracteristicas-list" class="dynamic-list">
                                <?php foreach ($pagina['seccion_asesorias']['caracteristicas'] as $index => $caracteristica): ?>
                                <div class="list-item">
                                    <input type="text" name="caracteristica_titulo[]" class="form-control" placeholder="Título" 
                                           value="<?php echo htmlspecialchars($caracteristica['titulo']); ?>">
                                    <input type="text" name="caracteristica_descripcion[]" class="form-control" placeholder="Descripción" 
                                           value="<?php echo htmlspecialchars($caracteristica['descripcion']); ?>">
                                    <button type="button" class="btn btn-danger btn-sm remove-item">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="add-caracteristica">
                                <i class="fas fa-plus"></i> Agregar Característica
                            </button>
                            
                            <div style="margin-top: 20px;">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Guardar Asesorías
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Chatbot -->
            <div id="chatbot" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-robot"></i> Chatbot</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="section" value="chatbot">
                            
                            <div class="form-group">
                                <label for="chatbot_nombre">Nombre del Bot</label>
                                <input type="text" id="chatbot_nombre" name="chatbot_nombre" class="form-control" 
                                       value="<?php echo htmlspecialchars($pagina['chatbot']['nombre']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="chatbot_mensaje_bienvenida">Mensaje de Bienvenida</label>
                                <textarea id="chatbot_mensaje_bienvenida" name="chatbot_mensaje_bienvenida" class="form-control" rows="2"><?php echo htmlspecialchars($pagina['chatbot']['mensaje_bienvenida']); ?></textarea>
                            </div>
                            
                            <h4>Opciones Iniciales</h4>
                            <div id="opciones-list" class="dynamic-list">
                                <?php foreach ($pagina['chatbot']['opciones_iniciales'] as $index => $opcion): ?>
                                <div class="list-item">
                                    <input type="text" name="opcion_texto[]" class="form-control" placeholder="Texto de la opción" 
                                           value="<?php echo htmlspecialchars($opcion); ?>">
                                    <button type="button" class="btn btn-danger btn-sm remove-item">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="add-opcion">
                                <i class="fas fa-plus"></i> Agregar Opción
                            </button>
                            
                            <h4 style="margin-top: 20px;">Respuestas Pregrabadas</h4>
                            <div id="respuestas-list" class="dynamic-list">
                                <?php foreach ($pagina['chatbot']['respuestas_pregrabadas'] as $clave => $texto): ?>
                                <div class="card" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa;">
                                    <div class="form-group">
                                        <label>Clave (número o palabra clave)</label>
                                        <input type="text" name="respuesta_clave[]" class="form-control" 
                                               value="<?php echo htmlspecialchars($clave); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Texto de Respuesta</label>
                                        <textarea name="respuesta_texto[]" class="form-control" rows="3"><?php echo htmlspecialchars($texto); ?></textarea>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm remove-respuesta">
                                        <i class="fas fa-trash"></i> Eliminar Respuesta
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="add-respuesta">
                                <i class="fas fa-plus"></i> Agregar Respuesta
                            </button>
                            
                            <div style="margin-top: 20px;">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Guardar Chatbot
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Navegación entre pestañas
            $('.sidebar-menu a').on('click', function(e) {
                e.preventDefault();
                const tab = $(this).data('tab');
                
                $('.sidebar-menu a').removeClass('active');
                $(this).addClass('active');
                
                $('.tab-content').removeClass('active');
                $(`#${tab}`).addClass('active');
            });

            // Botones de acciones rápidas
            $('[data-tab]').on('click', function() {
                const tab = $(this).data('tab');
                
                $('.sidebar-menu a').removeClass('active');
                $(`.sidebar-menu a[data-tab="${tab}"]`).addClass('active');
                
                $('.tab-content').removeClass('active');
                $(`#${tab}`).addClass('active');
            });

            // Agregar elementos dinámicos
            $('#add-insignia').on('click', function() {
                $('#insignias-list').append(`
                    <div class="list-item">
                        <input type="text" name="insignia_texto[]" class="form-control" placeholder="Texto de la insignia">
                        <select name="insignia_clase[]" class="form-control">
                            <option value="">Normal</option>
                            <option value="ok">Éxito</option>
                            <option value="warn">Advertencia</option>
                        </select>
                        <button type="button" class="btn btn-danger btn-sm remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `);
            });

            $('#add-beca').on('click', function() {
                $('#becas-list').append(`
                    <div class="card" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa;">
                        <input type="hidden" name="beca_id[]" value="${Date.now()}">
                        <div class="form-group">
                            <label>Nombre de la Beca</label>
                            <input type="text" name="beca_nombre[]" class="form-control" placeholder="Nombre de la beca" required>
                        </div>
                        <div class="form-group">
                            <label>Monto/Estímulo</label>
                            <input type="text" name="beca_monto[]" class="form-control" placeholder="Ej: $3000">
                        </div>
                        <div class="form-group">
                            <label>Resumen/Descripción</label>
                            <textarea name="beca_resumen[]" class="form-control" rows="2" placeholder="Descripción de la beca"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Requisitos (separar con comas)</label>
                            <input type="text" name="beca_requisitos[]" class="form-control" placeholder="Ej: Promedio 8.5, Entrevista técnica">
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="beca_bloqueada[]" value="1">
                                ¿Beca bloqueada? (solo disponible para estudiantes registrados)
                            </label>
                        </div>
                        <div class="enlaces-beca">
                            <div class="form-group">
                                <label>Enlace para Postular</label>
                                <input type="url" name="beca_enlace_postular[]" class="form-control" placeholder="https://...">
                            </div>
                            <div class="form-group">
                                <label>Enlace de Requisitos (PDF)</label>
                                <select name="beca_enlace_requisitos[]" class="form-control">
                                    <option value="">Seleccionar PDF...</option>
                                    <?php foreach ($pdf_files as $pdf): ?>
                                        <option value="<?php echo $pdf; ?>"><?php echo $pdf; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">PDFs disponibles en assets/PDF/</small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm remove-beca">
                            <i class="fas fa-trash"></i> Eliminar Beca
                        </button>
                    </div>
                `);
            });

            $('#add-caracteristica').on('click', function() {
                $('#caracteristicas-list').append(`
                    <div class="list-item">
                        <input type="text" name="caracteristica_titulo[]" class="form-control" placeholder="Título">
                        <input type="text" name="caracteristica_descripcion[]" class="form-control" placeholder="Descripción">
                        <button type="button" class="btn btn-danger btn-sm remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `);
            });

            $('#add-opcion').on('click', function() {
                $('#opciones-list').append(`
                    <div class="list-item">
                        <input type="text" name="opcion_texto[]" class="form-control" placeholder="Texto de la opción">
                        <button type="button" class="btn btn-danger btn-sm remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `);
            });

            $('#add-respuesta').on('click', function() {
                $('#respuestas-list').append(`
                    <div class="card" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa;">
                        <div class="form-group">
                            <label>Clave (número o palabra clave)</label>
                            <input type="text" name="respuesta_clave[]" class="form-control" placeholder="Ej: 1, ayuda, monto">
                        </div>
                        <div class="form-group">
                            <label>Texto de Respuesta</label>
                            <textarea name="respuesta_texto[]" class="form-control" rows="3" placeholder="Respuesta del chatbot"></textarea>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm remove-respuesta">
                            <i class="fas fa-trash"></i> Eliminar Respuesta
                        </button>
                    </div>
                `);
            });

            // Eliminar elementos
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.list-item').remove();
            });

            $(document).on('click', '.remove-beca', function() {
                $(this).closest('.card').remove();
            });

            $(document).on('click', '.remove-respuesta', function() {
                $(this).closest('.card').remove();
            });

            // Manejar checkboxes de becas bloqueadas
            $(document).on('change', 'input[name="beca_bloqueada[]"]', function() {
                const enlacesDiv = $(this).closest('.card').find('.enlaces-beca');
                if ($(this).is(':checked')) {
                    enlacesDiv.hide();
                } else {
                    enlacesDiv.show();
                }
            });

            // Inicializar estado de checkboxes
            $('input[name="beca_bloqueada[]"]').each(function() {
                const enlacesDiv = $(this).closest('.card').find('.enlaces-beca');
                if ($(this).is(':checked')) {
                    enlacesDiv.hide();
                }
            });
        });
    </script>

    <?php include "../../includes/footer.php"; ?>
</body>
</html>