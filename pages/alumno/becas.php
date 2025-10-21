<?php include "../../includes/header.php"; ?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Becas Universitarias | Impulsa tu camino</title>
    <!-- <link rel="stylesheet" href= "/INTEGRADORA-UTPN/assets/css/becas.css"> -->
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
  --bg: #f0f8f7;
  --bg-2: #0f172a;
  --txt: #e6eaf2;
  --muted: #a6b0c3;
  --brand: #7c3aed;   /* morado */
  --brand-2: #06b6d4; /* cian */
  --ok: #22c55e;
  --warn: #f59e0b;
  --glass: rgba(64, 224, 208, 0.5);
  --stroke: rgba(255,255,255,.12);
  --shadow: 0 10px 30px rgba(2,6,23,.35);
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
  background-color: #fdf6ec; /* 🎨 crema claro, cálido y suave */
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

  <!-- HERO -->
  <section class="hero">
    <div class="container grid grid-2">
      <div class="hero__copy">
        <div class="chip">Convocatorias abiertas</div>
        <h1>Consigue tu <span class="grad">beca</span> <br> Solicita <span class="grad alt">información</span> que te abran puertas</h1>
        <p class="muted">Explora convocatorias que te ayudaran en tu carrera.</p>

       <!-- <form class="search" action="#" method="get">
          <input name="q" type="text" placeholder="Busca: ‘ingeniería’, ‘posgrado’, ‘internacional’…" aria-label="Buscar becas">
          <button class="btn" type="submit">
            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path d="M10 18a8 8 0 1 1 5.293-14.293A8 8 0 0 1 10 18Zm11 3-5.4-5.4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Buscar
          </button>
        </form>-->

        <div class="hero__badges">
          <span class="badge ok">5 becas especializadas</span>
          <span class="badge">Porcentaje alto de obtener la beca</span>
          <span class="badge warn">Facil de acceder</span>
        </div>
      </div>

      <div class="hero__visual">
        <div class="glass card--stack">
          <div class="card">
            <h3>Beca Subes</h3>
            <p>Requisitos claros.</p>
            <div class="meta">
              <span>🇲🇽 México</span>
              <span>Licenciatura e Ingenieria</span>
              <span>Fecha de inicio: Empezando cuatrimestre</span>
            </div>
          </div>
          <div class="card delay">
            <h3>Jovenes Escribiendo el Futuro</h3>
            <p>Apoyo economico durante tu carrera universitaria</p>
            <div class="meta">
              <span>🌍 Beca Nacional</span>
              <span>Universitaria</span>
            </div>
            <a class="btn ghost" href="https://subes.becasbenitojuarez.gob.mx/" target="_blank">Empezar</a>
          </div>
        </div>
      </div>
    </div>
    <div class="hero__blur hero__blur--1"></div>
    <div class="hero__blur hero__blur--2"></div>
  </section>

  <!-- SECCIÓN: BECAS DESTACADAS -->
  <section id="becas" class="section">
    <div class="container">
      <div class="section__head">
        <h2>Becas destacadas</h2>
        <p class="muted">Curadas y verificadas por nuestro equipo.</p>
      </div>

      <div class="grid grid-3 cards">
        <article class="card2">
          <div class="card2__head">
            <h3>Beca Inscripción</h3>
            <span class="pill">$3000</span>
          </div>
          <p>Para carreras de TI y ciencia de datos. Incluye mentoría.</p>
          <ul class="list">
            <li>Promedio ≥ 8.5</li>
            <li>Proyecto o portafolio</li>
            <li>Entrevista técnica</li>
          </ul>
          <a class="btn block" href="https://mitch.chihuahua.gob.mx/seaged-app-portal/tramites/inicio" target="_blank">Postular</a>
          <a class="btn block" href="../../assets/PDF/beca_inscripcion_2.pdf" download>Descargar requisitos</a>
        </article>

        <article class="card2">
          <div class="card2__head">
            <h3>Beca Material y Equipo Técnico</h3>
            <span class="pill">$3000</span>
          </div>
          <p>Movilidad internacional y manutención por méritos académicos.</p>
          <ul class="list">
            <li>Idiomas B2+</li>
            <li>Ensayo motivacional</li>
            <li>Servicio social</li>
          </ul>
          <a class="btn block" href="https://mitch.chihuahua.gob.mx/seaged-app-portal/tramites/inicio" target="_blank" >Postular</a>
          <a class="btn block" href="../../assets/PDF/beca_material_y_equipo_tecnico_2.pdf" download>Descargar requisitos</a>
        </article>

        <article class="card2">
          <div class="card2__head">
            <h3>Beca Titulación</h3>
            <span class="pill">$3000</span>
          </div>
          <p>Fondos para proyectos universitarios y publicaciones.</p>
          <ul class="list">
            <li>Protocolo avalado</li>
            <li>Director de tesis</li>
            <li>Informe parcial</li>
          </ul>
          <a class="btn block" href="https://mitch.chihuahua.gob.mx/seaged-app-portal/tramites/inicio" target="_blank" >Postular</a>
          <a class="btn block" href="../../assets/PDF/beca_titulacion_2.pdf" download>Descargar requisitos</a>
        </article>

        <article class="card2">
          <div class="card2__head">
            <h3>Jóvenes Escribiendo el Futuro</h3>
            <span class="pill">Estímulo $5800</span>
          </div>
          <p>Fondos para proyectos universitarios y publicaciones.</p>
          <ul class="list">
            <li>Protocolo avalado</li>
            <li>Director de tesis</li>
            <li>Informe parcial</li>
          </ul>
          <a class="btn block" href="https://subes.becasbenitojuarez.gob.mx/" target="_blank">Postular</a>
          <a class="btn block"  href="https://programasparaelbienestar.gob.mx/beca-bienestar-benito-juarez-educacion-superior/" target="_blank">Ver requisitos</a>
        </article>

        <article class="card2">
          <div class="card2__head">
            <h3>Beca Acceso a la Universidad</h3>
            <span class="pill">Pago primer cuatrimestre</span>
          </div>
          <p>Solo estudiantes de nuevo ingreso.</p>
          <ul class="list">
            <li>Protocolo avalado</li>
            <li>Director de tesis</li>
            <li>Informe parcial</li>
          </ul>
          <a class="btn block" href="#aplica">Postular</a>
          <a class="btn block" href="../../assets/PDF/RequisitosBeca.pdf" download>Descargar requisitos</a>
        </article>
      </div>
    </div>
  </section>

  <!-- SECCIÓN: ASESORÍAS -->
  <section id="asesorias" class="section alt">
    <div class="container grid grid-3">
      <div class="feature">
        <div class="ico"></div>
        <h3>Revisión de documentos</h3>
        <p>CV académico, carta de motivación y ensayo con retro precisa.</p>
      </div>
      <div class="feature">
        <div class="ico"></div>
        <h3>Simulación de entrevista</h3>
        <p>Preguntas reales, feedback y plan de mejora por sesión.</p>
      </div>
      <div class="feature">
        <div class="ico"></div>
        <h3>Estrategia de postulación</h3>
        <p>Calendario, requisitos y priorización de convocatorias.</p>
      </div>
    </div>
  </section>

  <div class="floating-chat">
    <i class="fa fa-comments" aria-hidden="true"></i>
    <div class="chat">
        <div class="header">
            <span class="title">
                UTPN-BOT
            </span>
            <button>
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
                         
        </div>
        <ul class="messages">
            <li class="other">¡Hola! 👋 Selecciona una opción escribiendo el número:</li>
            <li class="other">1️⃣ ¿Qué becas están disponibles?</li>
            <li class="other">2️⃣ ¿Cuáles son los requisitos?</li>
            <li class="other">3️⃣ ¿Cómo solicitar una beca?</li>
            <li class="other">4️⃣ ¿Cuándo cierran las convocatorias?</li>
            <li class="other">5️⃣ ¿Ofrecen asesorías personalizadas?</li>
        </ul>
        <div class="footer">
            <div class="text-box" contenteditable="true" disabled="true"></div>
            <button id="sendMessage">send</button>
        </div>
    </div>
