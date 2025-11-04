<?php include("../../includes/header.php"); ?>

<!-- ===================== CSS EXTERNOS ===================== -->
<!-- Leaflet (Mapa) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- FullCalendar (Calendario) -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">

<!-- ===================== TUS CSS LOCALES ===================== -->
<link rel="stylesheet" href="../../assets/css/mapa_calendario.css">
<link rel="stylesheet" href="../../assets/css/navbar.css">
<link rel="stylesheet" href="../../assets/css/footer.css">
<link rel="stylesheet" href="../../assets/css/style.css">

<!-- ===================== ESTILO LOCAL ===================== -->
<style>
  #info-edificios {
    margin-top: 20px;
  }

  .acordeon {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }

  .acordeon-item {
    border-bottom: 1px solid #ddd;
  }

  .acordeon-header {
    background-color: #19a473ff;
    color: white;
    padding: 12px 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
  }

  .acordeon-header:hover {
    background-color: #148a60ff;
  }

  .acordeon-content {
    display: none;
    padding: 12px 16px;
    background: #f8f8f8;
    line-height: 1.6;
  }

  .acordeon-content p {
    margin: 5px 0;
  }

  #map {
    height: 450px;
    width: 100%;
    border-radius: 8px;
    margin-bottom: 30px;
  }

  #calendar {
    margin-top: 20px;
  }
</style>

