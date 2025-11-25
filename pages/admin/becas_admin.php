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

// Si hay error cargando el JSON, usar datos por defecto
if (!$data) {
    $data = [
        "titulo_pagina" => "Becas Universitarias | Impulsa tu camino",
        "hero" => [
            "chip_texto" => "Convocatorias abiertas",
            "titulo_principal" => "Consigue tu <span class=\"grad\">beca</span> <br> Solicita <span class=\"grad alt\">información</span> que te abran puertas",
            "descripcion" => "Explora convocatorias que te ayudaran en tu carrera.",
            "insignias" => [
                ["texto" => "5 becas especializadas", "clase" => "ok"],
                ["texto" => "Porcentaje alto de obtener la beca", "clase" => ""],
                ["texto" => "Facil de acceder", "clase" => "warn"]
            ],
            "tarjetas_ejemplo" => [
                [
                    "titulo" => "Beca Subes",
                    "descripcion" => "Requisitos claros.",
                    "metadata" => ["🇲🇽 México", "Licenciatura e Ingenieria", "Fecha de inicio: Empezando cuatrimestre"],
                    "enlace_texto" => null,
                    "enlace_url" => null
                ]
            ]
        ],
        "seccion_becas_destacadas" => [
            "titulo" => "Becas destacadas",
            "subtitulo" => "Curadas y verificadas por nuestro equipo.",
            "becas" => []
        ],
        "seccion_asesorias" => [
            "titulo_seccion" => "Asesorías",
            "caracteristicas" => []
        ],
        "chatbot" => [
            "nombre" => "UTPN-BOT",
            "opciones_iniciales" => [],
            "respuestas_pregrabadas" => []
        ]
    ];
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
        
        // Procesar según la sección
        switch($section) {
            case 'general':
                $data['titulo_pagina'] = $_POST['titulo_pagina'];
                break;
                
            case 'hero':
                $data['hero']['chip_texto'] = $_POST['chip_texto'];
                
                // Procesar título principal - convertir texto a HTML
                $titulo_texto = $_POST['titulo_principal'];
                $titulo_html = $titulo_texto;
                // Aplicar formato grad a "beca"
                $titulo_html = preg_replace('/(^|\s)(beca)($|\s)/', '$1<span class="grad">$2</span>$3', $titulo_html);
                // Aplicar formato grad alt a "información"
                $titulo_html = preg_replace('/(^|\s)(información)($|\s)/', '$1<span class="grad alt">$2</span>$3', $titulo_html);
                // Reemplazar saltos de línea por <br>
                $titulo_html = str_replace("\n", '<br>', $titulo_html);
                $data['hero']['titulo_principal'] = $titulo_html;
                
                $data['hero']['descripcion'] = $_POST['descripcion'];
                
                // Procesar insignias
                $data['hero']['insignias'] = [];
                if (isset($_POST['insignia_texto'])) {
                    foreach ($_POST['insignia_texto'] as $index => $texto) {
                        if (!empty($texto)) {
                            $data['hero']['insignias'][] = [
                                'texto' => $texto,
                                'clase' => $_POST['insignia_clase'][$index] ?? ''
                            ];
                        }
                    }
                }
                
                // Procesar tarjetas
                $data['hero']['tarjetas_ejemplo'] = [];
                if (isset($_POST['tarjeta_titulo'])) {
                    foreach ($_POST['tarjeta_titulo'] as $index => $titulo) {
                        if (!empty($titulo)) {
                            $metadata = isset($_POST['tarjeta_metadata'][$index]) ? 
                                array_filter(array_map('trim', explode(',', $_POST['tarjeta_metadata'][$index]))) : [];
                            
                            $data['hero']['tarjetas_ejemplo'][] = [
                                'titulo' => $titulo,
                                'descripcion' => $_POST['tarjeta_descripcion'][$index] ?? '',
                                'metadata' => $metadata,
                                'enlace_texto' => $_POST['tarjeta_enlace_texto'][$index] ?? null,
                                'enlace_url' => $_POST['tarjeta_enlace_url'][$index] ?? null
                            ];
                        }
                    }
                }
                break;
                
            case 'becas_destacadas':
                $data['seccion_becas_destacadas']['titulo'] = $_POST['becas_titulo'];
                $data['seccion_becas_destacadas']['subtitulo'] = $_POST['becas_subtitulo'];
                
                // Procesar becas
                $data['seccion_becas_destacadas']['becas'] = [];
                if (isset($_POST['beca_nombre'])) {
                    foreach ($_POST['beca_nombre'] as $index => $nombre) {
                        if (!empty($nombre)) {
                            $requisitos = isset($_POST['beca_requisitos'][$index]) ? 
                                array_filter(array_map('trim', explode(',', $_POST['beca_requisitos'][$index]))) : [];
                            
                            // Procesar enlace de requisitos - agregar ruta si es un PDF
                            $enlace_requisitos = $_POST['beca_enlace_requisitos'][$index] ?? '';
                            if (!empty($enlace_requisitos) && !str_contains($enlace_requisitos, '://')) {
                                $enlace_requisitos = 'assets/PDF/' . $enlace_requisitos;
                            }
                            
                            $data['seccion_becas_destacadas']['becas'][] = [
                                'id' => $_POST['beca_id'][$index] ?? time() + $index,
                                'nombre' => $nombre,
                                'monto' => $_POST['beca_monto'][$index] ?? '',
                                'resumen' => $_POST['beca_resumen'][$index] ?? '',
                                'requisitos' => $requisitos,
                                'enlace_postular' => $_POST['beca_enlace_postular'][$index] ?? '',
                                'enlace_descarga_requisitos' => $enlace_requisitos
                            ];
                        }
                    }
                }
                break;
                
            case 'asesorias':
                $data['seccion_asesorias']['titulo_seccion'] = $_POST['asesorias_titulo'];
                
                // Procesar características
                $data['seccion_asesorias']['caracteristicas'] = [];
                if (isset($_POST['caracteristica_titulo'])) {
                    foreach ($_POST['caracteristica_titulo'] as $index => $titulo) {
                        if (!empty($titulo)) {
                            $data['seccion_asesorias']['caracteristicas'][] = [
                                'titulo' => $titulo,
                                'descripcion' => $_POST['caracteristica_descripcion'][$index] ?? ''
                            ];
                        }
                    }
                }
                break;
                
            case 'chatbot':
                $data['chatbot']['nombre'] = $_POST['chatbot_nombre'];
                
                // Procesar opciones - extraer solo el texto visible
                $data['chatbot']['opciones_iniciales'] = [];
                if (isset($_POST['opcion_texto'])) {
                    foreach ($_POST['opcion_texto'] as $texto) {
                        if (!empty($texto)) {
                            $data['chatbot']['opciones_iniciales'][] = $texto;
                        }
                    }
                }
                
                // Procesar respuestas - extraer solo el texto visible
                $data['chatbot']['respuestas_pregrabadas'] = [];
                if (isset($_POST['respuesta_clave'])) {
                    foreach ($_POST['respuesta_clave'] as $index => $clave) {
                        if (!empty($clave)) {
                            $data['chatbot']['respuestas_pregrabadas'][$clave] = $_POST['respuesta_texto'][$index] ?? '';
                        }
                    }
                }
                // Asegurar respuesta por defecto
                if (!isset($data['chatbot']['respuestas_pregrabadas']['default'])) {
                    $data['chatbot']['respuestas_pregrabadas']['default'] = '🤖 No entendí tu pregunta. Por favor, reformula tu pregunta. 😊';
                }
                break;
        }
        
        // Guardar en el archivo JSON
        if (file_put_contents($json_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
            $success = "¡Cambios guardados correctamente!";
        } else {
            $error = "Error: No se pudo guardar el archivo JSON";
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

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
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

        /* Editor de texto mejorado */
        .text-editor-container {
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }

        .editor-toolbar {
            background: #f8f9fa;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .editor-toolbar button {
            background: white;
            border: 1px solid #ddd;
            border-radius: 3px;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 14px;
        }

        .text-editor {
            width: 100%;
            min-height: 200px;
            padding: 15px;
            border: none;
            font-size: 16px;
            line-height: 1.5;
            resize: vertical;
        }

        .preview-container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background: white;
            margin-top: 15px;
        }

        .preview-title {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .preview-content {
            font-size: 16px;
            line-height: 1.5;
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

        /* Estilos para elementos activos del sidebar */
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            border-left: 4px solid white;
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
                <li><a href="#" data-tab="becas"><i class="fas fa-graduation-cap"></i> Becas Destacadas</a></li>
                <li><a href="#" data-tab="asesorias"><i class="fas fa-hands-helping"></i> Asesorías</a></li>
                <li><a href="#" data-tab="chatbot"><i class="fas fa-robot"></i> Chatbot</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Panel de Administración - Becas Universitarias</h1>
                <div class="user-info">
                    <form method="POST" style="display: inline;">
                       
                    </form>
                </div>
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
                                    <h4>Becas Destacadas</h4>
                                    <p><?php echo count($data['seccion_becas_destacadas']['becas']); ?> becas registradas</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4>Características de Asesorías</h4>
                                    <p><?php echo count($data['seccion_asesorias']['caracteristicas']); ?> características</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4>Opciones del Chatbot</h4>
                                    <p><?php echo count($data['chatbot']['opciones_iniciales']); ?> opciones iniciales</p>
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
                                       value="<?php echo htmlspecialchars($data['titulo_pagina']); ?>" required>
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
                                       value="<?php echo htmlspecialchars($data['hero']['chip_texto']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="titulo_principal">Título Principal</label>
                                <div class="text-editor-container">
                                    <div class="editor-toolbar">
                                        <button type="button" data-format="bold"><i class="fas fa-bold"></i></button>
                                        <button type="button" data-format="italic"><i class="fas fa-italic"></i></button>
                                        <button type="button" data-format="grad">Beca</button>
                                        <button type="button" data-format="grad-alt">Información</button>
                                        <button type="button" data-format="linebreak">Salto de línea</button>
                                    </div>
                                    <textarea id="titulo_principal" name="titulo_principal" class="text-editor" rows="3"><?php 
                                        // Extraer texto visible del HTML
                                        $titulo = $data['hero']['titulo_principal'];
                                        $titulo = preg_replace('/<span class="grad">(.*?)<\/span>/', '$1', $titulo);
                                        $titulo = preg_replace('/<span class="grad alt">(.*?)<\/span>/', '$1', $titulo);
                                        $titulo = str_replace('<br>', "\n", $titulo);
                                        echo htmlspecialchars($titulo);
                                    ?></textarea>
                                </div>
                                <div class="preview-container">
                                    <div class="preview-title">Vista previa:</div>
                                    <div class="preview-content" id="tituloPreview"></div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea id="descripcion" name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($data['hero']['descripcion']); ?></textarea>
                            </div>
                            
                            <h4>Insignias</h4>
                            <div id="insignias-list" class="dynamic-list">
                                <?php foreach ($data['hero']['insignias'] as $index => $insignia): ?>
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
                            
                            <h4 style="margin-top: 20px;">Tarjetas de Ejemplo</h4>
                            <div id="tarjetas-list" class="dynamic-list">
                                <?php foreach ($data['hero']['tarjetas_ejemplo'] as $index => $tarjeta): ?>
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
                                        <label>Texto del Enlace (opcional)</label>
                                        <input type="text" name="tarjeta_enlace_texto[]" class="form-control" 
                                               value="<?php echo htmlspecialchars($tarjeta['enlace_texto'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>URL del Enlace (opcional)</label>
                                        <input type="url" name="tarjeta_enlace_url[]" class="form-control" 
                                               value="<?php echo htmlspecialchars($tarjeta['enlace_url'] ?? ''); ?>">
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm remove-tarjeta">
                                        <i class="fas fa-trash"></i> Eliminar Tarjeta
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="add-tarjeta">
                                <i class="fas fa-plus"></i> Agregar Tarjeta
                            </button>
                            
                            <div style="margin-top: 20px;">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Guardar Cambios Hero
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Becas Destacadas -->
            <div id="becas" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-graduation-cap"></i> Becas Destacadas</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="section" value="becas_destacadas">
                            
                            <div class="form-group">
                                <label for="becas_titulo">Título de la Sección</label>
                                <input type="text" id="becas_titulo" name="becas_titulo" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['seccion_becas_destacadas']['titulo']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="becas_subtitulo">Subtítulo</label>
                                <input type="text" id="becas_subtitulo" name="becas_subtitulo" class="form-control" 
                                       value="<?php echo htmlspecialchars($data['seccion_becas_destacadas']['subtitulo']); ?>">
                            </div>
                            
                            <h4>Becas</h4>
                            <div id="becas-list" class="dynamic-list">
                                <?php foreach ($data['seccion_becas_destacadas']['becas'] as $index => $beca): ?>
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
                                        <label>Enlace para Postular</label>
                                        <input type="url" name="beca_enlace_postular[]" class="form-control" 
                                               value="<?php echo htmlspecialchars($beca['enlace_postular']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Enlace de Requisitos (PDF)</label>
                                        <select name="beca_enlace_requisitos[]" class="form-control">
                                            <option value="">Seleccionar PDF...</option>
                                            <?php foreach ($pdf_files as $pdf): ?>
                                                <?php 
                                                $current_pdf = basename($beca['enlace_descarga_requisitos']);
                                                $selected = ($current_pdf === $pdf) ? 'selected' : '';
                                                ?>
                                                <option value="<?php echo $pdf; ?>" <?php echo $selected; ?>>
                                                    <?php echo $pdf; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="text-muted">PDFs disponibles en assets/PDF/</small>
                                    </div>
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
                                       value="<?php echo htmlspecialchars($data['seccion_asesorias']['titulo_seccion']); ?>">
                            </div>
                            
                            <h4>Características</h4>
                            <div id="caracteristicas-list" class="dynamic-list">
                                <?php foreach ($data['seccion_asesorias']['caracteristicas'] as $index => $caracteristica): ?>
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
                                       value="<?php echo htmlspecialchars($data['chatbot']['nombre']); ?>">
                            </div>
                            
                            <h4>Opciones Iniciales</h4>
                            <div id="opciones-list" class="dynamic-list">
                                <?php foreach ($data['chatbot']['opciones_iniciales'] as $index => $opcion): ?>
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
                                <?php foreach ($data['chatbot']['respuestas_pregrabadas'] as $clave => $texto): ?>
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
        // Configurar navegación entre pestañas
        $(document).ready(function() {
            // Manejar clics en el sidebar
            $('.sidebar-menu a').on('click', function(e) {
                e.preventDefault();
                const tab = $(this).data('tab');
                
                // Actualizar sidebar
                $('.sidebar-menu a').removeClass('active');
                $(this).addClass('active');
                
                // Mostrar contenido correspondiente
                $('.tab-content').removeClass('active');
                $(`#${tab}`).addClass('active');
            });

            // Manejar botones de acciones rápidas
            $('[data-tab]').on('click', function() {
                const tab = $(this).data('tab');
                
                // Actualizar sidebar
                $('.sidebar-menu a').removeClass('active');
                $(`.sidebar-menu a[data-tab="${tab}"]`).addClass('active');
                
                // Mostrar contenido correspondiente
                $('.tab-content').removeClass('active');
                $(`#${tab}`).addClass('active');
            });

            // Funciones para agregar elementos dinámicos
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

            $('#add-tarjeta').on('click', function() {
                $('#tarjetas-list').append(`
                    <div class="card" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa;">
                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" name="tarjeta_titulo[]" class="form-control" placeholder="Título de la tarjeta">
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="tarjeta_descripcion[]" class="form-control" rows="2" placeholder="Descripción"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Metadata (separar con comas)</label>
                            <input type="text" name="tarjeta_metadata[]" class="form-control" placeholder="Ej: México, Licenciatura, Fecha">
                        </div>
                        <div class="form-group">
                            <label>Texto del Enlace (opcional)</label>
                            <input type="text" name="tarjeta_enlace_texto[]" class="form-control" placeholder="Ej: Empezar">
                        </div>
                        <div class="form-group">
                            <label>URL del Enlace (opcional)</label>
                            <input type="url" name="tarjeta_enlace_url[]" class="form-control" placeholder="https://...">
                        </div>
                        <button type="button" class="btn btn-danger btn-sm remove-tarjeta">
                            <i class="fas fa-trash"></i> Eliminar Tarjeta
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

            $(document).on('click', '.remove-tarjeta', function() {
                $(this).closest('.card').remove();
            });

            $(document).on('click', '.remove-beca', function() {
                $(this).closest('.card').remove();
            });

            $(document).on('click', '.remove-respuesta', function() {
                $(this).closest('.card').remove();
            });

            // Editor de texto para título principal
            function updateTituloPreview() {
                const editor = document.getElementById('titulo_principal');
                const preview = document.getElementById('tituloPreview');
                
                if (!editor || !preview) return;
                
                // Convertir texto a HTML para la vista previa
                let html = editor.value;
                
                // Aplicar formato grad a "beca"
                html = html.replace(/(^|\s)(beca)($|\s)/g, '$1<span class="grad">$2</span>$3');
                
                // Aplicar formato grad alt a "información"
                html = html.replace(/(^|\s)(información)($|\s)/g, '$1<span class="grad alt">$2</span>$3');
                
                // Reemplazar saltos de línea por <br>
                html = html.replace(/\n/g, '<br>');
                
                preview.innerHTML = html;
            }

            // Configurar editor de texto
            const editor = document.getElementById('titulo_principal');
            const toolbar = document.querySelector('.editor-toolbar');
            
            if (editor) {
                editor.addEventListener('input', updateTituloPreview);
                
                // Inicializar vista previa
                updateTituloPreview();
            }

            if (toolbar) {
                toolbar.addEventListener('click', function(e) {
                    if (e.target.tagName === 'BUTTON') {
                        const format = e.target.dataset.format;
                        applyFormat(format, editor);
                    }
                });
            }

            function applyFormat(format, editor) {
                if (!editor) return;
                
                const start = editor.selectionStart;
                const end = editor.selectionEnd;
                const text = editor.value;
                let newText = '';
                
                switch(format) {
                    case 'bold':
                        newText = text.substring(0, start) + '**' + text.substring(start, end) + '**' + text.substring(end);
                        break;
                    case 'italic':
                        newText = text.substring(0, start) + '_' + text.substring(start, end) + '_' + text.substring(end);
                        break;
                    case 'grad':
                        newText = text.substring(0, start) + 'beca' + text.substring(end);
                        break;
                    case 'grad-alt':
                        newText = text.substring(0, start) + 'información' + text.substring(end);
                        break;
                    case 'linebreak':
                        newText = text.substring(0, start) + '\n' + text.substring(end);
                        break;
                }
                
                editor.value = newText;
                updateTituloPreview();
                
                // Restaurar el foco y la selección
                editor.focus();
                const newPosition = format === 'linebreak' ? start + 1 : start;
                editor.setSelectionRange(newPosition, newPosition);
            }

            // Prevenir envío duplicado de formularios
            $('form').on('submit', function() {
                $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
            });
        });
    </script>
   <?php include "../../includes/footer.php"; ?> 
</body>
</html>