</div>
  <!-- FOOTER -->


  <!-- CHATBOT JS -->
 <!-- CHATBOT JS -->
<script>
$(function(){
  var element = $('.floating-chat');
  var chatID = createUUID();
  var isOpen = false;

  // Respuestas pregrabadas
  var respuestas = {
      '1': '📚 Actualmente tenemos disponibles:\n• Beca Talento Digital (hasta 80%)\n• Beca Líder Global (100%)\n• Apoyo Investigación\n• Y más opciones en la sección de becas destacadas',
      '2': '📋 Los requisitos varían según la beca:\n• Promedio mínimo: 8.0-8.5\n• Documentos: CV, carta motivación, comprobantes\n• Algunos requieren entrevista o proyecto\n• Revisa cada convocatoria para detalles específicos',
      '3': '✍️ Pasos para solicitar:\n1. Revisa las becas disponibles\n2. Verifica que cumples los requisitos\n3. Prepara tu documentación\n4. Click en "Postular" en la beca que te interesa\n5. Llena el formulario completo\n6. ¡Listo! Recibirás confirmación por email',
      '4': '📅 Las convocatorias tienen diferentes fechas:\n• Revisa cada beca para ver su fecha límite\n• Generalmente abren al inicio de cada cuatrimestre\n• Te recomendamos aplicar con anticipación\n• Suscríbete para recibir notificaciones',
      '5': '👨‍🏫 ¡Sí! Ofrecemos:\n• Revisión de documentos\n• Simulación de entrevistas\n• Estrategia de postulación\n• Agenda una cita en la sección de Asesorías'
  };

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
      var newMessage = userInput.html().replace(/\<div\>|\<br.*?\>/ig, '\n').replace(/\<\/div\>/g, '').trim().replace(/\n/g, '<br>');
      if (!newMessage) return;
      
      var messagesContainer = $('.messages');
      messagesContainer.append('<li class="self">' + newMessage + '</li>');
      userInput.html('');
      
      // Verificar si es un número del 1 al 5
      var numero = newMessage.trim();
      if (respuestas[numero]) {
          setTimeout(function() {
              var respuesta = respuestas[numero].replace(/\n/g, '<br>');
              messagesContainer.append('<li class="other">' + respuesta + '</li>');
              messagesContainer.finish().animate({
                  scrollTop: messagesContainer.prop("scrollHeight")
              }, 250);
          }, 500);
      } else if (numero.length === 1 && numero >= '1' && numero <= '5') {
          setTimeout(function() {
              messagesContainer.append('<li class="other">Por favor escribe un número del 1 al 5 📝</li>');
              messagesContainer.finish().animate({
                  scrollTop: messagesContainer.prop("scrollHeight")
              }, 250);
          }, 500);
      } else {
          setTimeout(function() {
              messagesContainer.append('<li class="other">Por favor selecciona una opción escribiendo su número (1-5) 😊</li>');
              messagesContainer.finish().animate({
                  scrollTop: messagesContainer.prop("scrollHeight")
              }, 250);
          }, 500);
      }
      
      userInput.focus();
      messagesContainer.finish().animate({
          scrollTop: messagesContainer.prop("scrollHeight")
      }, 250);
  }

  function onMetaAndEnter(event) {
      if ((event.metaKey || event.ctrlKey) && event.keyCode == 13) {
          sendNewMessage();
      }
  }
});
</script>
</body>
<?php include "../../includes/footer.php"; ?>
</html>