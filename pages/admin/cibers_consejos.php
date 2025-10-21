<?php 
// Verificar que el usuario sea super admin
// session_start();
// if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'super_admin') {
//     header('Location: /login.php');
//     exit();
// }

include __DIR__ . "/../../includes/header.php"; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Consejos - UTPN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --teal: #00837F;
            --gold: #AE874C;
            --cream: #EDE5D6;
            --gray-medium: #7E8080;
            --gray-light: #D0D1D1;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--cream) 0%, var(--gray-light) 100%);
            min-height: 100vh;
        }

        /* Admin Header */
        .admin-header {
            background: linear-gradient(135deg, var(--teal) 0%, #006b68 100%);
            color: white;
            padding: 30px 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .admin-header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .admin-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-title i {
            font-size: 2em;
            color: var(--gold);
        }

        .admin-title h1 {
            font-size: 1.8em;
            font-weight: 700;
        }

        .admin-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        /* Container */
        .admin-container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Tabs Navigation */
        .tabs-nav {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            background: white;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 12px 25px;
            border: 2px solid var(--gray-light);
            background: white;
            color: var(--gray-medium);
            border-radius: 10px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn:hover {
            border-color: var(--teal);
            color: var(--teal);
            transform: translateY(-2px);
        }

        .tab-btn.active {
            background: var(--teal);
            color: white;
            border-color: var(--teal);
        }

        /* Tab Content */
        .tab-content {
            display: none;
            animation: fadeIn 0.5s;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Buttons */
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--teal);
            color: white;
        }

        .btn-primary:hover {
            background: #006b68;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,131,127,0.3);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-warning {
            background: var(--warning);
            color: #333;
        }

        .btn-warning:hover {
            background: #e0a800;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-secondary {
            background: var(--gray-medium);
            color: white;
        }

        .btn-secondary:hover {
            background: #6a6c6c;
        }

        .btn-sm {
            padding: 8px 15px;
            font-size: 0.9em;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--cream);
            flex-wrap: wrap;
            gap: 15px;
        }

        .card-title {
            color: var(--teal);
            font-size: 1.5em;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        thead {
            background: var(--teal);
            color: white;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--gray-light);
        }

        th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9em;
            letter-spacing: 0.5px;
        }

        tbody tr {
            transition: background 0.3s;
        }

        tbody tr:hover {
            background: var(--cream);
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-contraseñas { background: #e3f2fd; color: #1976d2; }
        .badge-phishing { background: #fff3e0; color: #f57c00; }
        .badge-redes-sociales { background: #f3e5f5; color: #7b1fa2; }
        .badge-wifi { background: #e8f5e9; color: #388e3c; }
        .badge-dispositivos { background: #fce4ec; color: #c2185b; }

        .badge-high { background: #ffebee; color: #c62828; }
        .badge-medium { background: #fff3e0; color: #f57c00; }
        .badge-low { background: #e8f5e9; color: #388e3c; }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--gray-medium);
            font-weight: 600;
            font-size: 0.95em;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--gray-light);
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--teal);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        select.form-control {
            cursor: pointer;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .modal.active {
            display: flex;
        }

        .modal-dialog {
            background: white;
            border-radius: 20px;
            max-width: 800px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideInModal 0.3s;
        }

        @keyframes slideInModal {
            from {
                transform: scale(0.9) translateY(-20px);
                opacity: 0;
            }
            to {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 25px 30px;
            border-bottom: 2px solid var(--cream);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            color: var(--teal);
            font-size: 1.8em;
            margin: 0;
        }

        .modal-close {
            font-size: 28px;
            cursor: pointer;
            color: var(--gray-medium);
            background: var(--cream);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s;
            border: none;
        }

        .modal-close:hover {
            background: var(--danger);
            color: white;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 30px;
        }

        .modal-footer {
            padding: 20px 30px;
            border-top: 2px solid var(--cream);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s;
        }

        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid var(--success);
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid var(--danger);
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid var(--warning);
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-icon.blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-icon.green { background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%); }
        .stat-icon.orange { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-icon.purple { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

        .stat-info h3 {
            font-size: 2em;
            color: var(--teal);
            margin-bottom: 5px;
        }

        .stat-info p {
            color: var(--gray-medium);
            font-size: 0.95em;
        }

        /* Search Bar */
        .search-bar {
            position: relative;
            margin-bottom: 20px;
        }

        .search-bar input {
            width: 100%;
            padding: 12px 45px 12px 15px;
            border: 2px solid var(--gray-light);
            border-radius: 10px;
            font-size: 1em;
        }

        .search-bar i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-medium);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray-medium);
        }

        .empty-state i {
            font-size: 4em;
            color: var(--gray-light);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .admin-actions {
                width: 100%;
            }

            .admin-actions .btn {
                flex: 1;
                justify-content: center;
            }

            .tabs-nav {
                flex-direction: column;
            }

            .tab-btn {
                width: 100%;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 0.9em;
            }

            th, td {
                padding: 10px;
            }

            .modal-dialog {
                margin: 10px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="admin-header-content">
            <div class="admin-title">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <h1>Administración de Consejos</h1>
                    <p style="opacity: 0.9; margin-top: 5px;">Panel de gestión de contenido de ciberseguridad</p>
                </div>
            </div>
            <div class="admin-actions">
                <button class="btn btn-primary" onclick="openModal('createModal')">
                    <i class="fas fa-plus"></i> Nuevo Consejo
                </button>
                <a href="../alumno/cibers_consejos.php" class="btn btn-secondary">
                    <i class="fas fa-eye"></i> Vista Usuario
                </a>
            </div>
        </div>
    </header>

    <!-- Admin Container -->
    <div class="admin-container">
        <!-- Alert Messages -->
        <div id="alertContainer"></div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-info">
                    <h3 id="totalConsejos">18</h3>
                    <p>Total de Consejos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3 id="consejosActivos">18</h3>
                    <p>Consejos Activos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-folder"></i>
                </div>
                <div class="stat-info">
                    <h3 id="totalCategorias">5</h3>
                    <p>Categorías</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>Hoy</h3>
                    <p>Última Actualización</p>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <nav class="tabs-nav">
            <button class="tab-btn active" onclick="switchTab('consejos')">
                <i class="fas fa-list"></i> Gestión de Consejos
            </button>
            <button class="tab-btn" onclick="switchTab('categorias')">
                <i class="fas fa-tags"></i> Categorías
            </button>
        </nav>

        <!-- Tab: Gestión de Consejos -->
        <div id="consejos-tab" class="tab-content active">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-shield-alt"></i> Lista de Consejos
                    </h2>
                    <div class="search-bar" style="max-width: 300px;">
                        <input type="text" id="searchConsejos" placeholder="Buscar consejos..." onkeyup="filterConsejos()">
                        <i class="fas fa-search"></i>
                    </div>
                </div>

                <div class="table-container">
                    <table id="consejosTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Categoría</th>
                                <th>Prioridad</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="consejosTableBody">
                            <!-- Los datos se cargarán dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab: Categorías -->
        <div id="categorias-tab" class="tab-content">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-tags"></i> Gestión de Categorías
                    </h2>
                    <button class="btn btn-primary" onclick="openModal('createCategoryModal')">
                        <i class="fas fa-plus"></i> Nueva Categoría
                    </button>
                </div>

                <div class="table-container">
                    <table id="categoriasTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Ícono</th>
                                <th>Consejos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="categoriasTableBody">
                            <!-- Los datos se cargarán dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Crear/Editar Consejo -->
    <div id="createModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h2 id="modalTitle">Nuevo Consejo de Seguridad</h2>
                <button class="modal-close" onclick="closeModal('createModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="consejoForm" onsubmit="saveConsejo(event)">
                    <input type="hidden" id="consejoId">
                    
                    <div class="form-group">
                        <label for="titulo">Título del Consejo *</label>
                        <input type="text" id="titulo" class="form-control" required placeholder="Ej: Contraseñas Fuertes">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="categoria">Categoría *</label>
                            <select id="categoria" class="form-control" required>
                                <option value="">Seleccionar categoría</option>
                                <option value="contraseñas">🔐 Contraseñas</option>
                                <option value="phishing">🎣 Phishing</option>
                                <option value="redes-sociales">📱 Redes Sociales</option>
                                <option value="wifi">📶 Redes WiFi</option>
                                <option value="dispositivos">💻 Dispositivos</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="prioridad">Nivel de Importancia *</label>
                            <select id="prioridad" class="form-control" required>
                                <option value="">Seleccionar nivel</option>
                                <option value="high">Alta</option>
                                <option value="medium">Media</option>
                                <option value="low">Baja</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="icono">Ícono (Emoji)</label>
                        <input type="text" id="icono" class="form-control" placeholder="Ej: 🔐" maxlength="2">
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción Corta *</label>
                        <textarea id="descripcion" class="form-control" required placeholder="Descripción breve que aparece en la tarjeta..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="contenidoCompleto">Contenido Completo *</label>
                        <textarea id="contenidoCompleto" class="form-control" required placeholder="Contenido detallado que aparece en el modal..." style="min-height: 200px;"></textarea>
                        <small style="color: var(--gray-medium); display: block; margin-top: 5px;">
                            Puedes usar HTML básico: &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt;
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createModal')">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit" form="consejoForm" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Consejo
                </button>
            </div>
        </div>
    </div>

    <!-- Modal: Crear/Editar Categoría -->
    <div id="createCategoryModal" class="modal">
        <div class="modal-dialog" style="max-width: 600px;">
            <div class="modal-header">
                <h2 id="categoryModalTitle">Nueva Categoría</h2>
                <button class="modal-close" onclick="closeModal('createCategoryModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="categoryForm" onsubmit="saveCategory(event)">
                    <input type="hidden" id="categoryId">
                    
                    <div class="form-group">
                        <label for="categoryNombre">Nombre de la Categoría *</label>
                        <input type="text" id="categoryNombre" class="form-control" required placeholder="Ej: Seguridad en Redes">
                    </div>

                    <div class="form-group">
                        <label for="categoryIcono">Ícono (Emoji) *</label>
                        <input type="text" id="categoryIcono" class="form-control" required placeholder="Ej: 🌐" maxlength="2">
                    </div>

                    <div class="form-group">
                        <label for="categoryDescripcion">Descripción</label>
                        <textarea id="categoryDescripcion" class="form-control" placeholder="Descripción opcional de la categoría..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createCategoryModal')">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit" form="categoryForm" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Categoría
                </button>
            </div>
        </div>
    </div>

    <!-- Modal: Confirmar Eliminación -->
    <div id="deleteModal" class="modal">
        <div class="modal-dialog" style="max-width: 500px;">
            <div class="modal-header">
                <h2>Confirmar Eliminación</h2>
                <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>¿Estás seguro?</strong>
                        <p id="deleteMessage" style="margin-top: 5px;">Esta acción no se puede deshacer.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('deleteModal')">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </div>

    <script>
        // Datos de ejemplo (en producción vendrían de la base de datos)
        let consejos = [
            {
                id: 1,
                titulo: "Contraseñas Fuertes",
                categoria: "contraseñas",
                prioridad: "high",
                icono: "🔐",
                descripcion: "Utiliza combinaciones de letras mayúsculas, minúsculas, números y símbolos especiales.",
                contenidoCompleto: "<h3>¿Por qué son importantes?</h3><p>Una contraseña fuerte es tu primera línea de defensa...</p>",
                fecha: "2025-01-15"
            },
            {
                id: 2,
                titulo: "No Reutilices Contraseñas",
                categoria: "contraseñas",
                prioridad: "high",
                icono: "🔄",
                descripcion: "Cada cuenta debe tener una contraseña única. Si una cuenta es comprometida, las demás permanecerán seguras.",
                contenidoCompleto: "<h3>Riesgos de la reutilización</h3><p>Cuando reutilizas una contraseña...</p>",
                fecha: "2025-01-15"
            },
            {
                id: 3,
                titulo: "Verifica el Remitente",
                categoria: "phishing",
                prioridad: "high",
                icono: "🎣",
                descripcion: "Antes de hacer clic en enlaces o descargar archivos, verifica cuidadosamente la dirección de correo.",
                contenidoCompleto: "<h3>Señales de correo fraudulento</h3><p>Los atacantes se hacen pasar...</p>",
                fecha: "2025-01-14"
            },
            {
                id: 4,
                titulo: "Configura tu Privacidad",
                categoria: "redes-sociales",
                prioridad: "medium",
                icono: "📱",
                descripcion: "Revisa y ajusta regularmente la configuración de privacidad en tus redes sociales.",
                contenidoCompleto: "<h3>Configuración esencial</h3><p>Las redes sociales recopilan...</p>",
                fecha: "2025-01-13"
            },
            {
                id: 5,
                titulo: "WiFi Públicas",
                categoria: "wifi",
                prioridad: "medium",
                icono: "📶",
                descripcion: "Las redes WiFi públicas son inseguras. Evita realizar transacciones bancarias en estas redes.",
                contenidoCompleto: "<h3>Peligros del WiFi público</h3><p>Las redes WiFi en cafeterías...</p>",
                fecha: "2025-01-12"
            },
            {
                id: 6,
                titulo: "Actualiza tu Software",
                categoria: "dispositivos",
                prioridad: "high",
                icono: "💻",
                descripcion: "Las actualizaciones incluyen parches de seguridad cruciales. Activa las actualizaciones automáticas.",
                contenidoCompleto: "<h3>Importancia de actualizar</h3><p>Las actualizaciones no solo...</p>",
                fecha: "2025-01-11"
            }
        ];

        let categorias = [
            { id: 1, nombre: "Contraseñas", icono: "🔐", descripcion: "Gestión segura de contraseñas", consejos: 2 },
            { id: 2, nombre: "Phishing", icono: "🎣", descripcion: "Prevención de estafas y fraudes", consejos: 1 },
            { id: 3, nombre: "Redes Sociales", icono: "📱", descripcion: "Privacidad en redes sociales", consejos: 1 },
            { id: 4, nombre: "Redes WiFi", icono: "📶", descripcion: "Seguridad en conexiones WiFi", consejos: 1 },
            { id: 5, nombre: "Dispositivos", icono: "💻", descripcion: "Protección de dispositivos", consejos: 1 }
        ];

        let deleteTarget = null;
        let deleteType = null;

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            loadConsejos();
            loadCategorias();
            updateStats();
        });

        // Switch between tabs
        function switchTab(tabName) {
            // Ocultar todos los tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Mostrar tab seleccionado
            document.getElementById(tabName + '-tab').classList.add('active');
            event.target.classList.add('active');
        }

        // Load Consejos Table
        function loadConsejos() {
            const tbody = document.getElementById('consejosTableBody');
            
            if (consejos.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h3>No hay consejos registrados</h3>
                                <p>Comienza agregando tu primer consejo de seguridad</p>
                                <button class="btn btn-primary" onclick="openModal('createModal')" style="margin-top: 15px;">
                                    <i class="fas fa-plus"></i> Crear Primer Consejo
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = consejos.map(consejo => `
                <tr>
                    <td><strong>#${consejo.id}</strong></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 1.5em;">${consejo.icono}</span>
                            <strong>${consejo.titulo}</strong>
                        </div>
                    </td>
                    <td><span class="badge badge-${consejo.categoria}">${getCategoryName(consejo.categoria)}</span></td>
                    <td><span class="badge badge-${consejo.prioridad}">${getPriorityName(consejo.prioridad)}</span></td>
                    <td>${formatDate(consejo.fecha)}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-warning btn-sm" onclick="editConsejo(${consejo.id})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteConsejo(${consejo.id})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Load Categorias Table
        function loadCategorias() {
            const tbody = document.getElementById('categoriasTableBody');
            
            if (categorias.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-tags"></i>
                                <h3>No hay categorías registradas</h3>
                                <p>Crea categorías para organizar los consejos</p>
                                <button class="btn btn-primary" onclick="openModal('createCategoryModal')" style="margin-top: 15px;">
                                    <i class="fas fa-plus"></i> Crear Primera Categoría
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = categorias.map(cat => `
                <tr>
                    <td><strong>#${cat.id}</strong></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 1.5em;">${cat.icono}</span>
                            <div>
                                <strong>${cat.nombre}</strong>
                                ${cat.descripcion ? `<br><small style="color: var(--gray-medium);">${cat.descripcion}</small>` : ''}
                            </div>
                        </div>
                    </td>
                    <td><span style="font-size: 1.5em;">${cat.icono}</span></td>
                    <td><span class="badge badge-${cat.nombre.toLowerCase().replace(/\s+/g, '-')}">${cat.consejos} consejos</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-warning btn-sm" onclick="editCategory(${cat.id})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteCategory(${cat.id})" ${cat.consejos > 0 ? 'disabled title="No se puede eliminar con consejos asociados"' : ''}>
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Update Stats
        function updateStats() {
            document.getElementById('totalConsejos').textContent = consejos.length;
            document.getElementById('consejosActivos').textContent = consejos.length;
            document.getElementById('totalCategorias').textContent = categorias.length;
        }

        // Modal Functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Reset form if creating new
            if (modalId === 'createModal' && !event.target.dataset.edit) {
                document.getElementById('consejoForm').reset();
                document.getElementById('consejoId').value = '';
                document.getElementById('modalTitle').textContent = 'Nuevo Consejo de Seguridad';
            }
            
            if (modalId === 'createCategoryModal' && !event.target.dataset.edit) {
                document.getElementById('categoryForm').reset();
                document.getElementById('categoryId').value = '';
                document.getElementById('categoryModalTitle').textContent = 'Nueva Categoría';
            }
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Save Consejo
        function saveConsejo(event) {
            event.preventDefault();
            
            const id = document.getElementById('consejoId').value;
            const consejo = {
                id: id ? parseInt(id) : consejos.length + 1,
                titulo: document.getElementById('titulo').value,
                categoria: document.getElementById('categoria').value,
                prioridad: document.getElementById('prioridad').value,
                icono: document.getElementById('icono').value || '📌',
                descripcion: document.getElementById('descripcion').value,
                contenidoCompleto: document.getElementById('contenidoCompleto').value,
                fecha: new Date().toISOString().split('T')[0]
            };

            if (id) {
                // Editar existente
                const index = consejos.findIndex(c => c.id === parseInt(id));
                consejos[index] = consejo;
                showAlert('Consejo actualizado exitosamente', 'success');
            } else {
                // Crear nuevo
                consejos.push(consejo);
                showAlert('Consejo creado exitosamente', 'success');
            }

            closeModal('createModal');
            loadConsejos();
            updateStats();
            
            // En producción, aquí iría la llamada AJAX al servidor
            // saveConsejoToDatabase(consejo);
        }

        // Edit Consejo
        function editConsejo(id) {
            const consejo = consejos.find(c => c.id === id);
            if (!consejo) return;

            document.getElementById('consejoId').value = consejo.id;
            document.getElementById('titulo').value = consejo.titulo;
            document.getElementById('categoria').value = consejo.categoria;
            document.getElementById('prioridad').value = consejo.prioridad;
            document.getElementById('icono').value = consejo.icono;
            document.getElementById('descripcion').value = consejo.descripcion;
            document.getElementById('contenidoCompleto').value = consejo.contenidoCompleto;
            document.getElementById('modalTitle').textContent = 'Editar Consejo';

            openModal('createModal');
        }

        // Delete Consejo
        function deleteConsejo(id) {
            deleteTarget = id;
            deleteType = 'consejo';
            const consejo = consejos.find(c => c.id === id);
            document.getElementById('deleteMessage').textContent = `¿Estás seguro de eliminar el consejo "${consejo.titulo}"? Esta acción no se puede deshacer.`;
            openModal('deleteModal');
        }

        // Save Category
        function saveCategory(event) {
            event.preventDefault();
            
            const id = document.getElementById('categoryId').value;
            const category = {
                id: id ? parseInt(id) : categorias.length + 1,
                nombre: document.getElementById('categoryNombre').value,
                icono: document.getElementById('categoryIcono').value,
                descripcion: document.getElementById('categoryDescripcion').value,
                consejos: id ? categorias.find(c => c.id === parseInt(id)).consejos : 0
            };

            if (id) {
                // Editar existente
                const index = categorias.findIndex(c => c.id === parseInt(id));
                categorias[index] = category;
                showAlert('Categoría actualizada exitosamente', 'success');
            } else {
                // Crear nueva
                categorias.push(category);
                showAlert('Categoría creada exitosamente', 'success');
            }

            closeModal('createCategoryModal');
            loadCategorias();
            updateStats();
        }

        // Edit Category
        function editCategory(id) {
            const category = categorias.find(c => c.id === id);
            if (!category) return;

            document.getElementById('categoryId').value = category.id;
            document.getElementById('categoryNombre').value = category.nombre;
            document.getElementById('categoryIcono').value = category.icono;
            document.getElementById('categoryDescripcion').value = category.descripcion;
            document.getElementById('categoryModalTitle').textContent = 'Editar Categoría';

            openModal('createCategoryModal');
        }

        // Delete Category
        function deleteCategory(id) {
            const category = categorias.find(c => c.id === id);
            if (category.consejos > 0) {
                showAlert('No se puede eliminar una categoría con consejos asociados', 'danger');
                return;
            }
            
            deleteTarget = id;
            deleteType = 'category';
            document.getElementById('deleteMessage').textContent = `¿Estás seguro de eliminar la categoría "${category.nombre}"? Esta acción no se puede deshacer.`;
            openModal('deleteModal');
        }

        // Confirm Delete
        function confirmDelete() {
            if (deleteType === 'consejo') {
                consejos = consejos.filter(c => c.id !== deleteTarget);
                loadConsejos();
                showAlert('Consejo eliminado exitosamente', 'success');
            } else if (deleteType === 'category') {
                categorias = categorias.filter(c => c.id !== deleteTarget);
                loadCategorias();
                showAlert('Categoría eliminada exitosamente', 'success');
            }

            closeModal('deleteModal');
            updateStats();
            deleteTarget = null;
            deleteType = null;
        }

        // Filter Consejos
        function filterConsejos() {
            const searchTerm = document.getElementById('searchConsejos').value.toLowerCase();
            const rows = document.querySelectorAll('#consejosTableBody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }

        // Show Alert
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alertId = 'alert-' + Date.now();
            
            const alert = document.createElement('div');
            alert.id = alertId;
            alert.className = `alert alert-${type}`;
            alert.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;
            
            alertContainer.appendChild(alert);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                const alertElement = document.getElementById(alertId);
                if (alertElement) {
                    alertElement.style.animation = 'slideUp 0.3s reverse';
                    setTimeout(() => alertElement.remove(), 300);
                }
            }, 5000);
        }

        // Helper Functions
        function getCategoryName(categoria) {
            const names = {
                'contraseñas': 'Contraseñas',
                'phishing': 'Phishing',
                'redes-sociales': 'Redes Sociales',
                'wifi': 'Redes WiFi',
                'dispositivos': 'Dispositivos'
            };
            return names[categoria] || categoria;
        }

        function getPriorityName(prioridad) {
            const names = {
                'high': 'Alta',
                'medium': 'Media',
                'low': 'Baja'
            };
            return names[prioridad] || prioridad;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('es-MX', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        }

        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.active').forEach(modal => {
                    modal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                });
            }
        });

        // Prevenir envío de formulario con Enter excepto en textarea
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
<?php include __DIR__ . "/../../includes/footer.php"; ?>