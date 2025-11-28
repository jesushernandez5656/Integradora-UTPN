<?php 
include "../../includes/header.php"; 

// Ruta del archivo JSON
$json_file = '../../assets/js/becas.json';

// Cargar datos desde el JSON
$data = [];
if (file_exists($json_file)) {
    $json_data = file_get_contents($json_file);
    $data = json_decode($json_data, true);
}

// Usar la página original del JSON
$pagina = $data['pagina_original'] ?? [];

// Si no existe la página original, usar estructura vacía
if (!$pagina) {
    $pagina = [
        'titulo_pagina' => 'Becas Universitarias | Impulsa tu camino',
        'hero' => [
            'chip_texto' => '',
            'titulo_principal' => '',
            'descripcion' => '',
            'insignias' => [],
            'tarjetas_ejemplo' => []
        ],
        'seccion_becas_destacadas' => [
            'titulo' => '',
            'subtitulo' => '',
            'becas' => []
        ],
        'seccion_asesorias' => [
            'titulo_seccion' => '',
            'caracteristicas' => []
        ],
        'chatbot' => [
            'nombre' => '',
            'opciones_iniciales' => [],
            'respuestas_pregrabadas' => []
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pagina['titulo_pagina']); ?></title>
    <link rel="stylesheet" href="/INTEGRADORA-UTPN/assets/css/header.css">
    <link rel="stylesheet" href="/INTEGRADORA-UTPN/assets/css/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>
    <style>
        :root {
          --bg: #f1CBA5;
          --bg-2: #c79c4dff;
          --txt: #1d1f24ff;
          --muted: #a6b0c3;
          --brand: #d0d1d1;
          --brand-2: #7e8080;
          --ok: #22c55e;
          --warn: #f59e0b;
          --glass: #ddbf87ff;
          --stroke: rgba(189, 145, 63, 0.12);
          --shadow: 0 10px 30px rgba(230, 167, 73, 0.35);
          --radius: 24px;
          --max: 1200px;
        }

        * {
          box-sizing: border-box;
        }

        html {
          scroll-behavior: smooth;
        }

        body {
          margin: 0;
          font-family: 'Plus Jakarta Sans', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, "Helvetica Neue", Arial, sans-serif;
          color: var(--txt);
          background-color: #EDE5D6;
        }

        .container {
          width: 100%;
          max-width: var(--max);
          margin-inline: auto;
          padding: clamp(12px, 3vw, 28px);
        }

        .grid {
          display: grid;
          gap: clamp(12px, 2.2vw, 28px);
        }

        .grid-2 {
          grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grid-3 {
          grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        /* ================================
           HERO COMPACTO (ESTILO BECA ACCESO UNIVERSIDAD)
        ================================ */
        .hero {
          position: relative;
          padding: clamp(20px, 4vw, 40px) 0;
          background: linear-gradient(135deg, #f8f4ec 0%, #EDE5D6 100%);
        }

        .hero-compact {
          display: flex;
          flex-direction: column;
          gap: 20px;
        }

        .hero-main {
          display: grid;
          grid-template-columns: 1fr auto;
          gap: 20px;
          align-items: center;
        }

        .hero-content {
          flex: 1;
        }

        .hero-badge {
          display: inline-block;
          padding: 6px 12px;
          background: linear-gradient(135deg, #AE874C, #c79c4d);
          color: white;
          border-radius: 20px;
          font-size: 12px;
          font-weight: 700;
          margin-bottom: 15px;
        }

        .hero-title {
          font-size: clamp(20px, 4vw, 32px);
          line-height: 1.2;
          margin: 0 0 10px 0;
          color: var(--txt);
        }

        .hero-title .highlight {
          background: linear-gradient(135deg, #AE874C, #c79c4d);
          -webkit-background-clip: text;
          background-clip: text;
          color: transparent;
          font-weight: 800;
        }

        .hero-description {
          color: var(--muted);
          font-size: clamp(14px, 1.8vw, 16px);
          line-height: 1.4;
          margin: 0 0 15px 0;
        }

        .hero-features {
          display: flex;
          gap: 10px;
          flex-wrap: wrap;
          margin-bottom: 20px;
        }

        .feature-tag {
          padding: 4px 10px;
          background: rgba(174, 135, 76, 0.1);
          border: 1px solid rgba(174, 135, 76, 0.3);
          border-radius: 12px;
          font-size: 11px;
          font-weight: 600;
          color: #AE874C;
        }

        .feature-tag.success {
          background: rgba(34, 197, 94, 0.1);
          border-color: rgba(34, 197, 94, 0.3);
          color: #16a34a;
        }

        .feature-tag.warning {
          background: rgba(245, 158, 11, 0.1);
          border-color: rgba(245, 158, 11, 0.3);
          color: #d97706;
        }

        .hero-card {
          background: var(--glass);
          border: 1px solid var(--stroke);
          border-radius: 16px;
          padding: 16px;
          box-shadow: var(--shadow);
          min-width: 280px;
          max-width: 320px;
        }

        .card-compact {
          background: rgba(255, 255, 255, 0.9);
          border-radius: 12px;
          padding: 16px;
          border: 1px solid rgba(189, 145, 63, 0.2);
        }

        .card-compact h3 {
          font-size: 16px;
          margin: 0 0 8px 0;
          color: var(--txt);
          font-weight: 700;
        }

        .card-compact p {
          font-size: 13px;
          color: var(--muted);
          margin: 0 0 12px 0;
          line-height: 1.3;
        }

        .card-meta {
          display: flex;
          gap: 8px;
          flex-wrap: wrap;
          margin-bottom: 15px;
        }

        .meta-item {
          padding: 4px 8px;
          background: rgba(174, 135, 76, 0.1);
          border-radius: 8px;
          font-size: 11px;
          font-weight: 600;
          color: #AE874C;
        }

        .card-action {
          width: 100%;
          padding: 10px 16px;
          background: linear-gradient(135deg, #d0d1d1, #7e8080);
          color: white;
          border: none;
          border-radius: 10px;
          font-weight: 700;
          font-size: 13px;
          cursor: pointer;
          text-decoration: none;
          text-align: center;
          display: block;
          transition: transform 0.2s ease;
        }

        .card-action:hover {
          transform: translateY(-2px);
          color: white;
        }

        /* ================================
           RESPONSIVE HERO
        ================================ */
        @media (max-width: 768px) {
          .hero-main {
            grid-template-columns: 1fr;
            gap: 15px;
          }
          
          .hero-card {
            max-width: 100%;
            order: -1;
          }
        }

        @media (max-width: 480px) {
          .hero {
            padding: 15px 0;
          }
          
          .hero-compact {
            gap: 15px;
          }
          
          .hero-features {
            gap: 6px;
          }
          
          .feature-tag {
            font-size: 10px;
            padding: 3px 8px;
          }
          
          .card-compact {
            padding: 12px;
          }
        }

        /* ================================
           RESTO DEL ESTILO (MANTENER ORIGINAL)
        ================================ */
        @media (max-width:1024px) {
          .grid-3 { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width:720px) {
          .grid-2, .grid-3 { grid-template-columns: 1fr; }
        }

        .muted { color: var(--muted); }
        .small { font-size: .9rem; }
        .block { display: block; width: 100%; }

        .btn {
          --bgbtn: linear-gradient(90deg, var(--brand), var(--brand-2));
          display: inline-flex;
          align-items: center;
          gap: 10px;
          background: var(--bgbtn);
          color: white;
          text-decoration: none;
          font-weight: 700;
          padding: 14px 20px;
          border-radius: 16px;
          border: 1px solid transparent;
          box-shadow: var(--shadow);
          transform: translateZ(0);
          transition: transform .2s ease, filter .2s ease;
        }

        .btn.small {
          padding: 10px 14px;
          font-weight: 700;
        }

        .btn:hover {
          filter: brightness(1.08);
          transform: translateY(-2px);
        }

        .btn.ghost {
          background: transparent;
          border-color: var(--stroke);
          font-weight: 600;
        }

        .section {
          padding: clamp(40px, 7vw, 84px) 0;
        }

        .section.alt {
          background: linear-gradient(180deg, rgba(255,255,255,.02), rgba(255,255,255,0));
        }

        .section__head {
          display: flex;
          align-items: baseline;
          justify-content: space-between;
          gap: 16px;
          margin-bottom: 20px;
        }

        .section__head h2 {
          margin: 0;
          font-size: clamp(24px, 3.5vw, 36px);
        }

        .cards { margin-top: 10px; }

        .card2 {
          padding: 22px;
          border-radius: 18px;
          background: rgba(255,255,255,.04);
          border: 1px solid var(--stroke);
          box-shadow: var(--shadow);
          display: flex;
          flex-direction: column;
          gap: 12px;
        }

        .card2__head {
          display: flex;
          justify-content: space-between;
          align-items: center;
        }

        .card2__head h3 { margin: 0; }

        .pill {
          padding: 6px 10px;
          border-radius: 999px;
          background: linear-gradient(90deg, var(--brand), var(--brand-2));
          font-weight: 700;
        }

        .list {
          margin: 0 0 8px 0;
          padding: 0;
          list-style: none;
          color: var(--muted);
        }

        .list li {
          padding-left: 14px;
          position: relative;
          margin: .25rem 0;
        }

        .list li:before {
          content: '•';
          position: absolute;
          left: 0;
          opacity: .6;
        }

        .feature {
          padding: 22px;
          border-radius: 18px;
          border: 1px solid var(--stroke);
          background: rgba(255,255,255,.04);
        }

        .feature h3 {
          margin: .2rem 0 .4rem;
        }

        .feature p {
          margin: 0;
          color: var(--muted);
        }

        .feature .ico {
          width: 36px;
          height: 36px;
          border-radius: 12px;
          background: linear-gradient(90deg, var(--brand), var(--brand-2));
          box-shadow: var(--shadow);
          margin-bottom: 10px;
        }

        /* ================================
           CHATBOT (MANTENER MEJORADO)
        ================================ */
        .floating-chat {
         position: fixed;
         bottom: 20px;
         right: 20px;
         background: linear-gradient(135deg, var(--brand), var(--brand-2));
         color: white;
         width: 56px;
         height: 56px;
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         cursor: pointer;
         box-shadow: var(--shadow);
         z-index: 9999;
         transition: all 0.3s ease;
         border: 2px solid rgba(255, 255, 255, 0.5);
        }

        .floating-chat.expand {
         width: 60px;
         height: 60px;
         transform: scale(1.05);
        }

        .floating-chat i {
         font-size: 24px;
         transition: transform 0.3s ease;
        }

        .floating-chat:hover {
         filter: brightness(1.1);
         transform: translateY(-2px);
        }

        .floating-chat .chat {
         position: absolute;
         bottom: 70px;
         right: 0;
         width: min(100vw - 40px, 340px);
         max-height: 480px;
         background: var(--glass);
         backdrop-filter: blur(10px);
         color: var(--txt);
         border-radius: var(--radius);
         border: 1px solid var(--stroke);
         box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
         display: none;
         flex-direction: column;
         overflow: hidden;
         animation: fadeIn .3s ease-out;
        }

        @keyframes fadeIn {
         from { opacity: 0; transform: translateY(10px); }
         to { opacity: 1; transform: translateY(0); }
        }

        .floating-chat .chat .header {
         background: var(--bg-2);
         color: white;
         padding: 14px 18px;
         display: flex;
         justify-content: space-between;
         align-items: center;
         font-weight: 700;
        }

        .floating-chat .chat .header .title {
         display: flex;
         align-items: center;
         gap: 8px;
        }

        .floating-chat .chat .header button {
         background: transparent;
         border: none;
         color: white;
         font-size: 18px;
         cursor: pointer;
         opacity: 0.8;
         transition: opacity 0.2s;
        }

        .floating-chat .chat .header button:hover {
         opacity: 1;
        }

        .floating-chat .chat .messages {
         list-style: none;
         padding: 15px;
         margin: 0;
         flex: 1;
         overflow-y: auto;
         font-size: 15px;
         line-height: 1.5;
         scroll-behavior: smooth;
        }

        .floating-chat .chat .messages::-webkit-scrollbar { width: 6px; }
        .floating-chat .chat .messages::-webkit-scrollbar-thumb {
         background-color: rgba(0, 0, 0, 0.2);
         border-radius: 10px;
        }

        .floating-chat .chat .messages li {
         margin-bottom: 10px;
         padding: 10px 14px;
         border-radius: 16px;
         max-width: 85%;
         clear: both;
        }

        .floating-chat .chat .messages li.other {
         background: #f3f4f6;
         float: left;
         border-bottom-left-radius: 4px;
        }

        .floating-chat .chat .messages li.self {
         background: linear-gradient(90deg, var(--brand), var(--brand-2));
         color: white;
         float: right;
         border-bottom-right-radius: 4px;
        }

        .floating-chat .chat .footer {
         display: flex;
         border-top: 1px solid var(--stroke);
         padding: 10px;
         background-color: rgba(255, 255, 255, 0.8);
        }

        .floating-chat .chat .text-box {
         flex: 1;
         padding: 10px;
         background: transparent;
         border: 1px solid var(--stroke);
         border-radius: 10px;
         outline: none;
         font-size: 15px;
         resize: none;
         margin-right: 8px;
         transition: border-color 0.2s;
         line-height: 1.4;
         max-height: 80px;
         overflow-y: auto;
        }

        .floating-chat .chat .text-box:focus {
         border-color: var(--brand);
        }

        .floating-chat .chat button {
         background: linear-gradient(90deg, var(--brand), var(--brand-2));
         color: white;
         border: none;
         border-radius: 10px;
         padding: 0 16px;
         cursor: pointer;
         transition: transform 0.2s, filter 0.2s;
         font-weight: 700;
        }

        .floating-chat .chat button:hover {
         filter: brightness(1.1);
         transform: translateY(-1px);
        }

        .floating-chat .chat .messages li.typing {
         background: #f3f4f6;
         padding: 12px 16px;
         display: flex;
         gap: 4px;
         align-items: center;
         width: fit-content;
         float: left;
        }
        .floating-chat .chat .messages li.typing span {
         width: 8px;
         height: 8px;
         border-radius: 50%;
         background: var(--muted);
         animation: typing 1.4s infinite;
        }
        .floating-chat .chat .messages li.other a {
         color: var(--brand);
         text-decoration: underline;
        }
        .floating-chat .chat .messages li.other strong {
         color: var(--txt);
        }

        @keyframes typing {
          0%, 60%, 100% { transform: translateY(0); }
          30% { transform: translateY(-5px); }
        }
    </style>

    <!-- HERO SECTION COMPACTO (ESTILO BECA ACCESO UNIVERSIDAD) -->
    <section class="hero">
        <div class="container">
            <div class="hero-compact">
                <div class="hero-main">
                    <div class="hero-content">
                        <?php if (!empty($pagina['hero']['chip_texto'])): ?>
                            <div class="hero-badge"><?php echo htmlspecialchars($pagina['hero']['chip_texto']); ?></div>
                        <?php endif; ?>
                        
                        <?php if (!empty($pagina['hero']['titulo_principal'])): ?>
                            <h1 class="hero-title">
                                <?php echo $pagina['hero']['titulo_principal']; ?>
                            </h1>
                        <?php endif; ?>
                        
                        <?php if (!empty($pagina['hero']['descripcion'])): ?>
                            <p class="hero-description"><?php echo htmlspecialchars($pagina['hero']['descripcion']); ?></p>
                        <?php endif; ?>

                        <?php if (!empty($pagina['hero']['insignias'])): ?>
                            <div class="hero-features">
                                <?php foreach ($pagina['hero']['insignias'] as $insignia): ?>
                                    <span class="feature-tag <?php echo $insignia['clase']; ?>"><?php echo htmlspecialchars($insignia['texto']); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($pagina['hero']['tarjetas_ejemplo'])): ?>
                        <div class="hero-card">
                            <?php $tarjeta = $pagina['hero']['tarjetas_ejemplo'][0]; ?>
                            <div class="card-compact">
                                <h3><?php echo htmlspecialchars($tarjeta['titulo']); ?></h3>
                                <p><?php echo htmlspecialchars($tarjeta['descripcion']); ?></p>
                                <div class="card-meta">
                                    <?php foreach ($tarjeta['metadata'] as $meta): ?>
                                        <span class="meta-item"><?php echo htmlspecialchars($meta); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <?php if ($tarjeta['enlace_texto'] && $tarjeta['enlace_url']): ?>
                                    <a class="card-action" href="<?php echo htmlspecialchars($tarjeta['enlace_url']); ?>" target="_blank">
                                        <?php echo htmlspecialchars($tarjeta['enlace_texto']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- EL RESTO DE TU CÓDIGO SE MANTIENE IGUAL -->
    <!-- BECAS DESTACADAS SECTION -->
    <section id="becas" class="section">
    <div class="container">
        <div class="section__head">
            <?php if (!empty($pagina['seccion_becas_destacadas']['titulo'])): ?>
                <h2><?php echo htmlspecialchars($pagina['seccion_becas_destacadas']['titulo']); ?></h2>
            <?php endif; ?>
            
            <?php if (!empty($pagina['seccion_becas_destacadas']['subtitulo'])): ?>
                <p class="muted"><?php echo htmlspecialchars($pagina['seccion_becas_destacadas']['subtitulo']); ?></p>
            <?php endif; ?>
        </div>

        <?php if (!empty($pagina['seccion_becas_destacadas']['becas'])): ?>
            <div class="grid grid-3 cards">
                <?php foreach ($pagina['seccion_becas_destacadas']['becas'] as $beca): ?>
                    <article class="card2">
                        <div class="card2__head">
                            <h3><?php echo htmlspecialchars($beca['nombre']); ?></h3>
                            <span class="pill"><?php echo htmlspecialchars($beca['monto']); ?></span>
                        </div>
                        <p><?php echo htmlspecialchars($beca['resumen']); ?></p>
                        
                        <?php if (!empty($beca['requisitos'])): ?>
                            <ul class="list">
                                <?php foreach ($beca['requisitos'] as $requisito): ?>
                                    <li><?php echo htmlspecialchars($requisito); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        
                        <?php if (!empty($beca['enlace_postular'])): ?>
                            <a class="btn block" href="<?php echo htmlspecialchars($beca['enlace_postular']); ?>" target="_blank">Postular</a>
                        <?php endif; ?>
                        
                        <?php if (!empty($beca['enlace_descarga_requisitos'])): ?>
                            <?php if (strpos(strtolower($beca['nombre']), 'jóvenes escribiendo el futuro') !== false): ?>
                                <!-- Solo para Jóvenes Escribiendo el Futuro - Ver requisitos -->
                                <a class="btn block" href="<?php echo htmlspecialchars($beca['enlace_descarga_requisitos']); ?>" target="_blank">
                                    Ver requisitos
                                </a>
                            <?php else: ?>
                                <!-- Para todas las demás becas - Descargar requisitos -->
                                <a class="btn block" href="<?php echo htmlspecialchars($beca['enlace_descarga_requisitos']); ?>" <?php echo strpos($beca['enlace_descarga_requisitos'], 'http') === 0 ? 'target="_blank"' : 'download'; ?>>
                                    <?php echo strpos($beca['enlace_descarga_requisitos'], 'http') === 0 ? 'Ver requisitos' : 'Descargar requisitos'; ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

    <!-- ASESORÍAS SECTION -->
    <section id="asesorias" class="section alt">
        <div class="container grid grid-3">
            <?php if (!empty($pagina['seccion_asesorias']['caracteristicas'])): ?>
                <?php foreach ($pagina['seccion_asesorias']['caracteristicas'] as $caracteristica): ?>
                    <div class="feature">
                        <div class="ico"></div>
                        <h3><?php echo htmlspecialchars($caracteristica['titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($caracteristica['descripcion']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- CHATBOT MEJORADO -->
    <div class="floating-chat">
        <i class="fa-solid fa-graduation-cap" aria-hidden="true"></i>
        <div class="chat">
            <div class="header">
                <span class="title">
                    <i class="fa-solid fa-robot"></i> 
                    <?php echo !empty($pagina['chatbot']['nombre']) ? htmlspecialchars($pagina['chatbot']['nombre']) : 'UTPN-BOT'; ?>
                </span>
                <button>
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <ul class="messages">
                <li class="other">¡Hola! 👋 Soy tu asistente de becas. Selecciona una opción o escribe tu pregunta:</li>
                <?php if (!empty($pagina['chatbot']['opciones_iniciales'])): ?>
                    <?php foreach ($pagina['chatbot']['opciones_iniciales'] as $opcion): ?>
                        <li class="other"><?php echo htmlspecialchars($opcion); ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <div class="footer">
                <textarea class="text-box" placeholder="Escribe tu mensaje..." rows="1"></textarea>
                <button id="sendMessage"><i class="fa fa-paper-plane"></i></button>
            </div>
        </div>
    </div>

    <script>
        // Configuración del chatbot desde JSON
        var respuestas = <?php echo !empty($pagina['chatbot']['respuestas_pregrabadas']) ? json_encode($pagina['chatbot']['respuestas_pregrabadas']) : '{}'; ?>;

        $(function(){
            var element = $('.floating-chat');
            var isOpen = false;
            
            element.find('.chat').click(function(e) {
                e.stopPropagation();
            });

            element.click(toggleChat);

            setTimeout(function() {
                element.addClass('enter');
            }, 1000);

            function toggleChat() {
                if (isOpen) {
                    closeElement();
                } else {
                    openElement();
                }
            }

            function openElement() {
                var messages = element.find('.messages');
                var textInput = element.find('.text-box');
                
                element.find('>i').removeClass('fa-graduation-cap').addClass('fa-times');
                element.addClass('expand');
                element.find('.chat').css('display', 'flex');
                textInput.prop("disabled", false).focus();
                element.find('.header button').click(closeElement);
                element.find('#sendMessage').click(sendNewMessage);
                messages.scrollTop(messages.prop("scrollHeight"));
                
                textInput.keydown(onEnterPress);
                textInput.on('input', autoResizeTextarea);
                autoResizeTextarea.call(textInput.get(0));
                
                isOpen = true;
            }

            function closeElement() {
                element.find('.chat').css('display', 'none');
                element.find('>i').removeClass('fa-times').addClass('fa-graduation-cap');
                element.removeClass('expand');
                element.find('.header button').off('click', closeElement);
                element.find('#sendMessage').off('click', sendNewMessage);
                element.find('.text-box').off('keydown', onEnterPress).off('input', autoResizeTextarea).prop("disabled", true).blur();
                isOpen = false;
            }

            function autoResizeTextarea() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
                if (this.scrollHeight > 80) {
                    this.style.overflowY = 'auto';
                } else {
                    this.style.overflowY = 'hidden';
                }
            }

            function sendNewMessage() {
                var userInput = $('.text-box');
                var newMessage = userInput.val().trim();

                if (!newMessage) return;

                var messagesContainer = $('.messages');
                
                messagesContainer.append('<li class="self">' + newMessage.replace(/\n/g, '<br>') + '</li>');
                
                userInput.val('');
                autoResizeTextarea.call(userInput.get(0));
                
                messagesContainer.scrollTop(messagesContainer.prop("scrollHeight"));

                messagesContainer.append('<li class="other typing"><span></span><span></span><span></span></li>');
                messagesContainer.scrollTop(messagesContainer.prop("scrollHeight"));

                setTimeout(function() {
                    messagesContainer.find('.typing').remove();
                    
                    var respuesta = generarRespuesta(newMessage.toLowerCase());
                    
                    messagesContainer.append('<li class="other">' + respuesta + '</li>');
                    messagesContainer.scrollTop(messagesContainer.prop("scrollHeight"));
                }, 800 + Math.random() * 400); 
            }

            function generarRespuesta(mensaje) {
                mensaje = mensaje.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                
                // Buscar respuesta exacta
                if (respuestas[mensaje]) {
                    return respuestas[mensaje];
                }

                // Buscar por número
                var numeros = mensaje.match(/\d+/);
                if (numeros && respuestas[numeros[0]]) {
                    return respuestas[numeros[0]];
                }

                // Respuesta por defecto
                return respuestas['default'] || '🤖 No estoy seguro de entender tu pregunta. Por favor, reformula tu pregunta. 😊';
            }

            function onEnterPress(event) {
                if (event.keyCode === 13 && !event.shiftKey) {
                    sendNewMessage();
                    event.preventDefault();
                }
            }
        });
    </script>
</body>
<?php include "../../includes/footer.php"; ?>
</html>