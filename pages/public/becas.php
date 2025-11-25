<?php 
include "../../includes/header.php"; 

// Cargar y decodificar el JSON
$json_file = '../../assets/js/becas.json';
$json_data = file_get_contents($json_file);
$data = json_decode($json_data, true);

// Usar los datos específicos de la página de becas universitarias
$pagina = $data['pagina_becas_universitarias'];
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
  overflow-x: hidden;
}

/* ================================
   Layout
================================ */
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
   NUEVO DISEÑO HERO - COMPACTO
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
   Responsive Breakpoints
================================ */
@media (max-width: 1024px) {
  .grid-3 { 
    grid-template-columns: repeat(2, minmax(0, 1fr)); 
  }
}

@media (max-width: 768px) {
  .grid-2, 
  .grid-3 { 
    grid-template-columns: 1fr; 
  }
  
  .hero-main {
    grid-template-columns: 1fr;
    gap: 15px;
  }
  
  .hero-card {
    max-width: 100%;
    order: -1;
  }
  
  .section__head {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }
}

@media (max-width: 480px) {
  .container {
    padding: 10px;
  }
  
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
  justify-content: center;
  gap: 8px;
  background: var(--bgbtn);
  color: white;
  text-decoration: none;
  font-weight: 700;
  padding: 12px 16px;
  border-radius: 12px;
  border: 1px solid transparent;
  box-shadow: var(--shadow);
  transform: translateZ(0);
  transition: transform .2s ease, filter .2s ease;
  text-align: center;
  font-size: 14px;
}

.btn.small {
  padding: 8px 12px;
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
  padding: 0 12px;
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
  font-size: clamp(16px, 4vw, 18px);
}

.brand__accent {
  background: linear-gradient(90deg, var(--brand), var(--brand-2));
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
}

.brand__dot {
  width: 8px;
  height: 8px;
  border-radius: 999px;
  background: linear-gradient(90deg, var(--brand), var(--brand-2));
  box-shadow: 0 0 18px rgba(124,58,237,.7);
}

/* ================================
   Sections
================================ */
.section {
  padding: clamp(24px, 6vw, 64px) 0;
}

.section.alt {
  background: linear-gradient(180deg, rgba(255,255,255,.02), rgba(255,255,255,0));
}

.section__head {
  display: flex;
  align-items: baseline;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 16px;
}

.section__head h2 {
  margin: 0;
  font-size: clamp(20px, 3.5vw, 32px);
}

.cards { margin-top: 8px; }

.card2 {
  padding: 16px;
  border-radius: 16px;
  background: rgba(255,255,255,.04);
  border: 1px solid var(--stroke);
  box-shadow: var(--shadow);
  display: flex;
  flex-direction: column;
  gap: 10px;
  min-height: 240px;
}

.card2__head {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 8px;
}

.card2__head h3 { 
  margin: 0; 
  font-size: clamp(16px, 2.5vw, 18px);
  line-height: 1.2;
}

.pill {
  padding: 5px 8px;
  border-radius: 999px;
  background: linear-gradient(90deg, var(--brand), var(--brand-2));
  font-weight: 700;
  font-size: 12px;
  white-space: nowrap;
  flex-shrink: 0;
}

.list {
  margin: 0 0 6px 0;
  padding: 0;
  list-style: none;
  color: var(--muted);
}

.list li {
  padding-left: 12px;
  position: relative;
  margin: .2rem 0;
  font-size: 13px;
  line-height: 1.3;
}

.list li:before {
  content: '•';
  position: absolute;
  left: 0;
  opacity: .6;
}

/* ================================
   Features
================================ */
.feature {
  padding: 16px;
  border-radius: 16px;
  border: 1px solid var(--stroke);
  background: rgba(255,255,255,.04);
  min-height: 160px;
}

.feature h3 {
  margin: .2rem 0 .3rem;
  font-size: clamp(16px, 2.5vw, 18px);
}

.feature p {
  margin: 0;
  color: var(--muted);
  font-size: 13px;
  line-height: 1.4;
}

.feature .ico {
  width: 32px;
  height: 32px;
  border-radius: 10px;
  background: linear-gradient(90deg, var(--brand), var(--brand-2));
  box-shadow: var(--shadow);
  margin-bottom: 8px;
}

/* ================================
   Footer
================================ */
.footer {
  border-top: 1px solid var(--stroke);
  padding: 20px 0;
  background: rgba(255,255,255,.02);
}

.footer__inner {
  display: grid;
  gap: 8px;
  align-items: center;
  justify-items: center;
  text-align: center;
}

.footer__links {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  justify-content: center;
}

.footer__links a {
  color: var(--muted);
  text-decoration: none;
  font-size: 13px;
}

.brand--footer {
  font-weight: 800;
}

/* --- CHAT FLOTANTE --- */
.floating-chat {
  position: fixed;
  bottom: 15px;
  right: 15px;
  background: #4f46e5;
  color: #fff;
  width: 50px;
  height: 50px;
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
  font-size: 22px;
}

