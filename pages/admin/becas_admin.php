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
            "titulo_principal" => "Consigue tu <span class=\"grad\">beca</span> <br> Solicita <span class=\"grad alt\">informaci&oacute;n</span> que te abran puertas",
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
            "titulo_seccion" => "Asesor&iacute;as",
            "caracteristicas" => []
        ],
        "chatbot" => [
            "nombre" => "UTPN-BOT",
            "opciones_iniciales" => [],
            "respuestas_pregrabadas" => []
        ]
    ];
}

// Procesar guardado de datos
$success = "";
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['json_data'])) {
        $new_data = json_decode($_POST['json_data'], true);
        if ($new_data) {
            if (file_put_contents($json_file, json_encode($new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                $data = $new_data;
                $success = "¡Datos guardados correctamente en el servidor!";
            } else {
                $error = "Error: No se pudo guardar el archivo JSON";
            }
        } else {
            $error = "Error: JSON inválido";
        }
    }
    
    // Logout
    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: admin-login.php');
        exit;
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

        .sidebar-header h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.3rem;
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

        .sidebar-menu i {
            width: 20px;
            text-align: center;
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

        .header h1 {
            color: var(--dark);
            font-size: 1.8rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
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

        .card-header h3 {
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
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
            transition: border 0.3s;
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

        .btn-primary:hover {
            background: var(--secondary);
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

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #555;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        .actions {
            display: flex;
            gap: 5px;
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 20px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .tab.active {
            border-bottom-color: var(--primary);
            color: var(--primary);
            font-weight: 600;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
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

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-primary {
            background: var(--primary);
            color: white;
        }

        .badge-success {
            background: var(--success);
            color: white;
        }

        .badge-warning {
            background: var(--warning);
            color: white;
        }

        /* JSON Preview */
        .json-preview {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 15px;
            font-family: monospace;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
            font-size: 0.9rem;
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
        }

        .list-item input {
            flex: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .sidebar-menu {
                display: flex;
                overflow-x: auto;
            }
            
            .sidebar-menu li {
                flex-shrink: 0;
            }
            
            .sidebar-menu a {
                padding: 10px 15px;
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
                <li><a href="#" data-tab="hero"><i class="fas fa-home"></i> Sección Hero</a></li>
                <li><a href="#" data-tab="becas"><i class="fas fa-graduation-cap"></i> Becas Destacadas</a></li>
                <li><a href="#" data-tab="asesorias"><i class="fas fa-hands-helping"></i> Asesorías</a></li>
                <li><a href="#" data-tab="chatbot"><i class="fas fa-robot"></i> Chatbot</a></li>
                <li><a href="#" data-tab="json"><i class="fas fa-code"></i> JSON Completo</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Panel de Administración - Becas Universitarias</h1>
                <div class="user-info">
                    <div class="user-avatar">A</div>
                    <span>Administrador</span>
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
                        <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                            <div class="card">
                                <div class="card-body">
                                    <h4>Becas Destacadas</h4>
                                    <p id="becas-count"><?php echo count($data['seccion_becas_destacadas']['becas']); ?> becas registradas</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4>Características de Asesorías</h4>
                                    <p id="asesorias-count"><?php echo count($data['seccion_asesorias']['caracteristicas']); ?> características</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4>Opciones del Chatbot</h4>
                                    <p id="chatbot-count"><?php echo count($data['chatbot']['opciones_iniciales']); ?> opciones iniciales</p>
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
                            <button class="btn btn-primary" data-tab="hero">
                                <i class="fas fa-edit"></i> Editar Hero
                            </button>
                            <button class="btn btn-success" data-tab="becas">
                                <i class="fas fa-plus"></i> Agregar Beca
                            </button>
                            <button class="btn btn-warning" data-tab="asesorias">
                                <i class="fas fa-cog"></i> Configurar Asesorías
                            </button>
                            <button class="btn btn-primary" data-tab="json">
                                <i class="fas fa-download"></i> Exportar JSON
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- JSON Completo -->
            <div id="json" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-code"></i> JSON Completo</h3>
                    </div>
                    <div class="card-body">
                        <p>Aquí puedes ver y editar el JSON completo de tu configuración:</p>
                        <form method="POST">
                            <div class="form-group">
                                <label for="json_data">Datos JSON:</label>
                                <textarea 
                                    id="json_data" 
                                    name="json_data" 
                                    class="json-preview" 
                                    required
                                    rows="20"
                                ><?php echo htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></textarea>
                            </div>
                            
                            <div style="margin-top: 15px;">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Guardar en Servidor
                                </button>
                                <button type="button" class="btn btn-primary" id="download-json">
                                    <i class="fas fa-download"></i> Descargar JSON
                                </button>
                                <a href="../" class="btn btn-warning" target="_blank">
                                    <i class="fas fa-eye"></i> Ver Sitio Web
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Otras secciones (Hero, Becas, Asesorías, Chatbot) -->
            <div id="hero" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-home"></i> Información General</h3>
                    </div>
                    <div class="card-body">
                        <p>Para editar la sección Hero, ve a la pestaña "JSON Completo" y modifica la estructura correspondiente.</p>
                        <div class="json-preview">
<?php echo htmlspecialchars(json_encode($data['hero'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="becas" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-graduation-cap"></i> Información de Becas</h3>
                    </div>
                    <div class="card-body">
                        <p>Para gestionar las becas destacadas, ve a la pestaña "JSON Completo" y modifica la estructura correspondiente.</p>
                        <div class="json-preview">
<?php echo htmlspecialchars(json_encode($data['seccion_becas_destacadas'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="asesorias" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-hands-helping"></i> Información de Asesorías</h3>
                    </div>
                    <div class="card-body">
                        <p>Para gestionar las asesorías, ve a la pestaña "JSON Completo" y modifica la estructura correspondiente.</p>
                        <div class="json-preview">
<?php echo htmlspecialchars(json_encode($data['seccion_asesorias'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="chatbot" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-robot"></i> Información del Chatbot</h3>
                    </div>
                    <div class="card-body">
                        <p>Para configurar el chatbot, ve a la pestaña "JSON Completo" y modifica la estructura correspondiente.</p>
                        <div class="json-preview">
<?php echo htmlspecialchars(json_encode($data['chatbot'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configurar navegación entre pestañas
        $(document).ready(function() {
            $('.sidebar-menu a').on('click', function(e) {
                e.preventDefault();
                const tab = $(this).data('tab');
                
                $('.sidebar-menu a').removeClass('active');
                $(this).addClass('active');
                
                $('.tab-content').removeClass('active');
                $(`#${tab}`).addClass('active');
            });

            $('[data-tab]').on('click', function() {
                const tab = $(this).data('tab');
                
                $('.sidebar-menu a').removeClass('active');
                $(`.sidebar-menu a[data-tab="${tab}"]`).addClass('active');
                
                $('.tab-content').removeClass('active');
                $(`#${tab}`).addClass('active');
            });

            // Descargar JSON
            $('#download-json').on('click', function() {
                const dataStr = $('#json_data').val();
                const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
                
                const exportFileDefaultName = 'becas-data.json';
                
                const linkElement = document.createElement('a');
                linkElement.setAttribute('href', dataUri);
                linkElement.setAttribute('download', exportFileDefaultName);
                linkElement.click();
            });

            // Validación básica del JSON antes de enviar
            $('form').on('submit', function(e) {
                const jsonTextarea = document.getElementById('json_data');
                try {
                    JSON.parse(jsonTextarea.value);
                } catch (error) {
                    e.preventDefault();
                    alert('Error: JSON inválido. Por favor corrige los errores antes de guardar.\n\n' + error.message);
                    jsonTextarea.focus();
                }
            });
            
            // Auto-indentación del JSON
            document.getElementById('json_data').addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    e.preventDefault();
                    const start = this.selectionStart;
                    const end = this.selectionEnd;
                    
                    this.value = this.value.substring(0, start) + '    ' + this.value.substring(end);
                    this.selectionStart = this.selectionEnd = start + 4;
                }
            });
        });
    </script>
</body>
<?php include "../../includes/footer.php"; ?>
</html>