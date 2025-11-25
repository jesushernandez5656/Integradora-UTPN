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
    <!-- TODO TU CSS ACTUAL (sin cambios) -->
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
          padding: clamp(16px, 3vw, 28px);
        }

        .grid {
          display: grid;
          gap: clamp(16px, 2.2vw, 28px);
        }

        .grid-2 {
          grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grid-3 {
          grid-template-columns: repeat(3, minmax(0, 1fr));
        }

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

        .nav {
          position: sticky;
          top: 0;
          backdrop-filter: saturate(140%) blur(8px);
          background: rgba(64, 224, 208, 0.5);
          border-bottom: 1px solid var(--stroke);
          z-index: 30;
        }

        .nav__inner {
          display: flex;
          align-items: center;
          justify-content: space-between;
        }

        .nav__links {
          display: flex;
          gap: 18px;
          align-items: center;
        }

        .nav__links a {
          color: var(--txt);
          text-decoration: none;
          opacity: .9;
        }

        .nav__links a:hover { opacity: 1; }

        .brand {
          display: flex;
          align-items: center;
          gap: .5ch;
          font-weight: 800;
          letter-spacing: .3px;
          text-decoration: none;
          color: var(--txt);
        }

        .brand__accent {
          background: linear-gradient(90deg, var(--brand), var(--brand-2));
          -webkit-background-clip: text;
          background-clip: text;
          color: transparent;
        }

        .brand__dot {
          width: 12px;
          height: 12px;
          border-radius: 999px;
          background: linear-gradient(90deg, var(--brand), var(--brand-2));
          box-shadow: 0 0 18px rgba(124,58,237,.7);
        }

        .hero {
          position: relative;
          padding: clamp(48px, 8vw, 96px) 0;
        }

        .hero__copy h1 {
          font-size: clamp(32px, 5vw, 56px);
          line-height: 1.05;
          margin: .2em 0 .3em;
        }

        .hero__copy p {
          font-size: clamp(16px, 1.6vw, 18px);
        }

        .chip {
          display: inline-block;
          padding: 8px 12px;
          border-radius: 999px;
          background: linear-gradient(90deg, rgba(124,58,237,.18), rgba(6,182,212,.18));
          border: 1px solid var(--stroke);
          font-weight: 700;
          color: #dfe7ff;
        }

        .grad {
          background: linear-gradient(90deg, #AE874C, #AE874C);
          -webkit-background-clip: text;
          background-clip: text;
          color: transparent;
        }

        .grad.alt { filter: saturate(140%); }

        .search {
          margin-top: 18px;
          display: flex;
          gap: 10px;
          align-items: center;
        }

        .search input {
          flex: 1;
          padding: 14px 16px;
          border-radius: 14px;
          border: 1px solid var(--stroke);
          background: rgba(255,255,255,.04);
          color: var(--txt);
          outline: none;
        }

        .search input::placeholder { color: #9aa4b8; }

        .search button svg { margin-top: -2px; }

        .hero__badges {
          display: flex;
          flex-wrap: wrap;
          gap: 10px;
          margin-top: 14px;
        }

        .badge {
          padding: 8px 12px;
          border-radius: 12px;
          background: rgba(10, 240, 133, 0.05);
          border: 1px solid var(--stroke);
        }

        .badge.ok {
          border-color: rgba(34,197,94,.4);
          box-shadow: 0 0 0 1px rgba(34,197,94,.25) inset;
        }

        .badge.warn {
          border-color: rgba(245,158,11,.4);
          box-shadow: 0 0 0 1px rgba(245,158,11,.25) inset;
        }

        .hero__visual {
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .glass {
          background: var(--glass);
          border: 1px solid var(--stroke);
          border-radius: var(--radius);
          box-shadow: var(--shadow);
        }

        .card--stack {
          position: relative;
          aspect-ratio: 4/3;
          min-height: 360px;
          display: grid;
          place-items: center;
          padding: 24px;
          overflow: hidden;
        }

        .card--stack .card {
          position: absolute;
          inset: auto 24px 24px 24px;
          padding: 22px;
          border-radius: 20px;
          background: rgba(7,12,25,.7);
          border: 1px solid var(--stroke);
          transform: rotate(-2deg) translateY(-6px);
          animation: float 6s ease-in-out infinite;
        }

        .card--stack .card.delay {
          inset: 24px;
          transform: rotate(2deg) translateY(6px);
          animation-delay: 1.2s;
        }

        .card h3 {
          margin: 0 0 6px;
          font-size: 20px;
        }

        .card p {
          margin: 0 0 12px;
          color: var(--muted);
        }

        .card .meta {
          display: flex;
          flex-wrap: wrap;
          gap: 10px;
          margin: 10px 0 14px;
        }

        .card .meta span {
          padding: 6px 10px;
          border: 1px solid var(--stroke);
          border-radius: 999px;
          background: rgba(255,255,255,.04);
        }

        @keyframes float {
          0%, 100% { transform: translateY(-4px) rotate(-2deg); }
          50% { transform: translateY(4px) rotate(-2deg); }
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

        .footer {
          border-top: 1px solid var(--stroke);
          padding: 32px 0;
          background: rgba(255,255,255,.02);
        }

        .footer__inner {
          display: grid;
          gap: 10px;
          align-items: center;
          justify-items: center;
        }

        .footer__links {
          display: flex;
          gap: 16px;
          flex-wrap: wrap;
        }

        .footer__links a {
          color: var(--muted);
          text-decoration: none;
        }

        .brand--footer {
          font-weight: 800;
        }

        .hero__blur {
          position: absolute;
          filter: blur(60px);
          opacity: .7;
          pointer-events: none;
        }

        .hero__blur--1 {
          width: 420px;
          height: 420px;
          background: radial-gradient(closest-side, rgba(124,58,237,.4), transparent);
          top: -80px;
          right: 5%;
        }

        .hero__blur--2 {
          width: 360px;
          height: 360px;
          background: radial-gradient(closest-side, rgba(6,182,212,.35), transparent);
          bottom: -60px;
          left: 8%;
        }

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
    </style>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="container grid grid-2">
            <div class="hero__copy">
                <?php if (!empty($pagina['hero']['chip_texto'])): ?>
                    <div class="chip"><?php echo htmlspecialchars($pagina['hero']['chip_texto']); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($pagina['hero']['titulo_principal'])): ?>
                    <h1><?php echo $pagina['hero']['titulo_principal']; ?></h1>
                <?php endif; ?>
                
                <?php if (!empty($pagina['hero']['descripcion'])): ?>
                    <p class="muted"><?php echo htmlspecialchars($pagina['hero']['descripcion']); ?></p>
                <?php endif; ?>

                <?php if (!empty($pagina['hero']['insignias'])): ?>
                    <div class="hero__badges">
                        <?php foreach ($pagina['hero']['insignias'] as $insignia): ?>
                            <span class="badge <?php echo $insignia['clase']; ?>">
                                <?php echo htmlspecialchars($insignia['texto']); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="hero__visual">
                <?php if (!empty($pagina['hero']['tarjetas_ejemplo'])): ?>
                    <div class="glass card--stack">
                        <?php foreach ($pagina['hero']['tarjetas_ejemplo'] as $index => $tarjeta): ?>
                            <div class="card <?php echo $index === 1 ? 'delay' : ''; ?>">
                                <h3><?php echo htmlspecialchars($tarjeta['titulo']); ?></h3>
                                <p><?php echo htmlspecialchars($tarjeta['descripcion']); ?></p>
                                <div class="meta">
                                    <?php foreach ($tarjeta['metadata'] as $meta): ?>
                                        <span><?php echo htmlspecialchars($meta); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <?php if ($tarjeta['enlace_texto'] && $tarjeta['enlace_url']): ?>
                                    <a class="btn ghost" href="<?php echo htmlspecialchars($tarjeta['enlace_url']); ?>" target="_blank">
                                        <?php echo htmlspecialchars($tarjeta['enlace_texto']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="hero__blur hero__blur--1"></div>
        <div class="hero__blur hero__blur--2"></div>
    </section>

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
                                <a class="btn block" href="<?php echo htmlspecialchars($beca['enlace_descarga_requisitos']); ?>" <?php echo strpos($beca['enlace_descarga_requisitos'], 'http') === 0 ? 'target="_blank"' : 'download'; ?>>
                                    <?php echo strpos($beca['enlace_descarga_requisitos'], 'http') === 0 ? 'Ver requisitos' : 'Descargar requisitos'; ?>
                                </a>
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

    <!-- CHATBOT -->
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

        // Tu código JavaScript del chatbot (sin cambios)
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