/* --- CUADRO DE CHAT --- */
.floating-chat .chat {
  position: absolute;
  bottom: 60px;
  right: 0;
  width: 280px;
  max-height: 350px;
  background: #fff;
  color: #333;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
  display: none;
  flex-direction: column;
  overflow: hidden;
}

@media (max-width: 480px) {
  .floating-chat {
    bottom: 10px;
    right: 10px;
    width: 45px;
    height: 45px;
  }
  
  .floating-chat i {
    font-size: 20px;
  }
  
  .floating-chat .chat {
    width: calc(100vw - 20px);
    right: -10px;
    bottom: 55px;
  }
}

/* Header */
.floating-chat .chat .header {
  background: #4f46e5;
  color: #fff;
  padding: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.floating-chat .chat .header button {
  background: transparent;
  border: none;
  color: #fff;
  font-size: 14px;
  cursor: pointer;
}

/* Mensajes */
.floating-chat .chat .messages {
  list-style: none;
  padding: 10px;
  margin: 0;
  flex: 1;
  overflow-y: auto;
  font-size: 13px;
  max-height: 250px;
}

.floating-chat .chat .messages li {
  margin-bottom: 6px;
  padding: 6px 10px;
  border-radius: 8px;
  max-width: 85%;
  word-wrap: break-word;
  font-size: 13px;
}

.floating-chat .chat .messages li.other {
  background: #f3f4f6;
  align-self: flex-start;
}

.floating-chat .chat .messages li.self {
  background: #e0e7ff;
  align-self: flex-end;
}

/* Footer */
.floating-chat .chat .footer {
  display: flex;
  border-top: 1px solid #ddd;
}

.floating-chat .chat .text-box {
  flex: 1;
  padding: 8px;
  outline: none;
  font-size: 13px;
  min-height: 40px;
  max-height: 80px;
  overflow-y: auto;
}

.floating-chat .chat button {
  background: #4f46e5;
  color: #fff;
  border: none;
  padding: 8px 12px;
  cursor: pointer;
  transition: background 0.2s;
  white-space: nowrap;
  font-size: 13px;
}

.floating-chat .chat button:hover {
  background: #3730a3;
}

/* 🔒 Bloquear botones de becas */
.btn.disabled {
  background: #bdbdbd !important;
  cursor: not-allowed !important;
  pointer-events: none !important;
  color: #666 !important;
  box-shadow: none !important;
}

.card2.locked {
  opacity: 0.6;
  filter: grayscale(0.8);
  position: relative;
}

.card2.locked::after {
  content: "🔒 Solo disponible para estudiantes";
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: clamp(12px, 2.5vw, 14px);
  color: #333;
  background: rgba(255,255,255,0.7);
  border-radius: 16px;
  text-align: center;
  padding: 16px;
}

/* MEJORAS ESPECÍFICAS PARA MÓVIL PEQUEÑO */
@media (max-width: 360px) {
  .container {
    padding: 8px;
  }
  
  .card2 {
    padding: 12px;
    min-height: 220px;
  }
  
  .feature {
    padding: 12px;
    min-height: 140px;
  }
  
  .btn {
    padding: 8px 12px;
    font-size: 12px;
  }
  
  .hero-title {
    font-size: 18px;
  }
  
  .hero-description {
    font-size: 13px;
  }
  
  .card-compact {
    padding: 10px;
  }
  
  .card-compact h3 {
    font-size: 14px;
  }
  
  .card-compact p {
    font-size: 12px;
  }
  
  .meta-item {
    font-size: 10px;
    padding: 3px 6px;
  }
  
  .card-action {
    padding: 8px 12px;
    font-size: 12px;
  }
}
  </style>
</head>
<body>

  <!-- HERO COMPACTO -->
  <section class="hero">
    <div class="container">
      <div class="hero-compact">
        <div class="hero-main">
          <div class="hero-content">
            <div class="hero-badge"><?php echo htmlspecialchars($pagina['hero']['chip_texto']); ?></div>
            <h1 class="hero-title">
              Consigue tu <span class="highlight">beca</span> de 
              <span class="highlight">Acceso a la Universidad</span>
            </h1>
            <p class="hero-description"><?php echo htmlspecialchars($pagina['hero']['descripcion']); ?></p>
            
            <div class="hero-features">
              <?php foreach ($pagina['hero']['insignias'] as $insignia): ?>
                <span class="feature-tag <?php echo $insignia['clase']; ?>"><?php echo htmlspecialchars($insignia['texto']); ?></span>
              <?php endforeach; ?>
            </div>
          </div>
          
          <div class="hero-card">
            <div class="card-compact">
              <h3><?php echo htmlspecialchars($pagina['hero']['tarjeta_ejemplo']['titulo']); ?></h3>
              <p><?php echo htmlspecialchars($pagina['hero']['tarjeta_ejemplo']['descripcion']); ?></p>
              <div class="card-meta">
                <?php foreach ($pagina['hero']['tarjeta_ejemplo']['metadata'] as $meta): ?>
                  <span class="meta-item"><?php echo htmlspecialchars($meta); ?></span>
                <?php endforeach; ?>
              </div>
              <a class="card-action" href="<?php echo htmlspecialchars($pagina['hero']['tarjeta_ejemplo']['enlace_url']); ?>" target="_blank">
                <?php echo htmlspecialchars($pagina['hero']['tarjeta_ejemplo']['enlace_texto']); ?>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SECCIÓN: BECAS -->
  <section id="becas" class="section">
    <div class="container">
      <div class="section__head">
        <h2><?php echo htmlspecialchars($pagina['seccion_becas']['titulo']); ?></h2>
        <p class="muted"><?php echo htmlspecialchars($pagina['seccion_becas']['subtitulo']); ?></p>
      </div>

      <div class="grid grid-3 cards">
        <?php foreach ($pagina['seccion_becas']['becas'] as $beca): ?>
          <article class="card2 <?php echo $beca['bloqueada'] ? 'locked' : ''; ?>">
            <div class="card2__head">
              <h3><?php echo htmlspecialchars($beca['nombre']); ?></h3>
              <span class="pill"><?php echo htmlspecialchars($beca['monto']); ?></span>
            </div>
            <p><?php echo htmlspecialchars($beca['resumen']); ?></p>
            <ul class="list">
              <?php foreach ($beca['requisitos'] as $requisito): ?>
                <li><?php echo htmlspecialchars($requisito); ?></li>
              <?php endforeach; ?>
            </ul>
            <?php if ($beca['bloqueada']): ?>
              <a class="btn block disabled">Postular</a>
              <a class="btn block disabled">Descargar requisitos</a>
            <?php else: ?>
              <a class="btn block" href="<?php echo htmlspecialchars($beca['enlace_postular']); ?>" target="_blank">Postular</a>
              <a class="btn block" href="<?php echo htmlspecialchars($beca['enlace_descarga_requisitos']); ?>" download>Descargar requisitos</a>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  
  <!-- SECCIÓN: ASESORÍAS -->
  <section id="asesorias" class="section alt">
    <div class="container grid grid-3">
      <?php foreach ($pagina['seccion_asesorias']['caracteristicas'] as $caracteristica): ?>
        <div class="feature">
          <div class="ico"></div>
          <h3><?php echo htmlspecialchars($caracteristica['titulo']); ?></h3>
          <p><?php echo htmlspecialchars($caracteristica['descripcion']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- CHATBOT -->
  <div class="floating-chat">
    <i class="fa fa-comments" aria-hidden="true"></i>
    <div class="chat">
        <div class="header">
            <span class="title">
                <?php echo htmlspecialchars($pagina['chatbot']['nombre']); ?>
            </span>
            <button>
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>                         
        </div>
        <ul class="messages">
            <li class="other"><?php echo htmlspecialchars($pagina['chatbot']['mensaje_bienvenida']); ?></li>
            <?php foreach ($pagina['chatbot']['opciones_iniciales'] as $opcion): ?>
                <li class="other"><?php echo htmlspecialchars($opcion); ?></li>
            <?php endforeach; ?>
        </ul>
        <div class="footer">
            <div class="text-box" contenteditable="true" disabled="true"></div>
            <button id="sendMessage">send</button>
        </div>
    </div>
  </div>

  <script>
$(function(){
  var element = $('.floating-chat');
  var chatID = createUUID();
  var isOpen = false;

  // Respuestas pregrabadas desde JSON
  var respuestas = <?php echo json_encode($pagina['chatbot']['respuestas_pregrabadas']); ?>;

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
      var newMessage = userInput.html()
          .replace(/\<div\>|\<br.*?\>/ig, '\n')
          .replace(/\<\/div\>/g, '')
          .trim()
          .replace(/\n/g, '<br>');

      if (!newMessage) return;

      var messagesContainer = $('.messages');
      messagesContainer.append('<li class="self">' + newMessage + '</li>');
      userInput.html('');
      messagesContainer.scrollTop(messagesContainer.prop("scrollHeight"));

      // Buscar respuesta
      var respuesta = respuestas[newMessage.trim()];
      setTimeout(function() {
          if (respuesta) {
              messagesContainer.append('<li class="other">' + respuesta.replace(/\n/g, '<br>') + '</li>');
          } else {
              messagesContainer.append('<li class="other">🤖 No entendí tu opción. Escribe un número del 1 al 5 para continuar.</li>');
          }
          messagesContainer.scrollTop(messagesContainer.prop("scrollHeight"));
      }, 700);
  }

  function onMetaAndEnter(event) {
      if (event.keyCode === 13) {
          sendNewMessage();
          event.preventDefault();
      }
  }
});
</script>
</body>
<?php include "../../includes/footer.php"; ?>
</html>