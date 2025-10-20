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

        /* Hero Section */
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

        

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
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

        /* Main Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        /* Category Navigation */
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

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

        /* Tips Grid */
        .tips-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 35px;
            animation: fadeInUp 1s ease;
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

        .tip-card {
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
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
            box-shadow: 0 15px 40px rgba(0, 131, 127, 0.2);
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
            line-height: 1.8;
            font-size: 1em;
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
            background: white;
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
            background: white;
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
            transition: all 0.3s;
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
            color: white;
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
            line-height: 1.8;
            font-size: 1em;
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

        /* Responsive */
        @media (max-width: 768px) {
            .hero-banner { 
                min-height: 250px;
                padding: 50px 20px;
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
            .category-grid { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); }
            .tips-container { grid-template-columns: 1fr; }
            .modal-content { padding: 30px; margin: 20px; }
            .stats-grid { grid-template-columns: 1fr; }
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
        }

        /* Loading Animation */
        .loading {
            opacity: 0;
            animation: fadeIn 0.5s forwards;
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
            },
            phishing1: {
                title: "Verificación del Remitente",
                icon: "🎣",
                content: `
                    <h3>¿Qué es el Phishing?</h3>
                    <p>El phishing es una técnica de engaño donde los atacantes se hacen pasar por entidades legítimas para robar información personal, contraseñas o datos financieros.</p>

                    <h3>Señales de un correo fraudulento:</h3>
                    <ul>
                        <li>Dirección de correo sospechosa o ligeramente diferente</li>
                        <li>Errores ortográficos y gramaticales</li>
                        <li>Saludos genéricos ("Estimado usuario")</li>
                        <li>Solicitudes urgentes de información personal</li>
                        <li>Amenazas de cerrar tu cuenta</li>
                        <li>Ofertas demasiado buenas para ser verdad</li>
                        <li>Enlaces que no coinciden con el texto mostrado</li>
                    </ul>

                    <h3>Cómo verificar el remitente:</h3>
                    <ul>
                        <li>Revisa cuidadosamente la dirección de correo completa</li>
                        <li>Busca dominios similares pero incorrectos (amaz0n.com vs amazon.com)</li>
                        <li>Verifica que el dominio coincida con la organización oficial</li>
                        <li>Contacta directamente a la organización por canales oficiales</li>
                        <li>Desconfía de correos no solicitados</li>
                    </ul>

                    <h3>Ejemplo de correo fraudulento:</h3>
                    <div class="example-box">
                        De: seguridad@micr0soft-support.com<br>
                        ⚠️ El dominio correcto es microsoft.com, no micr0soft-support.com
                    </div>

                    <h3>¿Qué hacer si sospechas de phishing?</h3>
                    <ul>
                        <li>NO hagas clic en ningún enlace</li>
                        <li>NO descargues archivos adjuntos</li>
                        <li>NO proporciones información personal</li>
                        <li>Reporta el correo como spam/phishing</li>
                        <li>Elimina el mensaje</li>
                        <li>Informa a tu departamento de TI si es en el trabajo</li>
                    </ul>
                `
            },
            phishing2: {
                title: "Detección de Enlaces Sospechosos",
                icon: "🔗",
                content: `
                    <h3>¿Cómo funcionan los enlaces maliciosos?</h3>
                    <p>Los atacantes disfrazan enlaces maliciosos para que parezcan legítimos. Al hacer clic, puedes ser redirigido a sitios falsos que roban tus credenciales o descargan malware.</p>

                    <h3>Técnicas para verificar enlaces:</h3>
                    <ul>
                        <li><strong>Hover/Pasar el mouse:</strong> Coloca el cursor sobre el enlace sin hacer clic para ver la URL real</li>
                        <li><strong>Verifica el dominio:</strong> Asegúrate que sea el oficial (no variaciones)</li>
                        <li><strong>Busca HTTPS:</strong> Los sitios seguros usan "https://" con el candado</li>
                        <li><strong>Acortadores de URL:</strong> Desconfía de bit.ly, tinyurl sin contexto</li>
                        <li><strong>URLs sospechosas:</strong> Números extraños, guiones excesivos, dominios raros</li>
                    </ul>

                    <h3>Ejemplos de enlaces engañosos:</h3>
                    <div class="example-box">
                        ❌ http://paypa1.com-security-update.xyz<br>
                        ❌ https://amaz0n.verification-required.com<br>
                        ❌ http://banco-santander.secure-login.tk<br>
                        ✅ https://www.paypal.com<br>
                        ✅ https://www.amazon.com
                    </div>

                    <h3>Características de URLs maliciosas:</h3>
                    <ul>
                        <li>Uso de números en lugar de letras (0 por O, 1 por I)</li>
                        <li>Subdominios sospechosos antes del dominio real</li>
                        <li>Dominios de nivel superior inusuales (.tk, .xyz, .top)</li>
                        <li>Palabras como "secure", "verify", "update" en la URL</li>
                        <li>URLs extremadamente largas o complejas</li>
                    </ul>

                    <h3>Qué hacer con enlaces sospechosos:</h3>
                    <ul>
                        <li>Accede directamente escribiendo la URL oficial</li>
                        <li>Usa marcadores/favoritos de sitios importantes</li>
                        <li>Busca el sitio en Google en lugar de hacer clic</li>
                        <li>Usa herramientas de verificación de URLs (VirusTotal)</li>
                        <li>Reporta enlaces maliciosos a las autoridades</li>
                    </ul>
                `
            },
            phishing3: {
                title: "Táctica de la Urgencia Falsa",
                icon: "⚠️",
                content: `
                    <h3>¿Qué es la táctica de urgencia?</h3>
                    <p>Los estafadores crean un falso sentido de urgencia para que actúes impulsivamente sin pensar. Te presionan para tomar decisiones rápidas que comprometen tu seguridad.</p>

                    <h3>Frases comunes de urgencia:</h3>
                    <ul>
                        <li>"Tu cuenta será cerrada en 24 horas"</li>
                        <li>"Acción requerida inmediatamente"</li>
                        <li>"Actividad sospechosa detectada - verifica ahora"</li>
                        <li>"Última oportunidad para reclamar tu premio"</li>
                        <li>"Tu paquete será devuelto si no actúas ya"</li>
                        <li>"Problema urgente con tu pago"</li>
                        <li>"Has ganado - reclama en 1 hora"</li>
                    </ul>

                    <h3>¿Por qué funciona esta táctica?</h3>
                    <ul>
                        <li>Explota el miedo a perder acceso a servicios importantes</li>
                        <li>Reduce el tiempo para pensar críticamente</li>
                        <li>Crea presión emocional para actuar</li>
                        <li>Impide que verifiques la legitimidad del mensaje</li>
                        <li>Aprovecha tu confianza en instituciones conocidas</li>
                    </ul>

                    <h3>Cómo responder correctamente:</h3>
                    <ul>
                        <li><strong>Pausa:</strong> Tómate tiempo para analizar</li>
                        <li><strong>Respira:</strong> No dejes que el pánico te controle</li>
                        <li><strong>Verifica:</strong> Contacta directamente a la organización</li>
                        <li><strong>Piensa:</strong> ¿Es lógico este mensaje?</li>
                        <li><strong>Investiga:</strong> Busca información sobre el tipo de estafa</li>
                    </ul>

                    <h3>Ejemplo de mensaje urgente falso:</h3>
                    <div class="example-box">
                        "⚠️ URGENTE: Tu cuenta de banco será bloqueada en 2 horas por actividad sospechosa. Haz clic aquí para verificar tu identidad AHORA o perderás el acceso permanentemente."
                    </div>

                    <h3>Señales de alerta:</h3>
                    <ul>
                        <li>Las instituciones legítimas nunca exigen acción inmediata por correo</li>
                        <li>Los bancos no solicitan contraseñas por email</li>
                        <li>Las ofertas reales no expiran en minutos</li>
                        <li>Las empresas serias dan tiempo razonable para responder</li>
                    </ul>

                    <h3>Regla de oro:</h3>
                    <p><strong>Si parece urgente, probablemente es falso. Las organizaciones legítimas te dan tiempo y múltiples formas de contacto.</strong></p>
                `
            },
            social1: {
                title: "Configuración de Privacidad en Redes Sociales",
                icon: "📱",
                content: `
                    <h3>¿Por qué es importante la privacidad?</h3>
                    <p>Las redes sociales recopilan grandes cantidades de información personal. Una configuración adecuada te protege de robo de identidad, acoso, y uso indebido de tus datos.</p>

                    <h3>Configuraciones esenciales de privacidad:</h3>
                    <ul>
                        <li><strong>Perfil:</strong> Establece como privado/solo amigos</li>
                        <li><strong>Publicaciones:</strong> Controla quién puede verlas (amigos, público, personalizado)</li>
                        <li><strong>Etiquetas:</strong> Revisa antes de que aparezcan en tu perfil</li>
                        <li><strong>Ubicación:</strong> Desactiva el etiquetado automático de ubicación</li>
                        <li><strong>Búsqueda:</strong> Limita quién puede encontrarte</li>
                        <li><strong>Mensajes:</strong> Controla quién puede contactarte</li>
                        <li><strong>Aplicaciones:</strong> Revisa permisos de apps conectadas</li>
                    </ul>

                    <h3>Configuración por plataforma:</h3>
                    <ul>
                        <li><strong>Facebook:</strong> Configuración → Privacidad → Revisar todas las opciones</li>
                        <li><strong>Instagram:</strong> Configuración → Privacidad → Cuenta privada</li>
                        <li><strong>Twitter/X:</strong> Configuración → Privacidad y seguridad</li>
                        <li><strong>TikTok:</strong> Configuración → Privacidad → Cuenta privada</li>
                        <li><strong>LinkedIn:</strong> Configuración → Privacidad</li>
                    </ul>

                    <h3>Información que NO debes compartir:</h3>
                    <ul>
                        <li>Número de teléfono completo</li>
                        <li>Dirección exacta de tu casa</li>
                        <li>Ubicación en tiempo real</li>
                        <li>Fotos de documentos oficiales</li>
                        <li>Información financiera</li>
                        <li>Planes de vacaciones (antes de viajar)</li>
                        <li>Rutinas diarias específicas</li>
                        <li>Respuestas a preguntas de seguridad (mascota, escuela, etc.)</li>
                    </ul>

                    <h3>Revisión periódica:</h3>
                    <ul>
                        <li>Revisa tu configuración cada 3-6 meses</li>
                        <li>Las plataformas cambian sus políticas frecuentemente</li>
                        <li>Elimina aplicaciones conectadas que no uses</li>
                        <li>Revisa la lista de amigos/seguidores</li>
                        <li>Actualiza las publicaciones antiguas sensibles</li>
                    </ul>

                    <h3>Herramientas útiles:</h3>
                    <ul>
                        <li>Usa las herramientas de "Revisión de privacidad" de cada plataforma</li>
                        <li>Activa alertas de inicio de sesión</li>
                        <li>Revisa sesiones activas regularmente</li>
                        <li>Configura autenticación de dos factores</li>
                    </ul>
                `
            },
            social2: {
                title: "Piensa Antes de Publicar",
                icon: "🔒",
                content: `
                    <h3>El impacto permanente de lo que publicas</h3>
                    <p>Internet tiene memoria permanente. Lo que compartes hoy puede afectarte años después. Las capturas de pantalla y los archivos pueden persistir incluso después de eliminar publicaciones.</p>

                    <h3>Antes de publicar, pregúntate:</h3>
                    <ul>
                        <li>¿Me sentiría cómodo si mi familia/empleador viera esto?</li>
                        <li>¿Podría esta información ser usada en mi contra?</li>
                        <li>¿Estoy compartiendo información personal sensible?</li>
                        <li>¿Esta publicación podría ofender o dañar a alguien?</li>
                        <li>¿Tendré la misma opinión en 5 años?</li>
                        <li>¿Es necesario compartir esto públicamente?</li>
                    </ul>

                    <h3>Riesgos de oversharing (compartir de más):</h3>
                    <ul>
                        <li><strong>Robo de identidad:</strong> Datos personales facilitan suplantación</li>
                        <li><strong>Ingeniería social:</strong> Información para ataques dirigidos</li>
                        <li><strong>Robo físico:</strong> Publicar vacaciones = casa vacía</li>
                        <li><strong>Acoso/stalking:</strong> Rastreo de ubicaciones y rutinas</li>
                        <li><strong>Consecuencias laborales:</strong> Empleadores revisan redes sociales</li>
                        <li><strong>Fraude:</strong> Respuestas a preguntas de seguridad</li>
                    </ul>

                    <h3>Ejemplos de información sensible:</h3>
                    <div class="example-box">
                        ❌ "¡Nos vamos de vacaciones 2 semanas! Casa vacía..."<br>
                        ❌ "Mi primer auto fue un [respuesta de seguridad]"<br>
                        ❌ Foto del boarding pass con código de barras<br>
                        ❌ "Mi cumpleaños es..." + año + ciudad natal<br>
                        ❌ Fotos de llaves o documentos con datos visibles
                    </div>

                    <h3>Consejos para publicaciones seguras:</h3>
                    <ul>
                        <li>Publica fotos de vacaciones DESPUÉS de regresar</li>
                        <li>Desactiva metadatos de ubicación en fotos</li>
                        <li>No compartas tickets, pases o documentos</li>
                        <li>Evita mostrar números de casa o placas de auto</li>
                        <li>No publiques el nombre completo de menores</li>
                        <li>Cuidado con fondos que revelan información</li>
                    </ul>

                    <h3>Regla del embarazo:</h3>
                    <p><strong>"Si no quieres que toda tu familia, jefe y extraños lo sepan, no lo publiques en redes sociales."</strong></p>

                    <h3>Educación digital:</h3>
                    <ul>
                        <li>Enseña a menores sobre huella digital permanente</li>
                        <li>Revisa configuraciones de privacidad con ellos</li>
                        <li>Establece reglas familiares sobre publicaciones</li>
                        <li>Practica el pensamiento crítico antes de compartir</li>
                    </ul>
                `
            },
            social3: {
                title: "Verificación de Solicitudes de Amistad",
                icon: "👥",
                content: `
                    <h3>El peligro de los perfiles falsos</h3>
                    <p>Los cibercriminales crean perfiles falsos para obtener información personal, realizar estafas, propagar malware o realizar ataques de ingeniería social.</p>

                    <h3>Señales de un perfil falso:</h3>
                    <ul>
                        <li><strong>Fotos:</strong> Muy pocas fotos, solo fotos profesionales, o imágenes robadas</li>
                        <li><strong>Amigos:</strong> Pocos amigos o amigos sin conexión entre sí</li>
                        <li><strong>Actividad:</strong> Cuenta nueva o poca actividad histórica</li>
                        <li><strong>Información:</strong> Perfil incompleto o información genérica</li>
                        <li><strong>Publicaciones:</strong> Spam, enlaces sospechosos o contenido robado</li>
                        <li><strong>Mensaje inicial:</strong> Demasiado amigable o con intenciones románticas rápidas</li>
                        <li><strong>Coincidencias:</strong> No tienen amigos mutuos reales</li>
                    </ul>

                    <h3>Cómo verificar un perfil:</h3>
                    <ul>
                        <li><strong>Búsqueda inversa de imagen:</strong> Usa Google Images para verificar fotos</li>
                        <li><strong>Revisa amigos mutuos:</strong> ¿Los conoces realmente?</li>
                        <li><strong>Historial:</strong> Revisa publicaciones antiguas</li>
                        <li><strong>Interacciones:</strong> ¿Hay comentarios reales de amigos?</li>
                        <li><strong>Información:</strong> ¿Es consistente la historia laboral/educativa?</li>
                        <li><strong>Actividad:</strong> ¿Publica solo enlaces o contenido promocional?</li>
                    </ul>

                    <h3>Tipos comunes de perfiles falsos:</h3>
                    <ul>
                        <li><strong>Catfishing:</strong> Pretenden ser otra persona para romance/estafa</li>
                        <li><strong>Bots:</strong> Cuentas automatizadas para spam</li>
                        <li><strong>Clones:</strong> Copian perfil de alguien que conoces</li>
                        <li><strong>Espías corporativos:</strong> Buscan información empresarial</li>
                        <li><strong>Estafadores:</strong> Historias de emergencias para pedir dinero</li>
                    </ul>

                    <h3>Qué hacer con perfiles sospechosos:</h3>
                    <ul>
                        <li><strong>NO aceptes</strong> la solicitud de amistad</li>
                        <li><strong>NO compartas</strong> información personal</li>
                        <li><strong>NO hagas clic</strong> en enlaces que envíen</li>
                        <li><strong>Reporta</strong> el perfil a la plataforma</li>
                        <li><strong>Bloquea</strong> el perfil inmediatamente</li>
                        <li><strong>Alerta</strong> a amigos si es un clon</li>
                    </ul>

                    <h3>Estafas comunes desde perfiles falsos:</h3>
                    <div class="example-box">
                        💔 Romance: Desarrollan relación y luego piden dinero<br>
                        💰 Inversión: Prometen ganancias rápidas<br>
                        🎁 Premios: "Ganaste algo" pero necesitan datos<br>
                        👔 Trabajo: Ofertas laborales falsas para robar información<br>
                        🆘 Emergencia: Familiar en problemas necesita dinero urgente
                    </div>

                    <h3>Protección de tu red:</h3>
                    <ul>
                        <li>Solo acepta solicitudes de personas que conoces en la vida real</li>
                        <li>Si alguien que ya tienes agregado te envía otra solicitud, es un clon</li>
                        <li>Verifica identidad por otro canal antes de aceptar</li>
                        <li>Revisa periódicamente tu lista de amigos</li>
                        <li>Elimina contactos que no reconoces</li>
                    </ul>

                    <h3>Regla de oro:</h3>
                    <p><strong>"En caso de duda, no aceptes. Es mejor tener menos amigos online y más seguridad."</strong></p>
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

            // Smooth scroll para la página
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });

        // Agregar detalles para los consejos restantes
        Object.assign(tipDetails, {
            wifi1: {
                title: "Peligros de las Redes WiFi Públicas",
                icon: "📶",
                content: `
                    <h3>¿Por qué son peligrosas las redes WiFi públicas?</h3>
                    <p>Las redes WiFi en cafeterías, aeropuertos, hoteles y espacios públicos no están cifradas o usan cifrado débil, permitiendo que atacantes intercepten tu tráfico de internet.</p>

                    <h3>Ataques comunes en WiFi público:</h3>
                    <ul>
                        <li><strong>Man-in-the-Middle (MITM):</strong> El atacante intercepta comunicaciones entre tú y el sitio web</li>
                        <li><strong>Redes falsas (Evil Twin):</strong> Redes WiFi creadas por atacantes con nombres similares</li>
                        <li><strong>Sniffing:</strong> Captura de paquetes de datos no cifrados</li>
                        <li><strong>Session Hijacking:</strong> Robo de sesiones activas y cookies</li>
                        <li><strong>Malware Distribution:</strong> Inyección de software malicioso</li>
                    </ul>

                    <h3>Actividades que NUNCA debes hacer en WiFi público:</h3>
                    <ul>
                        <li>Operaciones bancarias o financieras</li>
                        <li>Compras online con tarjeta de crédito</li>
                        <li>Acceso a información médica o legal</li>
                        <li>Inicio de sesión en cuentas importantes sin VPN</li>
                        <li>Envío de información confidencial laboral</li>
                        <li>Compartir archivos sensibles</li>
                    </ul>

                    <h3>Medidas de protección básicas:</h3>
                    <ul>
                        <li>Usa tus datos móviles para transacciones importantes</li>
                        <li>Verifica que estás conectado a la red oficial del establecimiento</li>
                        <li>Desactiva la conexión automática a WiFi</li>
                        <li>Desactiva compartir archivos y red</li>
                        <li>Usa solo sitios HTTPS (con candado)</li>
                        <li>Desconéctate cuando no estés usando la red</li>
                    </ul>

                    <h3>Cómo identificar una red WiFi falsa:</h3>
                    <div class="example-box">
                        ⚠️ "Starbucks_WiFi_Free" vs "Starbucks WiFi"<br>
                        ⚠️ "Airport_Free_WiFi" vs red oficial del aeropuerto<br>
                        ⚠️ Múltiples redes con nombres muy similares
                    </div>

                    <h3>Alternativas seguras:</h3>
                    <ul>
                        <li><strong>Hotspot personal:</strong> Usa los datos de tu teléfono</li>
                        <li><strong>VPN confiable:</strong> Cifra todo tu tráfico</li>
                        <li><strong>Firewall activo:</strong> Mantén el firewall encendido</li>
                        <li><strong>Antivirus actualizado:</strong> Protección adicional</li>
                    </ul>
                `
            },
            wifi2: {
                title: "Uso de VPN para Protección",
                icon: "🛡️",
                content: `
                    <h3>¿Qué es una VPN?</h3>
                    <p>Una VPN (Red Privada Virtual) crea un túnel cifrado entre tu dispositivo e internet, protegiendo tus datos de miradas indiscretas y ocultando tu ubicación real.</p>

                    <h3>¿Cómo funciona una VPN?</h3>
                    <ul>
                        <li>Cifra todo tu tráfico de internet</li>
                        <li>Oculta tu dirección IP real</li>
                        <li>Enruta tu conexión a través de servidores seguros</li>
                        <li>Protege contra interceptación en redes públicas</li>
                        <li>Evita el seguimiento de tu actividad online</li>
                    </ul>

                    <h3>Beneficios de usar VPN:</h3>
                    <ul>
                        <li><strong>Privacidad:</strong> Tu ISP no puede ver tu actividad</li>
                        <li><strong>Seguridad:</strong> Protección contra hackers en WiFi público</li>
                        <li><strong>Anonimato:</strong> Oculta tu ubicación geográfica</li>
                        <li><strong>Acceso:</strong> Evita bloqueos geográficos</li>
                        <li><strong>Protección de datos:</strong> Cifrado militar de 256 bits</li>
                    </ul>

                    <h3>VPN recomendadas (de pago):</h3>
                    <ul>
                        <li><strong>NordVPN:</strong> Excelente velocidad y seguridad</li>
                        <li><strong>ExpressVPN:</strong> Rápida y confiable</li>
                        <li><strong>ProtonVPN:</strong> Enfocada en privacidad</li>
                        <li><strong>Surfshark:</strong> Buena relación calidad-precio</li>
                        <li><strong>CyberGhost:</strong> Fácil de usar</li>
                    </ul>

                    <h3>⚠️ Precauciones con VPN gratuitas:</h3>
                    <ul>
                        <li>Muchas venden tus datos a terceros</li>
                        <li>Pueden inyectar anuncios en tu navegación</li>
                        <li>Velocidades muy lentas y limitadas</li>
                        <li>Límites de datos mensuales</li>
                        <li>Menor seguridad y cifrado débil</li>
                        <li>Registro de tu actividad</li>
                    </ul>

                    <h3>Cuándo es esencial usar VPN:</h3>
                    <ul>
                        <li>Conectarse a WiFi público</li>
                        <li>Trabajar remotamente con datos sensibles</li>
                        <li>Viajar a países con censura</li>
                        <li>Acceder a tu banco desde lugares desconocidos</li>
                        <li>Proteger tu privacidad de rastreo</li>
                    </ul>

                    <h3>Configuración básica:</h3>
                    <ul>
                        <li>Descarga la app oficial del proveedor</li>
                        <li>Crea una cuenta y suscríbete</li>
                        <li>Instala en todos tus dispositivos</li>
                        <li>Activa antes de conectarte a WiFi público</li>
                        <li>Elige servidores cercanos para mejor velocidad</li>
                        <li>Activa Kill Switch (corta internet si VPN falla)</li>
                    </ul>

                    <h3>Lo que VPN NO hace:</h3>
                    <ul>
                        <li>No te hace 100% anónimo online</li>
                        <li>No protege contra malware o virus</li>
                        <li>No evita que compartas información voluntariamente</li>
                        <li>No protege contra phishing si haces clic en enlaces</li>
                    </ul>
                `
            },
            wifi3: {
                title: "Seguridad del Router Doméstico",
                icon: "🔐",
                content: `
                    <h3>Tu router es la puerta de entrada a tu hogar digital</h3>
                    <p>Un router mal configurado permite que atacantes accedan a tu red, roben datos, usen tu internet para actividades ilegales o ataquen tus dispositivos.</p>

                    <h3>Configuración esencial del router:</h3>
                    <ul>
                        <li><strong>Cambia la contraseña predeterminada:</strong> Es lo primero que intentan los atacantes</li>
                        <li><strong>Cambia el nombre de usuario admin:</strong> No uses "admin"</li>
                        <li><strong>Actualiza el firmware:</strong> Mantén el software del router actualizado</li>
                        <li><strong>Cambia el SSID:</strong> No uses el nombre predeterminado del router</li>
                        <li><strong>Oculta el SSID:</strong> Tu red no aparecerá en búsquedas (opcional)</li>
                        <li><strong>Desactiva WPS:</strong> Es vulnerable a ataques</li>
                        <li><strong>Usa cifrado WPA3 o WPA2:</strong> Nunca WEP o WPA</li>
                    </ul>

                    <h3>Creación de contraseña WiFi fuerte:</h3>
                    <div class="example-box">
                        ❌ WiFi123456<br>
                        ❌ MiCasa2024<br>
                        ✅ $3gur0!W1F1#2025@Ch1hU4hu4<br>
                        (Mínimo 20 caracteres recomendado)
                    </div>

                    <h3>Tipos de cifrado WiFi:</h3>
                    <ul>
                        <li><strong>WPA3:</strong> ✅ Más seguro (usa si tu router lo soporta)</li>
                        <li><strong>WPA2:</strong> ✅ Seguro (usa AES, no TKIP)</li>
                        <li><strong>WPA:</strong> ⚠️ Vulnerable, evitar</li>
                        <li><strong>WEP:</strong> ❌ Extremadamente inseguro, nunca usar</li>
                        <li><strong>Abierta:</strong> ❌ Sin protección</li>
                    </ul>

                    <h3>Seguridad avanzada:</h3>
                    <ul>
                        <li><strong>Firewall activado:</strong> Incluido en la mayoría de routers</li>
                        <li><strong>Filtrado MAC:</strong> Permite solo dispositivos autorizados</li>
                        <li><strong>Red de invitados:</strong> Aísla visitas de tu red principal</li>
                        <li><strong>Desactiva administración remota:</strong> A menos que la necesites</li>
                        <li><strong>Cambia DNS:</strong> Usa DNS seguros (1.1.1.1, 8.8.8.8)</li>
                        <li><strong>Desactiva UPnP:</strong> Si no lo necesitas</li>
                    </ul>

                    <h3>Cómo acceder a tu router:</h3>
                    <ul>
                        <li>Abre navegador web</li>
                        <li>Escribe 192.168.1.1 o 192.168.0.1 (común)</li>
                        <li>Ingresa usuario y contraseña (viene en el router)</li>
                        <li>Busca configuración de seguridad/WiFi</li>
                        <li>Cambia todos los valores predeterminados</li>
                    </ul>

                    <h3>Señales de que tu WiFi está comprometida:</h3>
                    <ul>
                        <li>Internet súbitamente lento</li>
                        <li>Dispositivos desconocidos conectados</li>
                        <li>Configuración del router cambiada</li>
                        <li>Redirecciones extrañas en navegador</li>
                        <li>Exceso de uso de datos inexplicable</li>
                    </ul>

                    <h3>Monitoreo de red:</h3>
                    <ul>
                        <li>Revisa dispositivos conectados regularmente</li>
                        <li>Usa apps como Fing o router admin panel</li>
                        <li>Desconecta dispositivos que no reconoces</li>
                        <li>Cambia contraseña si sospechas intrusión</li>
                    </ul>

                    <h3>Mantenimiento periódico:</h3>
                    <ul>
                        <li>Reinicia el router mensualmente</li>
                        <li>Busca actualizaciones de firmware cada 3 meses</li>
                        <li>Cambia contraseña WiFi cada 6-12 meses</li>
                        <li>Revisa configuración después de actualizaciones</li>
                    </ul>
                `
            },
            device1: {
                title: "Importancia de las Actualizaciones",
                icon: "💻",
                content: `
                    <h3>¿Por qué son cruciales las actualizaciones?</h3>
                    <p>Las actualizaciones no solo agregan nuevas funciones, sino que parches críticos de seguridad que protegen contra vulnerabilidades conocidas que los atacantes explotan activamente.</p>

                    <h3>Tipos de actualizaciones:</h3>
                    <ul>
                        <li><strong>Actualizaciones de seguridad:</strong> Parches urgentes para vulnerabilidades críticas</li>
                        <li><strong>Actualizaciones de sistema operativo:</strong> Mejoras generales y de seguridad</li>
                        <li><strong>Actualizaciones de aplicaciones:</strong> Correcciones de bugs y seguridad</li>
                        <li><strong>Actualizaciones de firmware:</strong> Para dispositivos IoT, routers, etc.</li>
                        <li><strong>Definiciones de antivirus:</strong> Bases de datos de amenazas actualizadas</li>
                    </ul>

                    <h3>Riesgos de no actualizar:</h3>
                    <ul>
                        <li>Vulnerabilidades conocidas sin parchar</li>
                        <li>Explotación por malware y ransomware</li>
                        <li>Pérdida de compatibilidad con servicios</li>
                        <li>Bajo rendimiento del sistema</li>
                        <li>Falta de nuevas funciones de seguridad</li>
                        <li>No cumplir con regulaciones de seguridad</li>
                    </ul>

                    <h3>Sistemas operativos a actualizar:</h3>
                    <ul>
                        <li><strong>Windows:</strong> Windows Update - configura automáticas</li>
                        <li><strong>macOS:</strong> System Preferences → Software Update</li>
                        <li><strong>Linux:</strong> Gestor de paquetes (apt, yum, etc.)</li>
                        <li><strong>Android:</strong> Settings → System → System Update</li>
                        <li><strong>iOS:</strong> Settings → General → Software Update</li>
                    </ul>

                    <h3>Aplicaciones críticas para actualizar:</h3>
                    <ul>
                        <li>Navegadores web (Chrome, Firefox, Edge, Safari)</li>
                        <li>Software de seguridad (antivirus, firewall)</li>
                        <li>Aplicaciones de comunicación (WhatsApp, Telegram)</li>
                        <li>Clientes de correo electrónico</li>
                        <li>Software de productividad (Office, Adobe)</li>
                        <li>Reproductores multimedia</li>
                    </ul>

                    <h3>Mejores prácticas:</h3>
                    <ul>
                        <li><strong>Activa actualizaciones automáticas</strong> siempre que sea posible</li>
                        <li><strong>No postpongas</strong> actualizaciones críticas de seguridad</li>
                        <li><strong>Haz backup</strong> antes de actualizaciones mayores</li>
                        <li><strong>Actualiza por WiFi seguro</strong> no público</li>
                        <li><strong>Verifica la fuente</strong> de las actualizaciones</li>
                        <li><strong>Reinicia después</strong> de instalar actualizaciones</li>
                    </ul>

                    <h3>Calendario de actualizaciones recomendado:</h3>
                    <div class="example-box">
                        📅 Diarias: Definiciones de antivirus<br>
                        📅 Semanales: Navegadores y apps de comunicación<br>
                        📅 Mensuales: Sistema operativo y apps principales<br>
                        📅 Trimestrales: Firmware de dispositivos IoT<br>
                        📅 Inmediatas: Parches de seguridad críticos
                    </div>

                    <h3>Señales de actualización falsa (malware):</h3>
                    <ul>
                        <li>Pop-ups en el navegador ofreciendo actualizaciones</li>
                        <li>Solicitudes de actualización fuera del sistema oficial</li>
                        <li>Enlaces en emails para "actualizar software"</li>
                        <li>Descargas desde sitios no oficiales</li>
                        <li>Solicitudes de información de pago para actualizar</li>
                    </ul>

                    <h3>Actualizaciones solo desde fuentes oficiales:</h3>
                    <ul>
                        <li>Windows Update integrado en el sistema</li>
                        <li>Mac App Store o System Preferences</li>
                        <li>Google Play Store o App Store oficial</li>
                        <li>Sitios web oficiales de desarrolladores</li>
                        <li>Verificar certificados digitales y HTTPS</li>
                    </ul>
                `
            },
            device2: {
                title: "Software Antivirus y Antimalware",
                icon: "🦠",
                content: `
                    <h3>¿Por qué necesitas protección antivirus?</h3>
                    <p>Aunque los sistemas operativos modernos tienen protecciones integradas, un antivirus dedicado ofrece capas adicionales de defensa contra amenazas en constante evolución.</p>

                    <h3>Tipos de amenazas que detectan:</h3>
                    <ul>
                        <li><strong>Virus:</strong> Programas que se replican e infectan archivos</li>
                        <li><strong>Malware:</strong> Software malicioso en general</li>
                        <li><strong>Ransomware:</strong> Secuestra tus archivos por rescate</li>
                        <li><strong>Spyware:</strong> Espía tu actividad y roba datos</li>
                        <li><strong>Adware:</strong> Publicidad maliciosa invasiva</li>
                        <li><strong>Troyanos:</strong> Se disfrazan de software legítimo</li>
                        <li><strong>Rootkits:</strong> Ocultan malware del sistema</li>
                        <li><strong>Keyloggers:</strong> Registran tus pulsaciones de teclado</li>
                    </ul>

                    <h3>Antivirus recomendados (2025):</h3>
                    <ul>
                        <li><strong>Windows Defender:</strong> ✅ Gratis, integrado en Windows 10/11, muy efectivo</li>
                        <li><strong>Bitdefender:</strong> Excelente detección, bajo impacto</li>
                        <li><strong>Kaspersky:</strong> Protección robusta</li>
                        <li><strong>Norton 360:</strong> Suite completa de seguridad</li>
                        <li><strong>ESET NOD32:</strong> Ligero y eficiente</li>
                        <li><strong>Malwarebytes:</strong> Especializado en malware</li>
                    </ul>

                    <h3>Características esenciales:</h3>
                    <ul>
                        <li><strong>Escaneo en tiempo real:</strong> Protección activa constante</li>
                        <li><strong>Firewall:</strong> Control de tráfico de red</li>
                        <li><strong>Protección web:</strong> Bloqueo de sitios maliciosos</li>
                        <li><strong>Anti-phishing:</strong> Detecta correos y sitios falsos</li>
                        <li><strong>Protección de ransomware:</strong> Previene secuestro de datos</li>
                        <li><strong>Análisis de comportamiento:</strong> Detecta amenazas desconocidas</li>
                        <li><strong>Actualizaciones automáticas:</strong> Base de datos siempre actualizada</li>
                    </ul>

                    <h3>Configuración óptima:</h3>
                    <ul>
                        <li>Activa escaneo en tiempo real</li>
                        <li>Programa escaneos completos semanales</li>
                        <li>Habilita actualizaciones automáticas</li>
                        <li>Configura escaneo de unidades externas</li>
                        <li>Activa protección de navegación web</li>
                        <li>Habilita análisis de email</li>
                        <li>Configura cuarentena automática</li>
                    </ul>

                    <h3>⚠️ Errores comunes:</h3>
                    <ul>
                        <li>Instalar múltiples antivirus (pueden conflictuar)</li>
                        <li>Desactivar el antivirus "temporalmente"</li>
                        <li>Ignorar alertas de seguridad</li>
                        <li>No actualizar las definiciones</li>
                        <li>Usar versiones piratas de antivirus</li>
                        <li>Confiar solo en antivirus sin prácticas seguras</li>
                    </ul>

                    <h3>Rutina de mantenimiento:</h3>
                    <div class="example-box">
                        📅 Diario: Actualizaciones automáticas<br>
                        📅 Semanal: Escaneo completo del sistema<br>
                        📅 Mensual: Revisión de registros y cuarentena<br>
                        📅 Trimestral: Evaluación de configuración<br>
                        📅 Inmediato: Escaneo después de descargas sospechosas
                    </div>

                    <h3>Complementos recomendados:</h3>
                    <ul>
                        <li><strong>Malwarebytes:</strong> Escaneo adicional anti-malware</li>
                        <li><strong>HitmanPro:</strong> Segunda opinión contra amenazas</li>
                        <li><strong>Spybot:</strong> Especializado en spyware</li>
                        <li><strong>AdwCleaner:</strong> Elimina adware y toolbars</li>
                    </ul>

                    <h3>Protección en diferentes sistemas:</h3>
                    <ul>
                        <li><strong>Windows:</strong> Windows Defender + Malwarebytes</li>
                        <li><strong>macOS:</strong> Malwarebytes for Mac o CleanMyMac</li>
                        <li><strong>Linux:</strong> ClamAV (aunque menos necesario)</li>
                        <li><strong>Android:</strong> Google Play Protect + Bitdefender Mobile</li>
                        <li><strong>iOS:</strong> Sandbox de Apple (menos vulnerable)</li>
                    </ul>

                    <h3>Qué hacer si detecta amenaza:</h3>
                    <ul>
                        <li>NO ignores la alerta</li>
                        <li>Desconéctate de internet si es crítico</li>
                        <li>Sigue las recomendaciones del antivirus</li>
                        <li>Cuarentena o elimina el archivo infectado</li>
                        <li>Escanea completamente el sistema</li>
                        <li>Cambia contraseñas si hubo robo de datos</li>
                        <li>Revisa transacciones bancarias</li>
                    </ul>
                `
            },
            device3: {
                title: "Copias de Seguridad (Backups)",
                icon: "💾",
                content: `
                    <h3>La regla de oro: El dato que no está respaldado, no existe</h3>
                    <p>Las copias de seguridad son tu última línea de defensa contra ransomware, fallas de hardware, errores humanos y desastres. Son la diferencia entre una molestia y una catástrofe total.</p>

                    <h3>Regla 3-2-1 de backups:</h3>
                    <div class="example-box">
                        <strong>3</strong> copias de tus datos<br>
                        <strong>2</strong> en diferentes medios de almacenamiento<br>
                        <strong>1</strong> copia offsite (fuera de tu ubicación)
                    </div>

                    <h3>Tipos de backups:</h3>
                    <ul>
                        <li><strong>Backup completo:</strong> Copia de todos los datos (lento pero completo)</li>
                        <li><strong>Backup incremental:</strong> Solo cambios desde último backup (rápido)</li>
                        <li><strong>Backup diferencial:</strong> Cambios desde último backup completo</li>
                        <li><strong>Imagen del sistema:</strong> Copia exacta del disco completo</li>
                        <li><strong>Sincronización en tiempo real:</strong> Actualización continua</li>
                    </ul>

                    <h3>Métodos de respaldo:</h3>
                    <ul>
                        <li><strong>Disco duro externo:</strong> Económico, gran capacidad, control total</li>
                        <li><strong>NAS (Network Attached Storage):</strong> Servidor doméstico de backups</li>
                        <li><strong>Nube (Cloud):</strong> Acceso desde cualquier lugar, protección offsite</li>
                        <li><strong>Unidades USB:</strong> Portátiles pero menor capacidad</li>
                        <li><strong>Discos ópticos:</strong> DVD/Blu-ray (menos común ahora)</li>
                    </ul>

                    <h3>Servicios de backup en nube recomendados:</h3>
                    <ul>
                        <li><strong>Backblaze:</strong> Ilimitado, económico, fácil de usar</li>
                        <li><strong>Google Drive:</strong> 15GB gratis, integración con Google</li>
                        <li><strong>Dropbox:</strong> Sincronización excelente</li>
                        <li><strong>OneDrive:</strong> Integrado con Windows y Office</li>
                        <li><strong>iCloud:</strong> Ideal para ecosistema Apple</li>
                        <li><strong>Carbonite:</strong> Orientado a negocios</li>
                    </ul>

                    <h3>Qué datos respaldar:</h3>
                    <ul>
                        <li>✅ Documentos personales y laborales</li>
                        <li>✅ Fotos y videos irreemplazables</li>
                        <li>✅ Contactos y calendarios</li>
                        <li>✅ Configuraciones y preferencias</li>
                        <li>✅ Proyectos creativos y código fuente</li>
                        <li>✅ Registros financieros y fiscales</li>
                        <li>✅ Correos importantes</li>
                        <li>✅ Favoritos y contraseñas (cifrados)</li>
                    </ul>

                    <h3>Frecuencia de backups:</h3>
                    <div class="example-box">
                        📅 Crítico (trabajo): Diario o en tiempo real<br>
                        📅 Importante: Semanal<br>
                        📅 Personal: Cada 2 semanas<br>
                        📅 Archivos: Mensual<br>
                        📅 Imagen del sistema: Trimestral
                    </div>

                    <h3>Software de backup recomendado:</h3>
                    <ul>
                        <li><strong>Windows:</strong> File History, Windows Backup, Acronis</li>
                        <li><strong>macOS:</strong> Time Machine (integrado y excelente)</li>
                        <li><strong>Linux:</strong> Timeshift, Deja Dup, rsync</li>
                        <li><strong>Multiplataforma:</strong> Duplicati, Veeam, FreeFileSync</li>
                    </ul>

                    <h3>Cifrado de backups:</h3>
                    <ul>
                        <li>SIEMPRE cifra backups con información sensible</li>
                        <li>Usa cifrado AES-256 o superior</li>
                        <li>Guarda la clave de cifrado en lugar seguro (no en el backup)</li>
                        <li>Considera usar herramientas como VeraCrypt</li>
                        <li>Servicios en nube deben ofrecer cifrado de extremo a extremo</li>
                    </ul>

                    <h3>Verificación de backups:</h3>
                    <ul>
                        <li>❗ Un backup no verificado no es un backup</li>
                        <li>Prueba la restauración periódicamente</li>
                        <li>Verifica la integridad de los archivos</li>
                        <li>Confirma que los backups se están ejecutando</li>
                        <li>Revisa logs de errores</li>
                        <li>Mantén múltiples versiones de archivos</li>
                    </ul>

                    <h3>Protección contra ransomware:</h3>
                    <ul>
                        <li>Desconecta discos externos después del backup</li>
                        <li>Usa backups inmutables (que no pueden modificarse)</li>
                        <li>Mantén versiones históricas de archivos</li>
                        <li>Backups offline no conectados a la red</li>
                        <li>Almacenamiento cloud con versionado</li>
                    </ul>

                    <h3>Plan de recuperación ante desastres:</h3>
                    <ul>
                        <li>Documenta el proceso de restauración</li>
                        <li>Lista de software y licencias necesarias</li>
                        <li>Contactos de soporte técnico</li>
                        <li>Ubicación de todos los backups</li>
                        <li>Claves de cifrado en lugar seguro</li>
                        <li>Practica la restauración al menos una vez al año</li>
                    </ul>

                    <h3>Errores comunes a evitar:</h3>
                    <ul>
                        <li>❌ Confiar en un solo backup</li>
                        <li>❌ Nunca probar la restauración</li>
                        <li>❌ Backups solo en el mismo disco</li>
                        <li>❌ No cifrar información sensible</li>
                        <li>❌ Postponer backups "para después"</li>
                        <li>❌ No actualizar el plan de backup</li>
                    </ul>
                `
            },
            auth1: {
                title: "Autenticación de Dos Factores (2FA)",
                icon: "🔑",
                content: `
                    <h3>¿Qué es la autenticación de dos factores?</h3>
                    <p>2FA añade una segunda capa de seguridad más allá de la contraseña. Requiere algo que sabes (contraseña) y algo que tienes (teléfono, token) o algo que eres (huella digital).</p>

                    <h3>Tipos de 2FA:</h3>
                    <ul>
                        <li><strong>SMS/Mensaje de texto:</strong> ⚠️ Menos seguro (SIM swapping), pero mejor que nada</li>
                        <li><strong>Aplicación autenticadora:</strong> ✅ Recomendado (Google Authenticator, Authy, Microsoft Authenticator)</li>
                        <li><strong>Llaves de seguridad físicas:</strong> ✅✅ Más seguro (YubiKey, Titan Security Key)</li>
                        <li><strong>Biométrica:</strong> ✅ Huella digital, reconocimiento facial</li>
                        <li><strong>Código de respaldo:</strong> Códigos de un solo uso para emergencias</li>
                        <li><strong>Notificaciones push:</strong> Apruebas desde tu dispositivo</li>
                    </ul>

                    <h3>¿Por qué es crucial 2FA?</h3>
                    <ul>
                        <li>Incluso si roban tu contraseña, no pueden acceder</li>
                        <li>Protege contra ataques de fuerza bruta</li>
                        <li>Notificación si alguien intenta acceder</li>
                        <li>Requerido por muchas regulaciones de seguridad</li>
                        <li>Protección contra phishing de contraseñas</li>
                    </ul>

                    <h3>Cuentas donde DEBES activar 2FA:</h3>
                    <ul>
                        <li>✅ Email principal (Gmail, Outlook, etc.)</li>
                        <li>✅ Banca online y finanzas</li>
                        <li>✅ Redes sociales (Facebook, Instagram, Twitter)</li>
                        <li>✅ Almacenamiento en nube (Google Drive, Dropbox)</li>
                        <li>✅ Gestores de contraseñas</li>
                        <li>✅ Cuentas de trabajo y estudio</li>
                        <li>✅ Servicios de compras online</li>
                        <li>✅ Criptomonedas e inversiones</li>
                    </ul>

                    <h3>Aplicaciones autenticadoras recomendadas:</h3>
                    <ul>
                        <li><strong>Google Authenticator:</strong> Simple y efectivo</li>
                        <li><strong>Authy:</strong> Con backup en nube y multi-dispositivo</li>
                        <li><strong>Microsoft Authenticator:</strong> Integración con Microsoft</li>
                        <li><strong>1Password:</strong> Integrado con gestor de contraseñas</li>
                        <li><strong>Duo Mobile:</strong> Usado en empresas</li>
                    </ul>

                    <h3>Cómo configurar 2FA paso a paso:</h3>
                    <ul>
                        <li>Accede a configuración de seguridad de tu cuenta</li>
                        <li>Busca "Autenticación de dos factores" o "2FA"</li>
                        <li>Elige método preferido (app autenticadora recomendado)</li>
                        <li>Escanea código QR con la app</li>
                        <li>Verifica con el código generado</li>
                        <li><strong>IMPORTANTE:</strong> Guarda códigos de respaldo en lugar seguro</li>
                    </ul>

                    <h3>Códigos de respaldo - CRÍTICO:</h3>
                    <div class="example-box">
                        ⚠️ Guarda los códigos de respaldo que te dan al activar 2FA<br>
                        📄 Imprímelos o guárdalos en gestor de contraseñas<br>
                        🔒 No los compartas con nadie<br>
                        💾 Actualízalos si los usas
                    </div>

                    <h3>Llaves de seguridad físicas (nivel avanzado):</h3>
                    <ul>
                        <li><strong>YubiKey:</strong> Estándar de la industria</li>
                        <li><strong>Google Titan:</strong> Certificado por Google</li>
                        <li><strong>Thetis FIDO2:</strong> Alternativa económica</li>
                        <li>Inmunes a phishing</li>
                        <li>No requieren batería o conectividad</li>
                        <li>Compra 2: una principal y una de respaldo</li>
                    </ul>

                    <h3>Qué hacer si pierdes acceso a 2FA:</h3>
                    <ul>
                        <li>Usa códigos de respaldo guardados</li>
                        <li>Contacta soporte del servicio con identificación</li>
                        <li>Usa número de teléfono o email de recuperación</li>
                        <li>Restaura desde backup de app autenticadora (Authy)</li>
                        <li>Prevención: Siempre configura múltiples métodos</li>
                    </ul>

                    <h3>Errores comunes con 2FA:</h3>
                    <ul>
                        <li>❌ No guardar códigos de respaldo</li>
                        <li>❌ Solo un método 2FA configurado</li>
                        <li>❌ Usar SMS como único método</li>
                        <li>❌ Compartir códigos con otros</li>
                        <li>❌ No actualizar info de recuperación</li>
                    </ul>

                    <h3>Protección contra ataques a 2FA:</h3>
                    <ul>
                        <li>No compartas códigos con nadie (ni soporte técnico)</li>
                        <li>Verifica dominio antes de ingresar código</li>
                        <li>Usa apps autenticadoras, no SMS cuando sea posible</li>
                        <li>Protege tu SIM card contra SIM swapping</li>
                        <li>Habilita alertas de inicio de sesión</li>
                    </ul>
                `
            },
            email1: {
                title: "Seguridad en el Correo Electrónico",
                icon: "📧",
                content: `
                    <h3>Tu email es la llave maestra de tu vida digital</h3>
                    <p>El correo electrónico es el objetivo principal de los atacantes porque da acceso a resetear contraseñas de otros servicios, contiene información sensible y es punto de entrada para phishing.</p>

                    <h3>Configuración de seguridad esencial:</h3>
                    <ul>
                        <li><strong>Contraseña única y fuerte:</strong> Nunca reutilices la contraseña del email</li>
                        <li><strong>2FA activado:</strong> Usa app autenticadora, no SMS</li>
                        <li><strong>Email de recuperación:</strong> Uno diferente y seguro</li>
                        <li><strong>Número de teléfono:</strong> Actualizado para recuperación</li>
                        <li><strong>Preguntas de seguridad:</strong> Respuestas que no sean públicas</li>
                        <li><strong>Alertas de inicio:</strong> Notificaciones de accesos</li>
                    </ul>

                    <h3>Proveedores de email seguros:</h3>
                    <ul>
                        <li><strong>ProtonMail:</strong> ✅ Cifrado de extremo a extremo, basado en Suiza</li>
                        <li><strong>Tutanota:</strong> ✅ Cifrado automático, código abierto</li>
                        <li><strong>Gmail:</strong> Seguro con 2FA, pero escanea contenido</li>
                        <li><strong>Outlook:</strong> Seguro con configuración adecuada</li>
                        <li><strong>FastMail:</strong> Privacidad mejorada, de pago</li>
                    </ul>

                    <h3>Prácticas seguras de email:</h3>
                    <ul>
                        <li>No abras adjuntos de remitentes desconocidos</li>
                        <li>Verifica remitente antes de hacer clic en enlaces</li>
                        <li>No compartas información sensible sin cifrar</li>
                        <li>Usa BCC para listas largas (protege privacidad)</li>
                        <li>Borra correos sospechosos inmediatamente</li>
                        <li>Revisa sesiones activas regularmente</li>
                    </ul>

                    <h3>Cifrado de correos importantes:</h3>
                    <ul>
                        <li><strong>PGP/GPG:</strong> Estándar de cifrado para email</li>
                        <li><strong>S/MIME:</strong> Alternativa empresarial</li>
                        <li><strong>ProtonMail:</strong> Cifrado automático entre usuarios</li>
                        <li><strong>Password-protected:</strong> Envía contraseña por canal separado</li>
                        <li>Usa para: datos financieros, información médica, documentos legales</li>
                    </ul>

                    <h3>Organización y limpieza:</h3>
                    <ul>
                        <li>Desuscríbete de newsletters innecesarios</li>
                        <li>Usa filtros y etiquetas para organizar</li>
                        <li>Archiva correos antiguos importantes</li>
                        <li>Borra correos con información sensible después de usarlos</li>
                        <li>Revisa y limpia papelera regularmente</li>
                    </ul>

                    <h3>Correos de alerta que DEBES revisar:</h3>
                    <div class="example-box">
                        ⚠️ "Inicio de sesión desde nuevo dispositivo"<br>
                        ⚠️ "Cambio de contraseña solicitado"<br>
                        ⚠️ "Nueva aplicación conectada"<br>
                        ⚠️ "Cambio en configuración de seguridad"<br>
                        ⚠️ "Intento de acceso bloqueado"
                    </div>

                    <h3>Direcciones de email estratégicas:</h3>
                    <ul>
                        <li><strong>Principal:</strong> Servicios importantes (banco, trabajo)</li>
                        <li><strong>Secundario:</strong> Compras online y suscripciones</li>
                        <li><strong>Descartable:</strong> Registros dudosos o pruebas</li>
                        <li><strong>Profesional:</strong> Solo para trabajo/networking</li>
                        <li><strong>Familia:</strong> Compartido para asuntos familiares</li>
                    </ul>

                    <h3>Alias de email:</h3>
                    <ul>
                        <li>Gmail: usa +etiqueta (ejemplo@gmail.com → ejemplo+netflix@gmail.com)</li>
                        <li>Identifica quién filtró tu correo</li>
                        <li>Facilita filtrado y organización</li>
                        <li>Desactiva alias comprometidos</li>
                    </ul>

                    <h3>Señales de cuenta comprometida:</h3>
                    <ul>
                        <li>Correos en "Enviados" que no escribiste</li>
                        <li>Contactos reciben spam de tu cuenta</li>
                        <li>Cambios en configuración no autorizados</li>
                        <li>Filtros o reglas desconocidas</li>
                        <li>Emails importantes eliminados automáticamente</li>
                        <li>Sesiones activas desde ubicaciones extrañas</li>
                    </ul>

                    <h3>Qué hacer si tu email fue hackeado:</h3>
                    <ul>
                        <li><strong>1.</strong> Cambia contraseña INMEDIATAMENTE</li>
                        <li><strong>2.</strong> Cierra todas las sesiones activas</li>
                        <li><strong>3.</strong> Revisa y elimina aplicaciones conectadas</li>
                        <li><strong>4.</strong> Activa/refuerza 2FA</li>
                        <li><strong>5.</strong> Revisa filtros y reglas de reenvío</li>
                        <li><strong>6.</strong> Notifica a tus contactos</li>
                        <li><strong>7.</strong> Cambia contraseñas de otros servicios</li>
                        <li><strong>8.</strong> Monitorea actividad bancaria</li>
                    </ul>

                    <h3>Permisos de aplicaciones:</h3>
                    <ul>
                        <li>Revisa apps conectadas cada 3 meses</li>
                        <li>Elimina apps que no uses</li>
                        <li>Desconfía de apps que piden acceso total</li>
                        <li>Lee permisos antes de aceptar</li>
                        <li>Usa apps oficiales cuando sea posible</li>
                    </ul>

                    <h3>Backup de emails importantes:</h3>
                    <ul>
                        <li>Exporta correos críticos periódicamente</li>
                        <li>Usa formato estándar (MBOX, PST)</li>
                        <li>Almacena backup cifrado offline</li>
                        <li>Incluye adjuntos importantes</li>
                        <li>Documenta proceso de restauración</li>
                    </ul>
                `
            },
            browse1: {
                title: "Navegación Web Segura",
                icon: "🌐",
                content: `
                    <h3>Fundamentos de navegación segura</h3>
                    <p>Tu navegador es la puerta de entrada a internet. Una configuración inadecuada o malos hábitos de navegación pueden exponer tus datos a atacantes, rastreadores y malware.</p>

                    <h3>Navegadores recomendados (2025):</h3>
                    <ul>
                        <li><strong>Brave:</strong> ✅ Privacidad por defecto, bloqueo de ads integrado</li>
                        <li><strong>Firefox:</strong> ✅ Open source, enfocado en privacidad</li>
                        <li><strong>Chrome:</strong> Rápido pero recopila muchos datos</li>
                        <li><strong>Edge:</strong> Basado en Chromium, mejorado en seguridad</li>
                        <li><strong>Safari:</strong> Bueno para ecosistema Apple</li>
                        <li><strong>Tor Browser:</strong> Máxima privacidad y anonimato</li>
                    </ul>

                    <h3>Configuración esencial de seguridad:</h3>
                    <ul>
                        <li><strong>Actualizaciones automáticas:</strong> Siempre habilitadas</li>
                        <li><strong>HTTPS-only:</strong> Forzar conexiones seguras</li>
                        <li><strong>Bloqueador de pop-ups:</strong> Activado</li>
                        <li><strong>Advertencias de malware:</strong> No desactivar</li>
                        <li><strong>Cookies de terceros:</strong> Bloqueadas</li>
                        <li><strong>Rastreo:</strong> Do Not Track habilitado</li>
                        <li><strong>Autocompletar:</strong> Desactivado en computadoras compartidas</li>
                    </ul>

                    <h3>Extensiones de seguridad imprescindibles:</h3>
                    <ul>
                        <li><strong>uBlock Origin:</strong> ✅ Bloqueador de ads y rastreadores</li>
                        <li><strong>HTTPS Everywhere:</strong> Fuerza conexiones HTTPS</li>
                        <li><strong>Privacy Badger:</strong> Bloquea rastreadores</li>
                        <li><strong>Bitwarden:</strong> Gestor de contraseñas</li>
                        <li><strong>Decentraleyes:</strong> Protección contra CDN tracking</li>
                        <li><strong>ClearURLs:</strong> Elimina parámetros de rastreo</li>
                    </ul>

                    <h3>Verificación de sitios seguros:</h3>
                    <div class="example-box">
                        ✅ https:// (con candado) = Conexión cifrada<br>
                        ❌ http:// (sin candado) = Conexión NO segura<br>
                        🔍 Clic en candado → Ver certificado<br>
                        ⚠️ Candado no garantiza que el sitio sea legítimo
                    </div>

                    <h3>Señales de sitios web peligrosos:</h3>
                    <ul>
                        <li>URLs extrañas o con muchos números/guiones</li>
                        <li>Certificado SSL inválido o expirado</li>
                        <li>Pop-ups excesivos y agresivos</li>
                        <li>Solicitudes inmediatas de permisos</li>
                        <li>Descargas automáticas al visitar</li>
                        <li>Diseño pobre o lleno de errores</li>
                        <li>Ofertas demasiado buenas para ser verdad</li>
                        <li>Solicitud de información innecesaria</li>
                    </ul>

                    <h3>Modos de navegación:</h3>
                    <ul>
                        <li><strong>Normal:</strong> Para uso diario, guarda historial</li>
                        <li><strong>Incógnito/Privado:</strong> No guarda historial local (pero no es anónimo)</li>
                        <li><strong>Tor Browser:</strong> Para verdadero anonimato</li>
                        <li><strong>VPN activada:</strong> Para privacidad adicional</li>
                    </ul>

                    <h3>⚠️ Mitos sobre modo incógnito:</h3>
                    <ul>
                        <li>❌ NO te hace anónimo en internet</li>
                        <li>❌ Tu ISP PUEDE ver tu actividad</li>
                        <li>❌ Los sitios web PUEDEN rastrearte</li>
                        <li>✅ Solo evita guardar historial localmente</li>
                        <li>✅ Útil para probar sitios web como usuario no logueado</li>
                    </ul>

                    <h3>Gestión de cookies y caché:</h3>
                    <ul>
                        <li>Limpia cookies y caché periódicamente</li>
                        <li>Configura limpieza automática al cerrar</li>
                        <li>Acepta solo cookies necesarias</li>
                        <li>Revisa qué sitios tienen cookies guardadas</li>
                        <li>Usa contenedores de cookies (Firefox Multi-Account Containers)</li>
                    </ul>

                    <h3>Descargas seguras:</h3>
                    <ul>
                        <li>Solo descarga de sitios oficiales y confiables</li>
                        <li>Verifica URL antes de descargar</li>
                        <li>Lee permisos antes de instalar</li>
                        <li>Escanea archivos con antivirus antes de abrir</li>
                        <li>Desconfía de extensiones .exe, .bat, .scr</li>
                        <li>No ejecutes macros de Office de fuentes desconocidas</li>
                    </ul>

                    <h3>Permisos del navegador:</h3>
                    <ul>
                        <li><strong>Ubicación:</strong> Solo concede a sitios confiables</li>
                        <li><strong>Cámara/Micrófono:</strong> Otorga con precaución</li>
                        <li><strong>Notificaciones:</strong> Bloquea la mayoría</li>
                        <li><strong>Autoplay:</strong> Desactivado</li>
                        <li><strong>Descarga automática:</strong> Pedir confirmación</li>
                        <li>Revisa y revoca permisos regularmente</li>
                    </ul>

                    <h3>Protección contra fingerprinting:</h3>
                    <ul>
                        <li>Usa navegadores enfocados en privacidad</li>
                        <li>Instala extensiones anti-fingerprinting</li>
                        <li>Desactiva WebRTC si no lo necesitas</li>
                        <li>Usa resoluciones de pantalla comunes</li>
                        <li>Limita plugins y extensiones visibles</li>
                    </ul>

                    <h3>Buenas prácticas generales:</h3>
                    <ul>
                        <li>No hagas clic en ads sospechosos</li>
                        <li>Cierra sesión al terminar en sitios importantes</li>
                        <li>No guardes contraseñas en computadoras públicas</li>
                        <li>Verifica URLs copiadas (puede haber caracteres invisibles)</li>
                        <li>Desconfía de acortadores de URL</li>
                        <li>Lee políticas de privacidad de sitios importantes</li>
                        <li>Usa motor de búsqueda privado (DuckDuckGo, Startpage)</li>
                    </ul>

                    <h3>Señales de navegador comprometido:</h3>
                    <ul>
                        <li>Página de inicio cambiada sin tu permiso</li>
                        <li>Motor de búsqueda predeterminado alterado</li>
                        <li>Extensiones desconocidas instaladas</li>
                        <li>Redirecciones constantes a sitios extraños</li>
                        <li>Anuncios excesivos en todos los sitios</li>
                        <li>Toolbars no solicitadas</li>
                    </ul>

                    <h3>Qué hacer si tu navegador fue infectado:</h3>
                    <ul>
                        <li><strong>1.</strong> Desinstala extensiones sospechosas</li>
                        <li><strong>2.</strong> Restablece configuración del navegador</li>
                        <li><strong>3.</strong> Limpia cookies y caché</li>
                        <li><strong>4.</strong> Escanea sistema con antivirus</li>
                        <li><strong>5.</strong> Cambia contraseñas guardadas</li>
                        <li><strong>6.</strong> Considera reinstalar el navegador</li>
                    </ul>
                `
            }
        });
    </script>
</body>
</html>
<?php include __DIR__ . "/../../includes/footer.php"; ?>