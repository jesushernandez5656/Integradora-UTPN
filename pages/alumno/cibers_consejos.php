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
            --white: #ffffff;
            --shadow-light: 0 5px 15px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 8px 20px rgba(0, 0, 0, 0.1);
            --shadow-heavy: 0 15px 30px rgba(0, 131, 127, 0.3);
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
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
            line-height: 1.6;
        }

        /* Hero Section */
        .hero-banner {
            background: linear-gradient(135deg, var(--teal) 0%, #00837F 100%);
            color: var(--white);
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

        /* Animations */
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
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Main Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        /* Category Section */
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
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 50px;
        }

        .category-card {
            background: var(--white);
            border-radius: 15px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            border: 3px solid transparent;
            box-shadow: var(--shadow-light);
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
            box-shadow: var(--shadow-heavy);
        }

        .category-card.active {
            background: linear-gradient(135deg, var(--teal), #006b68);
            color: var(--white);
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

        /* Tips Grid */
        .tips-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 35px;
            animation: fadeInUp 1s ease;
        }

        .tip-card {
            background: var(--white);
            border-radius: 20px;
            padding: 35px;
            box-shadow: var(--shadow-medium);
            transition: var(--transition);
            border-left: 6px solid var(--gold);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .tip-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(0, 131, 127, 0.05) 0%, transparent 70%);
            transition: transform 0.6s;
            transform: scale(0);
        }

        .tip-card:hover::before {
            transform: scale(1);
        }

        .tip-card:hover {
            transform: translateY(-12px);
            box-shadow: var(--shadow-heavy);
            border-left-color: var(--teal);
        }

        .tip-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .tip-icon-circle {
            width: 60px;
            height: 60px;
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
            font-size: 1.3em;
            font-weight: 700;
            flex: 1;
        }

        .tip-card p {
            color: var(--gray-medium);
            margin-bottom: 20px;
        }

        .tip-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 2px solid var(--cream);
        }

        .category-badge {
            background: linear-gradient(135deg, var(--cream), var(--gray-light));
            color: var(--gold);
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9em;
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
            transition: gap 0.3s;
        }

        .tip-card:hover .read-more {
            gap: 12px;
        }

        /* Stats Section */
        .stats-section {
            background: var(--white);
            border-radius: 25px;
            padding: 50px;
            margin: 60px 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
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
            font-size: 1.1em;
            font-weight: 600;
        }

        /* Modal */
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
            animation: fadeIn 0.3s;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--white);
            border-radius: 25px;
            padding: 50px;
            max-width: 800px;
            max-height: 85vh;
            overflow-y: auto;
            position: relative;
            animation: slideInModal 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
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
            top: 25px;
            right: 25px;
            font-size: 35px;
            cursor: pointer;
            color: var(--gray-medium);
            transition: var(--transition);
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--cream);
        }

        .close-modal:hover {
            background: var(--teal);
            color: var(--white);
            transform: rotate(90deg);
        }

        .modal-content h2 {
            color: var(--teal);
            margin-bottom: 25px;
            font-size: 2em;
            border-bottom: 3px solid var(--gold);
            padding-bottom: 15px;
        }

        .modal-content h3 {
            color: var(--teal);
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .modal-content p {
            color: var(--gray-medium);
            margin-bottom: 15px;
        }

        .modal-content ul {
            margin-left: 25px;
            color: var(--gray-medium);
            line-height: 2;
        }

        .modal-content ul li {
            margin-bottom: 12px;
            position: relative;
            padding-left: 10px;
        }

        .modal-content ul li::before {
            content: '✓';
            position: absolute;
            left: -25px;
            color: var(--gold);
            font-weight: bold;
        }

        .example-box {
            background: var(--cream);
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            border-left: 4px solid var(--gold);
            font-family: 'Courier New', monospace;
            font-size: 0.95em;
        }

        /* Loading Animation */
        .loading {
            opacity: 0;
            animation: fadeIn 0.5s forwards;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-banner { 
                min-height: 250px;
                padding: 50px 20px;
            }
            .hero-banner h1 { 
                font-size: 1.8em;
            }
            .hero-banner p { 
                font-size: 1em;
            }
            .hero-icon { 
                font-size: 2.5em;
            }
            .hero-content {
                gap: 12px;
                padding: 15px;
            }
            .section-title {
                font-size: 1.6em;
            }
            .category-icon {
                font-size: 2em;
            }
            .category-card h3 {
                font-size: 1em;
            }
            .tip-card h3 {
                font-size: 1.2em;
            }
            .tip-icon-circle {
                width: 50px;
                height: 50px;
                font-size: 22px;
            }
            .stat-number {
                font-size: 2.5em;
            }
            .modal-content h2 {
                font-size: 1.6em;
            }
            .modal-content h3 {
                font-size: 1.2em;
            }
            .category-grid { 
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); 
            }
            .tips-container { 
                grid-template-columns: 1fr; 
            }
            .modal-content { 
                padding: 30px; 
                margin: 20px; 
            }
            .stats-grid { 
                grid-template-columns: 1fr; 
            }
        }

        @media (max-width: 480px) {
            .hero-banner {
                min-height: 230px;
                padding: 40px 15px;
            }
            .hero-banner h1 {
                font-size: 1.5em;
            }
            .hero-banner p {
                font-size: 0.95em;
            }
            .hero-icon {
                font-size: 2em;
            }
            .section-title {
                font-size: 1.4em;
            }
            .stat-number {
                font-size: 2em;
            }
            .tip-card {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="hero-content">
            <div class="hero-icon">🛡️</div>
            <h1>Consejos de Ciberseguridad</h1>
            <p>Protege tu información y navega de forma segura. Aprende las mejores prácticas para mantener tu seguridad digital y la de tu comunidad universitaria.</p>
        </div>
    </section>

    <!-- Main Container -->
    <div class="container">
        <!-- Category Section -->
        <section class="category-section">
            <h2 class="section-title">Explora por Categoría</h2>
            <div class="category-grid">
                <div class="category-card active" data-category="todos">
                    <span class="category-icon">🌐</span>
                    <h3>Todos</h3>
                </div>
                <div class="category-card" data-category="contraseñas">
                    <span class="category-icon">🔐</span>
                    <h3>Contraseñas</h3>
                </div>
                <div class="category-card" data-category="phishing">
                    <span class="category-icon">🎣</span>
                    <h3>Phishing</h3>
                </div>
                <div class="category-card" data-category="redes-sociales">
                    <span class="category-icon">📱</span>
                    <h3>Redes Sociales</h3>
                </div>
                <div class="category-card" data-category="wifi">
                    <span class="category-icon">📶</span>
                    <h3>Redes WiFi</h3>
                </div>
                <div class="category-card" data-category="dispositivos">
                    <span class="category-icon">💻</span>
                    <h3>Dispositivos</h3>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section loading">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">18</div>
                    <div class="stat-label">Consejos de Seguridad</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">6</div>
                    <div class="stat-label">Categorías</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Gratis y Accesible</div>
                </div>
            </div>
        </section>

        <!-- Tips Grid -->
        <h2 class="section-title">Guías y Recomendaciones</h2>
        <div class="tips-container" id="tipsGrid">
            <!-- Contraseñas -->
            <div class="tip-card loading" data-category="contraseñas" data-tip="password1">
                <div class="tip-header">
                    <div class="tip-icon-circle">🔐</div>
                    <h3>Contraseñas Fuertes</h3>
                </div>
                <p>Utiliza combinaciones de letras mayúsculas, minúsculas, números y símbolos especiales. Una contraseña segura debe tener al menos 12 caracteres y evitar palabras del diccionario.</p>
                <div class="tip-footer">
                    <span class="category-badge">Contraseñas</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <div class="tip-card loading" data-category="contraseñas" data-tip="password2">
                <div class="tip-header">
                    <div class="tip-icon-circle">🔄</div>
                    <h3>No Reutilices</h3>
                </div>
                <p>Cada cuenta debe tener una contraseña única. Si una cuenta es comprometida, las demás permanecerán seguras. La reutilización es una de las prácticas más peligrosas.</p>
                <div class="tip-footer">
                    <span class="category-badge">Contraseñas</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <div class="tip-card loading" data-category="contraseñas" data-tip="password3">
                <div class="tip-header">
                    <div class="tip-icon-circle">🗝️</div>
                    <h3>Gestores de Contraseñas</h3>
                </div>
                <p>Los gestores de contraseñas te ayudan a crear y almacenar contraseñas seguras de forma cifrada. Son herramientas esenciales para la seguridad moderna.</p>
                <div class="tip-footer">
                    <span class="category-badge">Contraseñas</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <!-- Phishing -->
            <div class="tip-card loading" data-category="phishing" data-tip="phishing1">
                <div class="tip-header">
                    <div class="tip-icon-circle">🎣</div>
                    <h3>Verifica el Remitente</h3>
                </div>
                <p>Antes de hacer clic en enlaces o descargar archivos, verifica cuidadosamente la dirección de correo del remitente. Los atacantes suelen usar direcciones similares.</p>
                <div class="tip-footer">
                    <span class="category-badge">Phishing</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <div class="tip-card loading" data-category="phishing" data-tip="phishing2">
                <div class="tip-header">
                    <div class="tip-icon-circle">🔗</div>
                    <h3>Enlaces Sospechosos</h3>
                </div>
                <p>Pasa el cursor sobre los enlaces antes de hacer clic para ver la URL real. No hagas clic en enlaces de correos inesperados o mensajes no solicitados.</p>
                <div class="tip-footer">
                    <span class="category-badge">Phishing</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <div class="tip-card loading" data-category="phishing" data-tip="phishing3">
                <div class="tip-header">
                    <div class="tip-icon-circle">⚠️</div>
                    <h3>Urgencia Falsa</h3>
                </div>
                <p>Los correos fraudulentos suelen crear sensación de urgencia para que actúes sin pensar. Tómate tu tiempo para verificar la autenticidad antes de tomar acción.</p>
                <div class="tip-footer">
                    <span class="category-badge">Phishing</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <!-- Redes Sociales -->
            <div class="tip-card loading" data-category="redes-sociales" data-tip="social1">
                <div class="tip-header">
                    <div class="tip-icon-circle">📱</div>
                    <h3>Configura tu Privacidad</h3>
                </div>
                <p>Revisa y ajusta regularmente la configuración de privacidad en tus redes sociales. Limita quién puede ver tu información personal y publicaciones.</p>
                <div class="tip-footer">
                    <span class="category-badge">Redes Sociales</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <div class="tip-card loading" data-category="redes-sociales" data-tip="social2">
                <div class="tip-header">
                    <div class="tip-icon-circle">🔒</div>
                    <h3>Piensa Antes de Publicar</h3>
                </div>
                <p>La información que compartes en redes sociales puede ser usada en tu contra. Evita publicar datos personales sensibles como ubicación en tiempo real o información financiera.</p>
                <div class="tip-footer">
                    <span class="category-badge">Redes Sociales</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <div class="tip-card loading" data-category="redes-sociales" data-tip="social3">
                <div class="tip-header">
                    <div class="tip-icon-circle">👥</div>
                    <h3>Solicitudes de Amistad</h3>
                </div>
                <p>No aceptes solicitudes de personas desconocidas sin verificar. Los perfiles falsos son comunes y pueden ser usados para robar información o realizar estafas.</p>
                <div class="tip-footer">
                    <span class="category-badge">Redes Sociales</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <!-- WiFi -->
            <div class="tip-card loading" data-category="wifi" data-tip="wifi1">
                <div class="tip-header">
                    <div class="tip-icon-circle">📶</div>
                    <h3>WiFi Públicas</h3>
                </div>
                <p>Las redes WiFi públicas son inseguras y fáciles de interceptar. Evita realizar transacciones bancarias o acceder a información sensible en estas redes.</p>
                <div class="tip-footer">
                    <span class="category-badge">Redes WiFi</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <div class="tip-card loading" data-category="wifi" data-tip="wifi2">
                <div class="tip-header">
                    <div class="tip-icon-circle">🛡️</div>
                    <h3>Usa VPN</h3>
                </div>
                <p>Si necesitas usar WiFi público, protege tu conexión con una VPN (Red Privada Virtual) para cifrar tu tráfico de internet y proteger tus datos.</p>
                <div class="tip-footer">
                    <span class="category-badge">Redes WiFi</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <div class="tip-card loading" data-category="wifi" data-tip="wifi3">
                <div class="tip-header">
                    <div class="tip-icon-circle">🔐</div>
                    <h3>Asegura tu Router</h3>
                </div>
                <p>Cambia la contraseña predeterminada de tu router y usa cifrado WPA3 o WPA2 para proteger tu red doméstica de accesos no autorizados.</p>
                <div class="tip-footer">
                    <span class="category-badge">Redes WiFi</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <!-- Dispositivos -->
            <div class="tip-card loading" data-category="dispositivos" data-tip="device1">
                <div class="tip-header">
                    <div class="tip-icon-circle">💻</div>
                    <h3>Actualiza tu Software</h3>
                </div>
                <p>Las actualizaciones incluyen parches de seguridad cruciales. Activa las actualizaciones automáticas cuando sea posible para mantenerte protegido.</p>
                <div class="tip-footer">
                    <span class="category-badge">Dispositivos</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <div class="tip-card loading" data-category="dispositivos" data-tip="device2">
                <div class="tip-header">
                    <div class="tip-icon-circle">🦠</div>
                    <h3>Antivirus Confiable</h3>
                </div>
                <p>Instala y mantén actualizado un software antivirus de confianza. Realiza escaneos regulares de tu sistema para detectar amenazas.</p>
                <div class="tip-footer">
                    <span class="category-badge">Dispositivos</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <div class="tip-card loading" data-category="dispositivos" data-tip="device3">
                <div class="tip-header">
                    <div class="tip-icon-circle">💾</div>
                    <h3>Copias de Seguridad</h3>
                </div>
                <p>Haz backups regulares de tu información importante en un disco externo o en la nube cifrada. Esto te protege contra ransomware y pérdida de datos.</p>
                <div class="tip-footer">
                    <span class="category-badge">Dispositivos</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <!-- Navegación Segura -->
            <div class="tip-card loading" data-category="dispositivos" data-tip="browse1">
                <div class="tip-header">
                    <div class="tip-icon-circle">🌐</div>
                    <h3>Navegación Segura</h3>
                </div>
                <p>Usa navegadores actualizados y verifica que los sitios web usen HTTPS (candado verde). Evita descargar software de fuentes no confiables.</p>
                <div class="tip-footer">
                    <span class="category-badge">Dispositivos</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <!-- Autenticación -->
            <div class="tip-card loading" data-category="contraseñas" data-tip="auth1">
                <div class="tip-header">
                    <div class="tip-icon-circle">🔑</div>
                    <h3>Autenticación de Dos Factores</h3>
                </div>
                <p>Activa la autenticación de dos factores (2FA) en todas tus cuentas importantes. Añade una capa adicional de seguridad más allá de la contraseña.</p>
                <div class="tip-footer">
                    <span class="category-badge">Contraseñas</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <!-- Email Seguro -->
            <div class="tip-card loading" data-category="phishing" data-tip="email1">
                <div class="tip-header">
                    <div class="tip-icon-circle">📧</div>
                    <h3>Correo Seguro</h3>
                </div>
                <p>No compartas información confidencial por correo electrónico no cifrado. Usa servicios de correo seguros y verifica siempre el remitente.</p>
                <div class="tip-footer">
                    <span class="category-badge">Phishing</span>
                    <span class="read-more">Leer más <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="tipModal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div id="modalBody"></div>
        </div>
    </div>

    <script>
        // Datos detallados de los consejos
        const tipDetails = {
            password1: {
                title: "Creación de Contraseñas Fuertes",
                icon: "🔐",
                content: `
                    <h3>¿Por qué son importantes las contraseñas fuertes?</h3>
                    <p>Una contraseña fuerte es tu primera línea de defensa contra ataques cibernéticos. Los atacantes utilizan herramientas automatizadas que pueden probar millones de combinaciones por segundo.</p>
                    
                    <h3>Características de una contraseña segura:</h3>
                    <ul>
                        <li>Mínimo 12 caracteres de longitud (ideal 16 o más)</li>
                        <li>Combinación de letras mayúsculas (A-Z)</li>
                        <li>Letras minúsculas (a-z)</li>
                        <li>Números (0-9)</li>
                        <li>Símbolos especiales (!@#$%^&*)</li>
                        <li>Evitar palabras del diccionario</li>
                        <li>No usar información personal (nombres, fechas de nacimiento)</li>
                        <li>Evitar secuencias obvias (123456, qwerty)</li>
                    </ul>

                    <h3>Ejemplo de contraseña fuerte:</h3>
                    <div class="example-box">M!C0ntr@s3ñ4$egur@2025#UTPN</div>

                    <h3>Método de la frase de contraseña:</h3>
                    <p>Crea una frase memorable y convierte cada palabra en su primera letra, agregando números y símbolos:</p>
                    <div class="example-box">
                        "Me gusta estudiar en la UTPN desde 2020"<br>
                        → Mge3!Ud$2020
                    </div>
                `
            },
            password2: {
                title: "No Reutilices tus Contraseñas",
                icon: "🔄",
                content: `
                    <h3>¿Por qué es peligroso reutilizar contraseñas?</h3>
                    <p>Cuando reutilizas una contraseña en múltiples sitios, si uno de esos sitios es comprometido, todos tus demás datos quedan vulnerables. Los atacantes prueban contraseñas filtradas en otros servicios.</p>

                    <h3>Riesgos de la reutilización:</h3>
                    <ul>
                        <li>Efecto dominó: Una brecha compromete todas tus cuentas</li>
                        <li>Ataques de relleno de credenciales (credential stuffing)</li>
                        <li>Pérdida simultánea de múltiples servicios</li>
                        <li>Robo de identidad en múltiples plataformas</li>
                        <li>Dificultad para identificar la fuente de la brecha</li>
                    </ul>

                    <h3>Mejores prácticas:</h3>
                    <ul>
                        <li>Una contraseña única para cada cuenta importante</li>
                        <li>Usa un gestor de contraseñas para recordarlas</li>
                        <li>Categoriza cuentas por nivel de importancia</li>
                        <li>Cambia contraseñas si sospechas una brecha</li>
                        <li>Monitorea alertas de brechas de seguridad</li>
                    </ul>

                    <h3>¿Cómo saber si tu contraseña fue filtrada?</h3>
                    <p>Usa servicios como "Have I Been Pwned" para verificar si tu correo o contraseñas aparecen en brechas de seguridad conocidas.</p>
                `
            },
            password3: {
                title: "Gestores de Contraseñas",
                icon: "🗝️",
                content: `
                    <h3>¿Qué es un gestor de contraseñas?</h3>
                    <p>Un gestor de contraseñas es una aplicación que almacena de forma segura y cifrada todas tus contraseñas en una "bóveda" digital protegida por una contraseña maestra.</p>

                    <h3>Ventajas de usar un gestor:</h3>
                    <ul>
                        <li>Solo necesitas recordar una contraseña maestra</li>
                        <li>Genera contraseñas aleatorias y seguras automáticamente</li>
                        <li>Autocompletar formularios de inicio de sesión</li>
                        <li>Sincronización entre dispositivos</li>
                        <li>Alertas de contraseñas débiles o reutilizadas</li>
                        <li>Notificaciones de brechas de seguridad</li>
                        <li>Almacenamiento seguro de otros datos sensibles</li>
                    </ul>

                    <h3>Gestores populares recomendados:</h3>
                    <ul>
                        <li><strong>Bitwarden</strong> - Open source y gratuito</li>
                        <li><strong>1Password</strong> - Excelente interfaz y funciones</li>
                        <li><strong>LastPass</strong> - Plan gratuito disponible</li>
                        <li><strong>Dashlane</strong> - Funciones avanzadas de seguridad</li>
                        <li><strong>KeePass</strong> - Completamente offline y gratuito</li>
                    </ul>

                    <h3>Cómo elegir tu contraseña maestra:</h3>
                    <ul>
                        <li>Debe ser la más fuerte que hayas creado</li>
                        <li>Fácil de recordar pero difícil de adivinar</li>
                        <li>Nunca la compartas ni la escribas</li>
                        <li>Considera usar una frase larga de 4-5 palabras aleatorias</li>
                    </ul>
                `
            }
        };

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            // Animación de carga de tarjetas
            const loadingCards = document.querySelectorAll('.loading');
            loadingCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                }, index * 50);
            });

            // Filtrado por categorías
            const categoryCards = document.querySelectorAll('.category-card');
            const tipCards = document.querySelectorAll('.tip-card');

            categoryCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Remover active de todos
                    categoryCards.forEach(c => c.classList.remove('active'));
                    // Agregar active al clickeado
                    this.classList.add('active');

                    const category = this.dataset.category;

                    // Filtrar tarjetas
                    tipCards.forEach(tip => {
                        if (category === 'todos' || tip.dataset.category === category) {
                            tip.style.display = 'block';
                            setTimeout(() => {
                                tip.style.opacity = '1';
                                tip.style.transform = 'translateY(0)';
                            }, 10);
                        } else {
                            tip.style.opacity = '0';
                            tip.style.transform = 'translateY(20px)';
                            setTimeout(() => {
                                tip.style.display = 'none';
                            }, 300);
                        }
                    });
                });
            });

            // Modal
            const modal = document.getElementById('tipModal');
            const modalBody = document.getElementById('modalBody');
            const closeBtn = document.querySelector('.close-modal');

            tipCards.forEach(card => {
                card.addEventListener('click', function() {
                    const tipId = this.dataset.tip;
                    
                    if (tipDetails[tipId]) {
                        const tip = tipDetails[tipId];
                        modalBody.innerHTML = `
                            <div style="text-align: center; font-size: 4em; margin-bottom: 20px;">
                                ${tip.icon}
                            </div>
                            <h2>${tip.title}</h2>
                            ${tip.content}
                        `;
                        modal.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    }
                });
            });

            closeBtn.addEventListener('click', function() {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });

            // Cerrar modal con tecla ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.classList.contains('active')) {
                    modal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        });
    </script>
</body>
</html>
<?php include __DIR__ . "/../../includes/footer.php"; ?>