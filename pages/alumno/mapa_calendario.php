<?php include("../../includes/header.php"); ?>

<!-- ===================== CSS EXTERNOS ===================== -->
<!-- Leaflet (Mapa) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- FullCalendar (Calendario) -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">

<!-- ===================== ESTILOS LOCALES ===================== -->
<style>
  :root {
    --txt: #2e2e2e;
  }

  body {
    margin: 0;
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, "Helvetica Neue", Arial, sans-serif;
    color: var(--txt);
    background-color: #EDE5D6; /* 🎨 crema claro, cálido y suave */
  }

  .contenedor-principal {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
  }

  h1, h2, h3 {
    text-align: center;
    font-weight: 700;
    color: #3b3b3b;
    margin-bottom: 20px;
  }

  h1 {
    font-size: 2.2rem;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  h2 {
    font-size: 1.6rem;
    margin-top: 40px;
  }

  /* ==== MAPA ==== */
  #map {
    height: 450px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    margin-bottom: 40px;
  }

  /* ==== CALENDARIO ==== */
  #calendar {
    background-color: #fff;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  /* ==== ACORDEÓN ==== */
  .acordeon {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-top: 20px;
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

  /* ==== SECCIONES DE DESCRIPCIÓN ==== */
  .seccion-lugares {
    margin-top: 40px;
    background-color: #fff;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  .seccion-lugares h3 {
    margin-bottom: 10px;
    color: #444;
  }

  .seccion-lugares p {
    margin: 0 0 10px;
  }
</style>

<!-- ===================== CONTENIDO ===================== -->
<div class="contenedor-principal">
  <h1>Mapa Interactivo y Calendario de Actividades</h1>

  <!-- MAPA -->
  <div id="map"></div>

  <!-- INFORMACIÓN DE LOS EDIFICIOS -->
  <div class="seccion-lugares">
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
  <h2>Calendario Académico</h2>
  <div id="calendar"></div>
</div>

<!-- ===================== JS EXTERNOS ===================== -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<!-- ===================== JS LOCAL ===================== -->
<script>
// -------- MAPA --------
const map = L.map("map", {
  center: [31.766600, -106.56248729667603],
  zoom: 17,
  minZoom: 17,
  zoomControl: false
});

const bounds = [
  [31.7655, -106.5645],
  [31.7685, -106.5600]
];
map.setMaxBounds(bounds);

L.tileLayer(
  "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
  { attribution: "Tiles © Esri — Source: Esri, Earthstar Geographics, Maxar" }
).addTo(map);

// --- MARCADORES ---
const lugares = [
  { coords: [31.766365511367177, -106.56166338142302], nombre: "Edificio A", scrollId: "#edificioA" },
  { coords: [31.766841645023472, -106.56102909656026], nombre: "Edificio B", scrollId: "#edificioB" },
  { coords: [31.76627811886781, -106.56245778374044], nombre: "Edificio C", scrollId: "#edificioC" },
  { coords: [31.766257798593916, -106.563129401242694], nombre: "Edificio D", scrollId: "#edificioD" },
  { coords: [31.766248767145573, -106.56389772444955], nombre: "Edificio E", scrollId: "#edificioE" },
  { coords: [31.767125033013915, -106.56140786196244], nombre: "Cafetería", scrollId: "#cafetería" },
  { coords: [31.766970077979806, -106.56308318968154], nombre: "Cancha de Fútbol", scrollId: "#canchadefutbol" },
  { coords: [31.766968880896098, -106.56343654851547], nombre: "Cancha de Voleibol", scrollId: "#canchadevoleibol" },
  { coords: [31.767213023750685, -106.56247437733018], nombre: "Cancha de Voleibol Playero", scrollId: "#canchadevoleibolplayero" },
  { coords: [31.766961941341236, -106.56280712333786], nombre: "Cancha de Basquetbol", scrollId: "#canchadebasquetbol" },
  { coords: [31.766974870070786, -106.56248066354152], nombre: "Quiosco", scrollId: "#quiosco" },
  { coords: [31.766993499754374, -106.56201627006439], nombre: "Punto de reunión", scrollId: "#puntodereunion" }
];

lugares.forEach((lugar) => {
  L.marker(lugar.coords).addTo(map).bindPopup(`<b>${lugar.nombre}</b>`).on('click', function() {
    const elem = document.querySelector(lugar.scrollId);
    if(elem){
      elem.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});

// -------- CALENDARIO EN ESPAÑOL (SOLO VISUALIZACIÓN) --------
document.addEventListener("DOMContentLoaded", () => {
  const calendarEl = document.getElementById("calendar");

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    locale: 'es', // Establecer idioma español
    firstDay: 1, // Lunes como primer día de la semana
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay"
    },
    events: [
      { title: "Entrega de Proyecto", start: "2025-09-10" },
      { title: "Revisión de Avances", start: "2025-09-12" },
      { title: "Exposición Parcial", start: "2025-09-15" },
      { title: "Práctica de Laboratorio", start: "2025-09-18" },
      { title: "Reunión Académica", start: "2025-09-20" },
      { title: "Entrega de Reporte", start: "2025-09-22" },
      { title: "Examen Final", start: "2025-09-25" },
      { title: "Clausura del Curso", start: "2025-09-28" }
    ]
    // Se eliminó la función select para evitar que los usuarios agreguen eventos
  });

  calendar.render();
});
</script>

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