<?php 
// 🚨 NOTA: Asegúrate de reemplazar esta función con tu lógica de sesión real (e.g., if($_SESSION['user_role'] == 'admin')).
function isAdmin() {
    return isset($_GET['admin']) && $_GET['admin'] == 'true'; 
}

include "../../includes/header.php"; 
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Becas Universitarias | Impulsa tu camino</title> 
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
        /* ================================
        Variables
        ================================ */
        :root {
            --bg: #f1CBA5;
            --bg-2: #c79c4dff;
            --txt: #1d1f24ff;
            --muted: #a6b0c3;
            --brand: #d0d1d1;   /* morado */
            --brand-2: #7e8080; /* cian */
            --ok: #22c55e;
            --warn: #f59e0b;
            --glass: #ddbf87ff;
            --stroke: rgba(189, 145, 63, 0.12);
            --shadow: 0 10px 30px rgba(230, 167, 73, 0.35);
            --radius: 24px;
            --max: 1200px;
        }

        /* ================================
        Reset & Base
        ================================ */
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
            background-color: #EDE5D6; /* 🎨 crema claro, cálido y suave */
        }

        /* ================================
        Layout
        ================================ */
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

        /* ================================
        Utilities
        ================================ */
        .muted { color: var(--muted); }
        .small { font-size: .9rem; }
        .block { display: block; width: 100%; }

        /* ================================
        Buttons
        ================================ */
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
            color: var(--txt); /* Asegurar que el texto sea visible */
            font-weight: 600;
        }

        /* ================================
        Navigation
        ================================ */
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

        /* ================================
        Brand
        ================================ */
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

        /* ================================
        Hero
        ================================ */
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
            color: var(--txt); /* Asegurar color de texto */
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
            color: white; /* Asegurar texto blanco para las tarjetas del hero */
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

        /* ================================
        Sections
        ================================ */
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
            color: white;
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
            color: var(--txt); /* Asegurar color de texto */
        }

        .list li:before {
            content: '•';
            position: absolute;
            left: 0;
            opacity: .6;
            color: var(--brand); /* Color del bullet point */
        }

        /* ================================
        Features
        ================================ */
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
        Footer
        ================================ */
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

        /* ================================
        Decorative Blurs
        ================================ */
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

        /* --- CHAT FLOTANTE --- */
        .floating-chat {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #4f46e5;
            color: #fff;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            z-index: 9999;
            transition: transform 0.2s ease-in-out, background 0.3s;
        }

        .floating-chat:hover {
            background: #3730a3;
            transform: scale(1.1);
        }

        .floating-chat i {
            font-size: 26px;
        }

        /* --- CUADRO DE CHAT --- */
        .floating-chat .chat {
            position: absolute;
            bottom: 70px;
            right: 0;
            width: 300px;
            max-height: 400px;
            background: #fff;
            color: #333;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            display: none;
            flex-direction: column;
            overflow: hidden;
        }

        /* Header */
        .floating-chat .chat .header {
            background: #4f46e5;
            color: #fff;
            padding: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .floating-chat .chat .header button {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        /* Mensajes */
        .floating-chat .chat .messages {
            list-style: none;
            padding: 12px;
            margin: 0;
            flex: 1;
            overflow-y: auto;
            font-size: 14px;
        }

        .floating-chat .chat .messages li {
            margin-bottom: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            max-width: 75%;
        }

        .floating-chat .chat .messages li.other {
            background: #f3f4f6;
            align-self: flex-start;
        }

        .floating-chat .chat .messages li.self {
            background: #e0e7ff;
            align-self: flex-end;
            margin-left: auto; /* Para que se alinee a la derecha */
        }

        /* Footer */
        .floating-chat .chat .footer {
            display: flex;
            border-top: 1px solid #ddd;
        }

        .floating-chat .chat .text-box {
            flex: 1;
            padding: 10px;
            outline: none;
            border: none;
            resize: none;
            font-size: 14px;
        }

        .floating-chat .chat button {
            background: #4f46e5;
            color: #fff;
            border: none;
            padding: 10px 16px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .floating-chat .chat button:hover {
            background: #3730a3;
        }
    </style>
</header>

<section class="hero">
    <div class="container grid grid-2">
        <div class="hero__copy">
            <div id="hero-content">
                <div class="chip" id="hero-chip">Convocatorias abiertas</div>
                <h1 id="hero-title">Consigue tu <span class="grad">beca</span> <br> Solicita <span class="grad alt">información</span> que te abran puertas</h1>
                <p class="muted" id="hero-description">Explora convocatorias que te ayudaran en tu carrera.</p>
            </div>

            <?php if (isAdmin()): ?>
                <a href="/admin/editar_hero.php" class="btn small" style="margin-top: 15px;">
                    <i class="fa fa-pen"></i> Editar Hero
                </a>
            <?php endif; ?>

            <div class="hero__badges" id="hero-badges-container">
                <span class="badge ok">5 becas especializadas</span>
                <span class="badge">Porcentaje alto de obtener la beca</span>
                <span class="badge warn">Facil de acceder</span>
            </div>
        </div>

        <div class="hero__visual">
            <div class="glass card--stack" id="hero-cards-container">
                </div>
        </div>
    </div>
    <div class="hero__blur hero__blur--1"></div>
    <div class="hero__blur hero__blur--2"></div>
</section>

<section id="becas" class="section">
    <div class="container">
        <div class="section__head">
            <h2 id="section-title">Becas destacadas</h2>
            <p class="muted" id="section-subtitle">Curadas y verificadas por nuestro equipo.</p>
        </div>
        
        <?php if (isAdmin()): ?>
            <a href="/admin/editar_becas.php" class="btn small" style="margin-bottom: 20px;">
                <i class="fa fa-pen"></i> Gestionar Becas
            </a>
        <?php endif; ?>

        <div class="grid grid-3 cards" id="becas-container">
            </div>
    </div>
</section>

<section id="asesorias" class="section alt">
    <div class="container">
        <?php if (isAdmin()): ?>
            <a href="/admin/editar_asesorias.php" class="btn small" style="margin-bottom: 20px; display: block;">
                <i class="fa fa-pen"></i> Editar Asesorías
            </a>
        <?php endif; ?>
        <div class="grid grid-3" id="asesorias-container">
            </div>
    </div>
</section>

<div class="floating-chat">
    <i class="fa fa-comments" aria-hidden="true"></i>
    <div class="chat">
        <div class="header">
            <span class="title">UTPN-BOT</span>
            <button><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
        <ul class="messages">
            </ul>
        <div class="footer">
            <textarea class="text-box" placeholder="Escribe tu opción (1-5)..." disabled="true"></textarea>
            <button id="sendMessage">Enviar</button>
        </div>
    </div>
</div>
<script>
$(function() {
    const jsonPath = '/assets/data/becas.json'; 

    // =======================================================
    // 1. CARGA DE CONTENIDO PRINCIPAL (HERO, BECAS, ASESORÍAS)
    // =======================================================
    $.getJSON(jsonPath, function(data) {
        const becasData = data.becas;

        // A. HERO SECTION - ACTUALIZAR TEXTOS
        document.title = becasData.titulo;
        $('#hero-chip').text(becasData.hero.chip_texto);
        // Usamos .html() para permitir los tags <span> del estilo grad
        $('#hero-title').html(becasData.hero.titulo_principal.replace('beca', '<span class="grad">beca</span>').replace('información', '<span class="grad alt">información</span>'));
        $('#hero-description').text(becasData.hero.descripcion);

        // Inyectar Insignias (se vacía el contenedor primero)
        const badgesContainer = $('#hero-badges-container');
        badgesContainer.empty();
        $.each(becasData.hero.insignias, function(i, badge) {
            badgesContainer.append(`<span class="badge ${badge.clase}">${badge.texto}</span>`);
        });

        // Inyectar Tarjetas del Hero (se vacía el contenedor primero)
        const heroCardsContainer = $('#hero-cards-container');
        heroCardsContainer.empty();
        $.each(becasData.hero.tarjetas_ejemplo, function(i, card) {
            const delayClass = (i % 2 !== 0) ? ' delay' : '';
            
            let metaHtml = '';
            $.each(card.metadata, function(j, meta) {
                metaHtml += `<span>${meta}</span>`;
            });

            const btnHtml = card.enlace_texto ? 
                `<a class="btn ghost" href="${card.enlace_url}" target="_blank">${card.enlace_texto}</a>` : '';

            const cardHtml = `
                <div class="card${delayClass}">
                    <h3>${card.titulo}</h3>
                    <p>${card.descripcion}</p>
                    <div class="meta">${metaHtml}</div>
                    ${btnHtml}
                </div>
            `;
            heroCardsContainer.append(cardHtml);
        });

        // B. BECAS DESTACADAS SECTION - ACTUALIZAR TEXTOS Y CONTENIDO
        $('#section-title').text(becasData.seccion_becas_destacadas.titulo);
        $('#section-subtitle').text(becasData.seccion_becas_destacadas.subtitulo);

        const container = $('#becas-container');
        container.empty(); 
        
        $.each(becasData.seccion_becas_destacadas.becas, function(i, beca) {
            let requisitosList = '<ul class="list">';
            $.each(beca.requisitos, function(j, req) {
                // Se usa &ge; para el símbolo de "mayor o igual"
                const reqText = req.replace('≥', '&ge;'); 
                requisitosList += `<li>${reqText}</li>`;
            });
            requisitosList += '</ul>';

            // Estructura card2 con el mismo HTML que tenías
            const cardHtml = `
                <article class="card2">
                    <div class="card2__head">
                        <h3>${beca.nombre}</h3>
                        <span class="pill">${beca.monto}</span>
                    </div>
                    <p>${beca.resumen}</p>
                    ${requisitosList}
                    <a class="btn block" href="${beca.enlace_postular}" target="_blank">Postular</a>
                    <a class="btn block ghost" href="${beca.enlace_descarga_requisitos}" download>Descargar requisitos</a>
                </article>
            `;
            container.append(cardHtml);
        });

        // C. ASESORÍAS SECTION - ACTUALIZAR CONTENIDO
        const asesoriasContainer = $('#asesorias-container');
        asesoriasContainer.empty();
        $.each(becasData.seccion_asesorias.caracteristicas, function(i, feature) {
            // Estructura feature con el mismo HTML que tenías
            const featureHtml = `
                <div class="feature">
                    <div class="ico"></div>
                    <h3>${feature.titulo}</h3>
                    <p>${feature.descripcion}</p>
                </div>
            `;
            asesoriasContainer.append(featureHtml);
        });

        // =======================================================
        // 2. CHATBOT - INICIALIZACIÓN
        // =======================================================
        loadChatbotResponses(data);


    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("No se pudo cargar el archivo becas.json. Error:", textStatus, errorThrown);
        // Muestra un mensaje de error si falla la carga
        $('.cards').html('<p style="text-align:center;">❌ Error: No se pudieron cargar los datos de las becas. Revise la consola.</p>');
    });


    // =======================================================
    // 3. CHATBOT - LÓGICA
    // =======================================================

    var element = $('.floating-chat');
    var chatID = createUUID();
    var isOpen = false;
    var respuestas = {}; 

    function loadChatbotResponses(data) {
        // Usa los datos que ya cargó getJSON
        const chatbotData = data.becas.chatbot;
        respuestas = chatbotData.respuestas_pregrabadas;
        
        // Inyecta las opciones iniciales
        const initialOptions = chatbotData.opciones_iniciales;
        const messagesContainer = element.find('.messages');
        messagesContainer.empty();
        messagesContainer.append('<li class="other">¡Hola! 👋 Selecciona una opción escribiendo el número:</li>');
        $.each(initialOptions, function(i, option) {
            messagesContainer.append('<li class="other">' + option + '</li>');
        });
        messagesContainer.scrollTop(messagesContainer.prop("scrollHeight"));
    }

    setTimeout(function() {
        element.addClass('enter');
    }, 1000);

    element.click(toggleChat);

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
        
        element.find('>i').removeClass('fa-comments').addClass('fa-times');
        element.addClass('expand');
        element.find('.chat').css('display', 'flex');
        
        textInput.prop("disabled", false).focus();
        
        element.find('.header button').click(closeElement);
        element.find('#sendMessage').click(sendNewMessage);
        messages.scrollTop(messages.prop("scrollHeight"));
        textInput.keydown(onMetaAndEnter);
        isOpen = true;
    }

    function closeElement() {
        element.find('.chat').css('display', 'none');
        element.find('>i').removeClass('fa-times').addClass('fa-comments');
        element.removeClass('expand');
        element.find('.header button').off('click', closeElement);
        element.find('#sendMessage').off('click', sendNewMessage);
        element.find('.text-box').off('keydown', onMetaAndEnter).prop("disabled", true).blur();
        isOpen = false;
    }

    function createUUID() {
        var s = [];
        var hexDigits = "0123456789abcdef";
        for (var i = 0; i < 36; i++) {
            s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
        }
        s[14] = "4";
        s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1);
        s[8] = s[13] = s[18] = s[23] = "-";
        return s.join("");
    }

    function sendNewMessage() {
        var userInput = $('.text-box');
        var newMessage = userInput.val().trim(); 

        if (!newMessage) return;

        var messagesContainer = $('.messages');
        messagesContainer.append('<li class="self">' + newMessage.replace(/\n/g, '<br>') + '</li>');
        userInput.val(''); 
        messagesContainer.scrollTop(messagesContainer.prop("scrollHeight"));

        // Buscar respuesta (usa 'default' si no encuentra el número)
        var respuesta = respuestas[newMessage] || respuestas['default'];
        
        setTimeout(function() {
            if (respuesta) {
                // Reemplazar saltos de línea (\n) y comillas escapadas (\" )
                const responseHtml = respuesta.replace(/\\n/g, '<br>').replace(/\\"/g, '"');
                messagesContainer.append('<li class="other">' + responseHtml + '</li>');
            }
            messagesContainer.scrollTop(messagesContainer.prop("scrollHeight"));
        }, 700);
    }

    function onMetaAndEnter(event) {
        if (event.keyCode === 13 && !event.shiftKey) { 
            sendNewMessage();
            event.preventDefault();
        }
    }
});
</script>

<?php include "../../includes/footer.php"; ?>
</body>
</html>