<?php include __DIR__ . "/../../includes/header.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consejos de Ciberseguridad - UTPN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --teal: #00837F;
            --gold: #AE874C;
            --cream: #EDE5D6;
            --gray-medium: #7E8080;
            --gray-light: #D0D1D1;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, var(--cream) 0%, var(--gray-light) 100%);
            min-height: 100vh;
        }

        .hero-banner {
            background: linear-gradient(135deg, var(--teal) 0%, #00837F 100%);
            color: white;
            padding: 60px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            min-height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            padding: 20px;
        }

        .hero-banner h1 {
            font-size: 2.2em;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.8s ease;
            line-height: 1.2;
        }

        .hero-banner p {
            font-size: 1.1em;
            line-height: 1.6;
            margin: 0;
            opacity: 0.95;
            animation: slideUp 0.8s ease;
            max-width: 800px;
        }

        .hero-icon {
            font-size: 3em;
            margin: 0;
            animation: pulse 2s ease-in-out infinite;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.3));
            line-height: 1;
        }

        @keyframes slideDown {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from { 
                transform: translateY(30px); 
                opacity: 0; 
            }
            to { 
                transform: translateY(0); 
                opacity: 1; 
            }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        .category-section {
            margin-bottom: 50px;
            animation: fadeIn 1s ease;
        }

        .section-title {
            text-align: center;
            color: var(--teal);
            font-size: 2em;
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--gold), var(--teal));
            border-radius: 2px;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 50px;
        }

        .category-card {
            background: white;
            border-radius: 15px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 3px solid transparent;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(174, 135, 76, 0.2), transparent);
            transition: left 0.5s;
        }

        .category-card:hover::before {
            left: 100%;
        }

        .category-card:hover {
            transform: translateY(-10px) scale(1.05);
            border-color: var(--teal);
            box-shadow: 0 15px 30px rgba(0, 131, 127, 0.3);
        }

        .category-card.active {
            background: linear-gradient(135deg, var(--teal), #006b68);
            color: white;
            border-color: var(--gold);
            transform: translateY(-5px);
        }

        .category-icon {
            font-size: 2.5em;
            margin-bottom: 15px;
            display: block;
            transition: transform 0.3s;
        }

        .category-card:hover .category-icon {
            transform: rotateY(360deg);
        }

        .category-card h3 {
            font-size: 1.1em;
            color: inherit;
            font-weight: 600;
        }

        .tips-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            animation: fadeInUp 1s ease;
        }

        .tip-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            border-left: 6px solid var(--gold);
            position: relative;
            overflow: hidden;
        }

        .tip-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 15px 40px rgba(0, 131, 127, 0.2);
            border-left-color: var(--teal);
        }

        .tip-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .tip-icon-circle {
            width: 60px;
            height: 60px;
            min-width: 60px;
            background: linear-gradient(135deg, var(--teal), var(--gold));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
            box-shadow: 0 5px 15px rgba(0, 131, 127, 0.3);
            transition: transform 0.3s;
        }

        .tip-card:hover .tip-icon-circle {
            transform: rotate(360deg);
        }

        .tip-card h3 {
            color: var(--teal);
            font-size: 1.2em;
            font-weight: 700;
            flex: 1;
            line-height: 1.3;
        }

        .tip-card p {
            color: var(--gray-medium);
            line-height: 1.7;
            font-size: 0.95em;
            margin-bottom: 20px;
        }

        .tip-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid var(--cream);
            flex-wrap: wrap;
            gap: 10px;
        }

        .category-badge {
            background: linear-gradient(135deg, var(--cream), var(--gray-light));
            color: var(--gold);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .read-more {
            color: var(--teal);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.95em;
        }

        .read-more:hover {
            gap: 12px;
            color: #006b68;
        }

        .stats-section {
            background: white;
            border-radius: 25px;
            padding: 40px;
            margin: 50px 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .stat-item {
            position: relative;
        }

        .stat-number {
            font-size: 3em;
            font-weight: 800;
            background: linear-gradient(135deg, var(--teal), var(--gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .stat-label {
            color: var(--gray-medium);
            font-size: 1em;
            font-weight: 600;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow-y: auto;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 25px;
            padding: 40px;
            max-width: 800px;
            width: 100%;
            max-height: 85vh;
            overflow-y: auto;
            position: relative;
            animation: slideInModal 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            margin: auto;
        }

        .modal-content::-webkit-scrollbar {
            width: 8px;
        }

        .modal-content::-webkit-scrollbar-track {
            background: var(--cream);
            border-radius: 10px;
        }

        .modal-content::-webkit-scrollbar-thumb {
            background: var(--teal);
            border-radius: 10px;
        }

        .modal-content::-webkit-scrollbar-thumb:hover {
            background: var(--gold);
        }

        @keyframes slideInModal {
            from {
                transform: scale(0.8) translateY(-50px);
                opacity: 0;
            }
            to {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 30px;
            cursor: pointer;
            color: var(--gray-medium);
            transition: all 0.3s;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--cream);
            border: none;
            z-index: 10;
        }

        .close-modal:hover {
            background: var(--teal);
            color: white;
            transform: rotate(90deg);
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
            margin-right: 8px;
        }

        .badge-high { background: #ffebee; color: #c62828; }
        .badge-medium { background: #fff3e0; color: #f57c00; }
        .badge-low { background: #e8f5e9; color: #388e3c; }

        .tip-card {
            transition: opacity 0.5s ease;
        }

        @media (max-width: 768px) {
            .hero-banner { 
                min-height: 250px;
                padding: 40px 15px;
            }
            
            .hero-banner h1 { 
                font-size: 1.8em;
                line-height: 1.3;
            }
            
            .hero-banner p { 
                font-size: 1em;
                line-height: 1.5;
            }
            
            .hero-icon { 
                font-size: 2.5em;
            }
            
            .hero-content {
                gap: 12px;
                padding: 15px;
            }

            .container {
                padding: 30px 15px;
            }
            
            .section-title {
                font-size: 1.6em;
                margin-bottom: 30px;
            }

            .category-grid { 
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                gap: 15px;
            }
            
            .category-card {
                padding: 20px 15px;
            }
            
            .category-icon {
                font-size: 2em;
            }
            
            .category-card h3 {
                font-size: 0.95em;
            }

            .tips-container { 
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .tip-card {
                padding: 25px;
            }
            
            .tip-card h3 {
                font-size: 1.1em;
            }

            .tip-header {
                gap: 12px;
            }
            
            .tip-icon-circle {
                width: 50px;
                height: 50px;
                min-width: 50px;
                font-size: 22px;
            }

            .tip-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .stats-section {
                padding: 30px 20px;
                margin: 30px 0;
            }

            .stats-grid { 
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
            
            .stat-number {
                font-size: 2.5em;
            }

            .stat-label {
                font-size: 0.9em;
            }

            .modal {
                padding: 10px;
                align-items: flex-start;
            }
            
            .modal-content { 
                padding: 30px 25px;
                margin: 20px 10px;
                max-height: 90vh;
            }

            .close-modal {
                top: 15px;
                right: 15px;
                width: 35px;
                height: 35px;
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .category-grid { 
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
                gap: 10px;
            }
            
            .category-card {
                padding: 15px 10px;
            }
            
            .category-icon {
                font-size: 1.8em;
                margin-bottom: 5px;
            }
            
            .category-card h3 {
                font-size: 0.85em;
            }

            .stats-grid { 
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .stat-number {
                font-size: 2em;
            }

            .modal-content { 
                padding: 20px;
                margin: 10px;
            }
        }

        @media (hover: none) and (pointer: coarse) {
            .category-card, .tip-card, .read-more {
                -webkit-tap-highlight-color: transparent;
            }
        }
    </style>
</head>
<body>
    <section class="hero-banner">
        <div class="hero-content">
            <div class="hero-icon">🛡️</div>
            <h1>Consejos de Ciberseguridad</h1>
            <p>Protege tu información y navega de forma segura. Aprende las mejores prácticas para mantener tu seguridad digital y la de tu comunidad universitaria.</p>
        </div>
    </section>

    <div class="container">
        <section class="category-section">
            <h2 class="section-title">Explora por Categoría</h2>
            <div class="category-grid">
                <div class="category-card active" data-category="todos">
                    <span class="category-icon">🌐</span>
                    <h3>Todos</h3>
                </div>
            </div>
        </section>

        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" id="totalConsejos">0</div>
                    <div class="stat-label">Consejos de Seguridad</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="totalCategorias">0</div>
                    <div class="stat-label">Categorías</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Gratis y Accesible</div>
                </div>
            </div>
        </section>

        <h2 class="section-title">Guías y Recomendaciones</h2>
        <div class="tips-container" id="consejosContainer">
            <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 4em; color: var(--teal); margin-bottom: 20px;"></i>
                <h3 style="color: var(--gray-medium);">Cargando consejos...</h3>
                <p style="color: var(--gray-medium);">Por favor espera un momento</p>
            </div>
        </div>
    </div>

    <div class="modal" id="tipModal">
        <div class="modal-content">
            <button class="close-modal">&times;</button>
            <div id="modalBody"></div>
        </div>
    </div>

    <script src="/INTEGRADORA-UTPN/assets/js/alumno-consejos.js"></script>
</body>
</html>
<?php include __DIR__ . "/../../includes/footer.php"; ?>