<!-- ===================== CONTENIDO PRINCIPAL ===================== -->
<div class="container">
  <h2>Mapa y Calendario de la UTPN</h2>

  <!-- MAPA -->
  <h3>Mapa de la Universidad</h3>
  <div id="map"></div>

  <!-- INFORMACIÓN DE LOS EDIFICIOS -->
  <div id="info-edificios">
    <h3>Información de los Edificios</h3>
    <div class="acordeon">

      <!-- EDIFICIO A -->
      <div class="acordeon-item" id="edificioA">
        <div class="acordeon-header">Edificio A</div>
        <div class="acordeon-content">
          <p><b>Escolares:</b> Oficina encargada de la gestión académica de los estudiantes, incluyendo inscripciones y control de materias.</p>
          <p><b>Psicología:</b> Área de atención psicológica para estudiantes, personal docente y administrativo.</p>
          <p><b>Enfermería:</b> Servicio de atención médica básica y primeros auxilios para la comunidad universitaria.</p>
          <p><b>Cultura y Deportes / Servicio Social:</b> Coordinación de actividades culturales, deportivas y programas de servicio social.</p>
          <p><b>Servicios Escolares:</b> Oficina de apoyo administrativo para trámites escolares y entrega de documentos.</p>
          <p><b>Vinculación y Prensa/Difusión:</b> Área dedicada a la relación con empresas, medios y difusión de actividades académicas.</p>
        </div>
      </div>

      <!-- EDIFICIO B -->
      <div class="acordeon-item" id="edificioB">
        <div class="acordeon-header">Edificio B</div>
        <div class="acordeon-content">
          <p><b>Laboratorio Redes:</b> Laboratorio especializado en redes de comunicación y telecomunicaciones.</p>
          <p><b>Laboratorio Mecatrónica:</b> Espacio para prácticas de robótica, automatización y sistemas mecatrónicos.</p>
          <p><b>Laboratorio Logística:</b> Laboratorio dedicado a la optimización y gestión de procesos logísticos.</p>
          <p><b>Centro STEAM:</b> Área interdisciplinaria enfocada en Ciencia, Tecnología, Ingeniería, Arte y Matemáticas.</p>
          <p><b>Laboratorio Simulación de Proyectos:</b> Simulación de proyectos reales para la práctica profesional de los estudiantes.</p>
          <p><b>Laboratorio de Gestión de Proyectos:</b> Espacio para la planificación y seguimiento de proyectos académicos y profesionales.</p>
        </div>
      </div>

      <!-- EDIFICIO C -->
      <div class="acordeon-item" id="edificioC">
        <div class="acordeon-header">Edificio C</div>
        <div class="acordeon-content">
          <p><b>Biblioteca:</b> Área de consulta y préstamo de libros, revistas, eventos, conferencias y recursos digitales.</p>
          <p><b>Caja:</b> Espacio para pagos y gestión financiera de estudiantes y servicios.</p>
          <p><b>Rectoría:</b> Oficinas administrativas de la dirección general de la universidad.</p>
          <p><b>Planeación:</b> Departamento encargado de la planificación académica y administrativa.</p>
          <p><b>Jurídico:</b> Asesoría legal para la comunidad universitaria y la institución.</p>
          <p><b>Finanzas:</b> Gestión financiera y presupuestal de la universidad.</p>
        </div>
      </div>

      <!-- EDIFICIO D -->
      <div class="acordeon-item" id="edificioD">
        <div class="acordeon-header">Edificio D</div>
        <div class="acordeon-content">
          <p><b>Audiovisual:</b> Área para proyección de material audiovisual académico.</p>
          <p><b>Dirección de Carrera:</b> Oficina de coordinación académica.</p>
          <p><b>Sala de Maestros:</b> Espacio de trabajo y reuniones para el personal docente.</p>
        </div>
      </div>

      <!-- EDIFICIO E -->
      <div class="acordeon-item" id="edificioE">
        <div class="acordeon-header">Edificio E</div>
        <div class="acordeon-content">
          <p><b>Laboratorio Dobot / Robótica Colaborativa:</b> Laboratorio de robótica colaborativa.</p>
          <p><b>Quality Room:</b> Pruebas de calidad y control de procesos.</p>
          <p><b>Laboratorio Manufactura Aditiva:</b> Impresión 3D y manufactura avanzada.</p>
          <p><b>Laboratorio de Arquitectura:</b> Diseño arquitectónico y proyectos constructivos.</p>
          <p><b>Departamento de Infraestructura Informática:</b> Gestión tecnológica.</p>
          <p><b>Laboratorio SMT:</b> Ensamblaje de circuitos electrónicos.</p>
          <p><b>Laboratorio de Internet de las Cosas:</b> Desarrollo de dispositivos IoT.</p>
          <p><b>Sala 3D:</b> Modelado tridimensional.</p>
          <p><b>Laboratorio de Robótica Móvil:</b> Prácticas con robots móviles.</p>
          <p><b>Salón Realidad Virtual:</b> Área de simulaciones inmersivas.</p>
        </div>
      </div>

      <!-- CAFETERÍA -->
      <div class="acordeon-item" id="cafetería">
        <div class="acordeon-header">Cafetería</div>
        <div class="acordeon-content">
          <p><b>Menú del Día:</b> Variedad de platillos y bebidas disponibles.</p>
          <p><b>Área de Comedores:</b> Espacios para comer y socializar.</p>
        </div>
      </div>

      <!-- CANCHAS -->
      <div class="acordeon-item" id="canchadefutbol">
        <div class="acordeon-header">Cancha de Fútbol</div>
        <div class="acordeon-content">
          <p><b>Descripción:</b> Espacio deportivo para la práctica de fútbol.</p>
          <p><b>Normativas:</b> Reglas y horarios de uso.</p>
        </div>
      </div>

      <div class="acordeon-item" id="canchadebasquetbol">
        <div class="acordeon-header">Cancha de Basquetbol</div>
        <div class="acordeon-content">
          <p><b>Descripción:</b> Espacio deportivo para basquetbol.</p>
        </div>
      </div>

      <div class="acordeon-item" id="canchadevoleibol">
        <div class="acordeon-header">Cancha de Voleibol</div>
        <div class="acordeon-content">
          <p><b>Descripción:</b> Espacio deportivo para voleibol.</p>
        </div>
      </div>

      <div class="acordeon-item" id="canchadevoleibolplayero">
        <div class="acordeon-header">Cancha de Voleibol Playero</div>
        <div class="acordeon-content">
          <p><b>Descripción:</b> Espacio deportivo para voleibol playero.</p>
        </div>
      </div>

      <!-- OTROS -->
      <div class="acordeon-item" id="quiosco">
        <div class="acordeon-header">Quiosco</div>
        <div class="acordeon-content">
          <p><b>Descripción:</b> Espacio al aire libre para actividades o descanso.</p>
        </div>
      </div>

      <div class="acordeon-item" id="puntodereunion">
        <div class="acordeon-header">Punto de Reunión</div>
        <div class="acordeon-content">
          <p><b>Descripción:</b> Espacio destinado a reuniones en situaciones de emergencia.</p>
        </div>
      </div>

    </div>
  </div>

  <!-- CALENDARIO -->
  <h3>Calendario de Actividades</h3>
  <div id="calendar"></div>
</div>

<!-- ===================== JS EXTERNOS ===================== -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<!-- ===================== JS LOCAL (MAPA Y CALENDARIO) ===================== -->
<script src="../../assets/js/mapa_calendario.js"></script>

<!-- ===================== ACORDEÓN ===================== -->
<script>
  document.querySelectorAll(".acordeon-header").forEach(header => {
    header.addEventListener("click", () => {
      const content = header.nextElementSibling;
      const isOpen = content.style.display === "block";
      document.querySelectorAll(".acordeon-content").forEach(c => c.style.display = "none");
      content.style.display = isOpen ? "none" : "block";
    });
  });
</script>

<?php include("../../includes/footer.php"); ?>
