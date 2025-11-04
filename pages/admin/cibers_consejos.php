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

        .admin-header {
            background: linear-gradient(135deg, var(--teal) 0%, #006b68 100%);
            color: white;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .admin-header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .admin-title {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 250px;
        }

        .admin-title i {
            font-size: 1.8em;
            color: var(--gold);
        }

        .admin-title h1 {
            font-size: 1.5em;
            font-weight: 700;
            line-height: 1.2;
        }

        .admin-title p {
            font-size: 0.9em;
            opacity: 0.9;
            margin-top: 5px;
        }

        .admin-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .admin-container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 0 15px;
        }

        .tabs-nav {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            background: white;
            padding: 12px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 10px 20px;
            border: 2px solid var(--gray-light);
            background: white;
            color: var(--gray-medium);
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.95em;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
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

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 0.95em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            white-space: nowrap;
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
            padding: 6px 12px;
            font-size: 0.85em;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--cream);
            flex-wrap: wrap;
            gap: 15px;
        }

        .card-title {
            color: var(--teal);
            font-size: 1.3em;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            min-width: 600px;
        }

        thead {
            background: var(--teal);
            color: white;
        }

        th, td {
            padding: 12px 10px;
            text-align: left;
            border-bottom: 1px solid var(--gray-light);
        }

        th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
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
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .badge-contrasenas { background: #e3f2fd; color: #1976d2; }
        .badge-phishing { background: #fff3e0; color: #f57c00; }
        .badge-redes-sociales { background: #f3e5f5; color: #7b1fa2; }
        .badge-wifi { background: #e8f5e9; color: #388e3c; }
        .badge-dispositivos { background: #fce4ec; color: #c2185b; }

        .badge-high { background: #ffebee; color: #c62828; }
        .badge-medium { background: #fff3e0; color: #f57c00; }
        .badge-low { background: #e8f5e9; color: #388e3c; }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: var(--gray-medium);
            font-weight: 600;
            font-size: 0.9em;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid var(--gray-light);
            border-radius: 8px;
            font-size: 0.95em;
            transition: border-color 0.3s;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--teal);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        select.form-control {
            cursor: pointer;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

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
            padding: 15px;
            overflow-y: auto;
        }

        .modal.active {
            display: flex;
        }

        .modal-dialog {
            background: white;
            border-radius: 15px;
            max-width: 800px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideInModal 0.3s;
            margin: auto;
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
            padding: 20px;
            border-bottom: 2px solid var(--cream);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .modal-header h2 {
            color: var(--teal);
            font-size: 1.4em;
            margin: 0;
            word-break: break-word;
        }

        .modal-close {
            font-size: 24px;
            cursor: pointer;
            color: var(--gray-medium);
            background: var(--cream);
            width: 35px;
            height: 35px;
            min-width: 35px;
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
            padding: 20px;
        }

        .modal-footer {
            padding: 15px 20px;
            border-top: 2px solid var(--cream);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            min-width: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .stat-icon.blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-icon.green { background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%); }
        .stat-icon.orange { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-icon.purple { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

        .stat-info h3 {
            font-size: 1.6em;
            color: var(--teal);
            margin-bottom: 3px;
        }

        .stat-info p {
            color: var(--gray-medium);
            font-size: 0.85em;
        }

        .search-bar {
            position: relative;
            margin-bottom: 15px;
            width: 100%;
            max-width: 300px;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 40px 10px 12px;
            border: 2px solid var(--gray-light);
            border-radius: 8px;
            font-size: 0.95em;
        }

        .search-bar i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-medium);
        }

        .action-buttons {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--gray-medium);
        }

        .empty-state i {
            font-size: 3em;
            color: var(--gray-light);
            margin-bottom: 15px;
        }

        .empty-state h3 {
            font-size: 1.3em;
            margin-bottom: 8px;
        }

        /* Responsive Improvements */
        @media (max-width: 992px) {
            .admin-header {
                padding: 15px;
            }

            .admin-title h1 {
                font-size: 1.3em;
            }

            .admin-title p {
                font-size: 0.85em;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }

            .stat-info h3 {
                font-size: 1.4em;
            }

            .stat-info p {
                font-size: 0.8em;
            }
        }

        @media (max-width: 768px) {
            .admin-header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-title {
                min-width: 100%;
            }

            .admin-title i {
                font-size: 1.5em;
            }

            .admin-title h1 {
                font-size: 1.2em;
            }

            .admin-title p {
                display: none;
            }

            .admin-actions {
                width: 100%;
                justify-content: stretch;
            }

            .admin-actions .btn {
                flex: 1;
                justify-content: center;
                padding: 10px;
                font-size: 0.9em;
            }

            .tabs-nav {
                flex-direction: column;
                padding: 10px;
            }

            .tab-btn {
                width: 100%;
                justify-content: center;
                padding: 12px;
            }

            .card {
                padding: 15px;
            }

            .card-header {
                flex-direction: column;
                align-items: stretch;
            }

            .card-title {
                font-size: 1.1em;
            }

            .search-bar {
                max-width: 100%;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 0.85em;
                min-width: 550px;
            }

            th, td {
                padding: 8px 6px;
            }

            .modal-dialog {
                margin: 10px;
                max-height: 95vh;
            }

            .modal-header {
                padding: 15px;
            }

            .modal-header h2 {
                font-size: 1.2em;
            }

            .modal-body {
                padding: 15px;
            }

            .modal-footer {
                padding: 12px 15px;
                flex-direction: column-reverse;
            }

            .modal-footer .btn {
                width: 100%;
                justify-content: center;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .stat-card {
                padding: 15px;
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .stat-info h3 {
                font-size: 1.3em;
            }

            .stat-info p {
                font-size: 0.75em;
            }
        }

        @media (max-width: 480px) {
            .admin-header {
                padding: 12px;
            }

            .admin-title {
                gap: 8px;
            }

            .admin-title i {
                font-size: 1.3em;
            }

            .admin-title h1 {
                font-size: 1.1em;
            }

            .admin-actions .btn {
                font-size: 0.85em;
                padding: 8px;
            }

            .btn {
                padding: 8px 15px;
                font-size: 0.9em;
            }

            .btn-sm {
                padding: 5px 10px;
                font-size: 0.8em;
            }

            .card {
                padding: 12px;
            }

            .card-title {
                font-size: 1em;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                flex-direction: row;
                justify-content: flex-start;
                text-align: left;
            }

            table {
                font-size: 0.8em;
            }

            .badge {
                font-size: 0.7em;
                padding: 3px 8px;
            }

            .form-control {
                font-size: 0.9em;
                padding: 8px 10px;
            }

            textarea.form-control {
                min-height: 80px;
            }

            .modal-header h2 {
                font-size: 1.1em;
            }

            .modal-close {
                width: 30px;
                height: 30px;
                min-width: 30px;
                font-size: 20px;
            }
        }

        /* Loading Animation */
        .loading {
            opacity: 0;
            animation: fadeIn 0.5s forwards;
        }

        /* Touch-friendly improvements */
        @media (hover: none) and (pointer: coarse) {
            .btn, .tab-btn, .category-card {
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
            }

            .modal {
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Landscape mobile adjustments */
        @media (max-width: 768px) and (orientation: landscape) {
            .modal-dialog {
                max-height: 85vh;
            }

            .admin-header {
                padding: 10px 15px;
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
                    <p>Panel de gestión de contenido de ciberseguridad</p>
                </div>
            </div>
            <div class="admin-actions">
                <button class="btn btn-primary" onclick="openModal('createModal')">
                    <i class="fas fa-plus"></i> Nuevo
                </button>
                <a href="../../pages/alumno/cibers_consejos.php" class="btn btn-secondary">
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
                    <h3 id="totalConsejos">0</h3>
                    <p>Total Consejos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3 id="consejosActivos">0</h3>
                    <p>Activos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-folder"></i>
                </div>
                <div class="stat-info">
                    <h3 id="totalCategorias">0</h3>
                    <p>Categorías</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>Hoy</h3>
                    <p>Actualización</p>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <nav class="tabs-nav">
            <button class="tab-btn active" onclick="switchTab('consejos')">
                <i class="fas fa-list"></i> Consejos
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
                    <div class="search-bar">
                        <input type="text" id="searchConsejos" placeholder="Buscar..." onkeyup="filterConsejos()">
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
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px;">
                                    <i class="fas fa-spinner fa-spin" style="font-size: 2em; color: var(--teal);"></i>
                                    <p style="margin-top: 10px;">Cargando...</p>
                                </td>
                            </tr>
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
                        <i class="fas fa-tags"></i> Categorías
                    </h2>
                    <button class="btn btn-primary" onclick="openModal('createCategoryModal')">
                        <i class="fas fa-plus"></i> Nueva
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
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px;">
                                    <i class="fas fa-spinner fa-spin" style="font-size: 2em; color: var(--teal);"></i>
                                    <p style="margin-top: 10px;">Cargando...</p>
                                </td>
                            </tr>
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
                <h2 id="modalTitle">Nuevo Consejo</h2>
                <button class="modal-close" onclick="closeModal('createModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="consejoForm" onsubmit="saveConsejo(event)">
                    <input type="hidden" id="consejoId">
                    
                    <div class="form-group">
                        <label for="titulo">Título *</label>
                        <input type="text" id="titulo" class="form-control" required placeholder="Ej: Contraseñas Fuertes">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="categoria">Categoría *</label>
                            <select id="categoria" class="form-control" required>
                                <option value="">Seleccionar</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="prioridad">Prioridad *</label>
                            <select id="prioridad" class="form-control" required>
                                <option value="">Seleccionar</option>
                                <option value="high">Alta</option>
                                <option value="medium">Media</option>
                                <option value="low">Baja</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="icono">Ícono</label>
                        <input type="text" id="icono" class="form-control" placeholder="Ej: 🔐" maxlength="2">
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción Corta *</label>
                        <textarea id="descripcion" class="form-control" required placeholder="Descripción breve..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="contenidoCompleto">Contenido Completo *</label>
                        <textarea id="contenidoCompleto" class="form-control" required placeholder="Contenido detallado..." style="min-height: 150px;"></textarea>
                        <small style="color: var(--gray-medium); display: block; margin-top: 5px;">
                            Puedes usar HTML: &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt;
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createModal')">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit" form="consejoForm" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar
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
                        <label for="categoryNombre">Nombre *</label>
                        <input type="text" id="categoryNombre" class="form-control" required placeholder="Ej: Seguridad en Redes">
                    </div>

                    <div class="form-group">
                        <label for="categoryIcono">Ícono *</label>
                        <input type="text" id="categoryIcono" class="form-control" required placeholder="Ej: 🌐" maxlength="2">
                    </div>

                    <div class="form-group">
                        <label for="categoryDescripcion">Descripción</label>
                        <textarea id="categoryDescripcion" class="form-control" placeholder="Descripción opcional..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createCategoryModal')">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit" form="categoryForm" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal: Confirmar Eliminación -->
    <div id="deleteModal" class="modal">
        <div class="modal-dialog" style="max-width: 500px;">
            <div class="modal-header">
                <h2>Confirmar</h2>
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

    <!-- JavaScript externo -->
    <script src="/INTEGRADORA-UTPN/assets/js/admin-consejos.js"></script>
</body>
</html>
<?php include __DIR__ . "/../../includes/footer.php"; ?>