<?php include("../../includes/header.php"); ?>

<!-- CSS de Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<!-- CSS de FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">

<!-- Tu CSS -->
<link rel="stylesheet" href="mapa_calendario.css">

<style>
  /* --- ESTILO DE PESTAÑAS --- */
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
    background-color: #19a473ff;
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
</style>

<div class="container">
  <h2>Mapa y Calendario de la UTPN</h2>

  <!-- MAPA -->
  <h3>Mapa de la Universidad</h3>
  <div id="map"></div>

  <!-- Información de los Edificios -->
  <div id="info-edificios">
    <h3>Información de los Edificios</h3>

    <div class="acordeon">

      <!-- EDIFICIO A -->
      <div class="acordeon-item">
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
      <div class="acordeon-item">
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
      <div class="acordeon-item">
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
      <div class="acordeon-item">
        <div class="acordeon-header">Edificio D</div>
        <div class="acordeon-content">
          <p><b>Audiovisual:</b> Área para proyección de material audiovisual académico así como salón para avisos o eventos ubicados en el edificio D.</p>
          <p><b>Dirección de Carrera:</b> Oficina de coordinación académica de las ingenierías y licenciaturas principales.</p>
          <p><b>Sala de Maestros:</b> Espacio de trabajo y reuniones para el personal docente.</p>
        </div>
      </div>

      <!-- EDIFICIO E -->
      <div class="acordeon-item">
        <div class="acordeon-header">Edificio E</div>
        <div class="acordeon-content">
          <p><b>Laboratorio Dobot / Robótica Colaborativa:</b> Laboratorio de robótica colaborativa con robots Dobot y automatización de tareas.</p>
          <p><b>Quality Room:</b> Espacio para pruebas de calidad y control de procesos académicos y tecnológicos.</p>
          <p><b>Laboratorio Manufactura Aditiva:</b> Laboratorio de impresión 3D y procesos de manufactura avanzada.</p>
          <p><b>Laboratorio de Arquitectura:</b> Espacio dedicado a los estudiantes de diseño arquitectónico y proyectos constructivos.</p>
          <p><b>Departamento de Infraestructura Informática:</b> Gestión de infraestructura tecnológica y sistemas informáticos de la universidad.</p>
          <p><b>Laboratorio SMT:</b> Laboratorio de ensamblaje de circuitos electrónicos y soldadura SMT.</p>
          <p><b>Laboratorio de Internet de las Cosas:</b> Desarrollo de dispositivos y sistemas conectados a IoT.</p>
          <p><b>Sala 3D:</b> Espacio de modelado y visualización tridimensional.</p>
          <p><b>Laboratorio de Robótica Móvil:</b> Prácticas y experimentación con robots móviles y automatización.</p>
          <p><b>Salón Realidad Virtual:</b> Área dedicada a la realidad virtual y simulaciones inmersivas.</p>
        </div>
      </div>


      <!-- Cafeteria -->
      <div class="acordeon-item">
        <div class="acordeon-header">Cafetería</div>
        <div class="acordeon-content">
          <p><b>Menú del Día:</b> Variedad de platillos y bebidas disponibles.</p>
          <p><b>Área de Comedores:</b> Espacios para comer y socializar.</p>
          
        </div>
      </div>

      <!-- Cancha de futbol -->
      <div class="acordeon-item">
        <div class="acordeon-header">Cancha de Fútbol</div>
        <div class="acordeon-content">
          <p><b>Descripción:</b> Espacio deportivo para la práctica de fútbol.</p>
          <p><b>Normativas:</b> Reglas y regulaciones para el uso de la cancha para mantener su buen esrado y reservar horarios de uso.</p>
        </div>
      </div>

      <!-- Cancha de basquetbol -->
      <div class="acordeon-item">
        <div class="acordeon-header">Cancha de basquetbol</div>
        <div class="acordeon-content">
          <p><b>Descripción:</b> Espacio deportivo para la práctica de basquetbol.</p>
          <p><b>Normativas:</b> Reglas y regulaciones para el uso de la cancha.</p>
        </div>
      </div>

       <!-- Cancha de voleybol -->
      <div class="acordeon-item">
        <div class="acordeon-header">Cancha de voleibol</div>
        <div class="acordeon-content">
          <p><b>Descripción:</b> Espacio deportivo para la práctica de voleibol.</p>
          <p><b>Normativas:</b> Reglas y regulaciones para el uso de la cancha.</p>
        </div>
      </div>

       <!-- Cancha de voleybol playero -->
      <div class="acordeon-item">
        <div class="acordeon-header">Cancha de voleibol playero</div>
        <div class="acordeon-content">
          <p><b>Descripción:</b> Espacio deportivo para la práctica de voleibol playero.</p>
          <p><b>Normativas:</b> Reglas y regulaciones para el uso de la cancha.</p>
        </div>
      </div>

       <!-- Quiosco -->
      <div class="acordeon-item">
        <div class="acordeon-header">Quiosco</div>
        <div class="acordeon-content">
          <p><b>Descripción:</b> Espacio tranquilo para poder pasar un rato al aire libre, tomarse fotos o realizar actividades en conjunto.</p>
        </div>
      </div>

       <!-- Punto de reunion -->
      <div class="acordeon-item">
        <div class="acordeon-header">Punto de reunión</div>
        <div class="acordeon-content">
          <p><b>Descripción:</b> Espacio destinado para que los estudiantes se reúnan en situaciones de emergencia.</p>
        </div>
      </div>

        </div>
      </div>

    </div>
  </div>

  <!-- CALENDARIO -->
  <h3>Calendario de Actividades</h3>
  <div id="calendar"></div>
</div>

<!-- JS de Leaflet -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- JS de FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<!-- Tu JS -->
<script src="mapa_calendario.js"></script>

<script>
  // --- LÓGICA DEL ACORDEÓN ---
  document.querySelectorAll(".acordeon-header").forEach(header => {
    header.addEventListener("click", () => {
      const content = header.nextElementSibling;
      const isOpen = content.style.display === "block";

      // Cierra todos los demás
      document.querySelectorAll(".acordeon-content").forEach(c => c.style.display = "none");

      // Abre o cierra el actual
      content.style.display = isOpen ? "none" : "block";
    });
  });
</script>

<?php include("../../includes/footer.php"); ?>
