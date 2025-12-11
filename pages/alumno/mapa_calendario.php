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
    background-color: #EDE5D6;
    overflow-x: hidden; /* Prevenir scroll horizontal */
  }

  .contenedor-principal {
    max-width: 1200px;
    margin: 0 auto;
    padding: 15px;
  }

  h1, h2, h3 {
    text-align: center;
    font-weight: 700;
    color: #3b3b3b;
    margin-bottom: 15px;
  }

  h1 {
    font-size: 1.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 10px;
    margin-bottom: 20px;
  }

  h2 {
    font-size: 1.4rem;
    margin-top: 30px;
  }

  h3 {
    font-size: 1.2rem;
    margin-bottom: 15px;
  }

  /* ==== RESPONSIVE GENERAL ==== */
  @media (max-width: 768px) {
    .contenedor-principal {
      padding: 12px;
    }
    
    h1 {
      font-size: 1.5rem;
      margin-bottom: 15px;
    }
    
    h2 {
      font-size: 1.3rem;
      margin-top: 25px;
    }
    
    h3 {
      font-size: 1.1rem;
    }
  }

  @media (max-width: 480px) {
    .contenedor-principal {
      padding: 10px;
    }
    
    h1 {
      font-size: 1.3rem;
      letter-spacing: 0.3px;
    }
    
    h2 {
      font-size: 1.15rem;
      margin-top: 20px;
    }
    
    h3 {
      font-size: 1rem;
    }
  }

  /* ==== MAPA ==== */
  #map {
    height: 400px;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    margin-bottom: 25px;
  }

  @media (max-width: 768px) {
    #map {
      height: 350px;
      border-radius: 12px;
      margin-bottom: 20px;
    }
    
    /* Ajustar controles del mapa para móvil */
    .leaflet-control-zoom {
      margin: 10px !important;
    }
    
    .leaflet-control-zoom a {
      width: 35px !important;
      height: 35px !important;
      line-height: 35px !important;
      font-size: 18px !important;
    }
  }

  @media (max-width: 480px) {
    #map {
      height: 300px;
      border-radius: 10px;
      margin-bottom: 15px;
    }
  }

  /* ==== CALENDARIO ==== */
  #calendar {
    background-color: #fff;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-top: 20px;
    margin-bottom: 20px;
  }

  /* Ajustes responsivos para FullCalendar */
  @media (max-width: 768px) {
    #calendar {
      padding: 12px;
      border-radius: 10px;
    }
    
    .fc-header-toolbar {
      flex-direction: column !important;
      gap: 10px !important;
    }
    
    .fc-header-toolbar .fc-toolbar-chunk {
      width: 100% !important;
      text-align: center !important;
      margin-bottom: 5px !important;
    }
    
    .fc-toolbar-title {
      font-size: 1.1rem !important;
      margin: 5px 0 !important;
    }
    
    .fc-button {
      padding: 6px 10px !important;
      font-size: 0.85rem !important;
      min-height: 36px !important;
    }
    
    .fc-daygrid-event {
      font-size: 0.75rem !important;
    }
  }

  @media (max-width: 480px) {
    #calendar {
      padding: 10px;
    }
    
    .fc-toolbar-title {
      font-size: 1rem !important;
    }
    
    .fc-button {
      padding: 5px 8px !important;
      font-size: 0.75rem !important;
      min-height: 32px !important;
    }
    
    .fc-col-header-cell {
      font-size: 0.8rem !important;
      padding: 5px 0 !important;
    }
    
    .fc-daygrid-day-number {
      font-size: 0.85rem !important;
      padding: 2px !important;
    }
    
    .fc-view-harness {
      min-height: 350px !important;
    }
    
    /* Simplificar toolbar en móvil muy pequeño */
    @media (max-width: 360px) {
      .fc-header-toolbar {
        flex-wrap: wrap !important;
      }
      
      .fc-toolbar-chunk:nth-child(3) {
        order: 2 !important;
        margin-top: 5px !important;
      }
    }
  }

  /* ==== ACORDEÓN ==== */
  .acordeon {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-top: 15px;
  }

  .acordeon-item {
    border-bottom: 1px solid #ddd;
  }

  .acordeon-header {
    background-color: #19a473;
    color: white;
    padding: 14px 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 50px; /* Tamaño táctil */
  }

  .acordeon-header::after {
    content: '+';
    font-size: 1.4rem;
    font-weight: normal;
    transition: transform 0.3s;
  }

  .acordeon-header.active::after {
    content: '-';
    transform: rotate(0deg);
  }

  .acordeon-header:hover {
    background-color: #148a60;
  }

  .acordeon-content {
    display: none;
    padding: 15px;
    background: #f8f8f8;
    line-height: 1.6;
    font-size: 0.95rem;
  }

  .acordeon-content.active {
    display: block;
  }

  .acordeon-content p {
    margin: 10px 0;
    padding-left: 12px;
    position: relative;
  }

  .acordeon-content p::before {
    content: "•";
    position: absolute;
    left: 0;
    color: #19a473;
    font-size: 1.2em;
  }

  /* Color del marcador del edificio */
  .color-indicator {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 12px;
    border: 2px solid white;
    box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
    flex-shrink: 0; /* Prevenir que se reduzca en móvil */
  }

  @media (max-width: 768px) {
    .acordeon-header {
      padding: 12px 14px;
      font-size: 0.95rem;
      min-height: 45px;
    }
    
    .acordeon-header::after {
      font-size: 1.3rem;
    }
    
    .acordeon-content {
      padding: 12px;
      font-size: 0.9rem;
    }
    
    .acordeon-content p {
      margin: 8px 0;
    }
    
    .color-indicator {
      width: 18px;
      height: 18px;
      margin-right: 10px;
    }
  }

  @media (max-width: 480px) {
    .acordeon-header {
      padding: 10px 12px;
      font-size: 0.9rem;
      min-height: 40px;
    }
    
    .acordeon-header::after {
      font-size: 1.2rem;
    }
    
    .acordeon-content {
      padding: 10px;
      font-size: 0.85rem;
    }
    
    .acordeon-content p {
      margin: 6px 0;
      padding-left: 10px;
    }
    
    .color-indicator {
      width: 16px;
      height: 16px;
      margin-right: 8px;
    }
  }

  /* ==== SECCIONES DE DESCRIPCIÓN ==== */
  .seccion-lugares {
    margin-top: 25px;
    background-color: #fff;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-bottom: 25px;
  }

  @media (max-width: 768px) {
    .seccion-lugares {
      padding: 15px;
      border-radius: 12px;
      margin-top: 20px;
      margin-bottom: 20px;
    }
  }

  @media (max-width: 480px) {
    .seccion-lugares {
      padding: 12px;
      border-radius: 10px;
      margin-top: 15px;
      margin-bottom: 15px;
    }
  }

  /* ==== MEJORAS PARA TEXTOS LARGOS ==== */
  @media (max-width: 768px) {
    p, .acordeon-content p {
      line-height: 1.5;
      word-wrap: break-word;
      overflow-wrap: break-word;
      hyphens: auto; /* Separación silábica */
    }
    
    /* Mejorar legibilidad en móvil */
    .acordeon-content b {
      display: inline-block;
      margin-top: 3px;
    }
  }

  /* ==== POPUPS DEL MAPA RESPONSIVOS ==== */
  .leaflet-popup-content {
    min-width: 140px !important;
    max-width: 200px !important;
  }

  @media (max-width: 480px) {
    .leaflet-popup-content {
      min-width: 120px !important;
      max-width: 160px !important;
      font-size: 0.9rem;
    }
    
    .leaflet-popup-content h4 {
      font-size: 0.9rem !important;
      margin: 5px 0 !important;
    }
    
    .leaflet-popup-content p {
      font-size: 0.8rem !important;
      margin: 3px 0 !important;
    }
  }

  /* ==== MEJORAS PARA BOTONES TÁCTILES ==== */
  .acordeon-header,
  .fc-button,
  .leaflet-control-zoom a {
    touch-action: manipulation; /* Mejora respuesta táctil */
  }

  /* ==== ESTADOS ACTIVOS VISIBLES ==== */
  .acordeon-header:active {
    background-color: #117a50 !important;
    transform: scale(0.98);
  }

  /* ==== MENSAJES SIN DATOS ==== */
  .no-data-message {
    text-align: center;
    padding: 20px;
    color: #666;
    font-style: italic;
    background: #f9f9f9;
    border-radius: 8px;
    margin: 15px 0;
    font-size: 0.95rem;
  }

  @media (max-width: 480px) {
    .no-data-message {
      padding: 15px;
      font-size: 0.85rem;
    }
  }

  /* ==== LOADING STATE (opcional) ==== */
  .loading {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255,255,255,.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s ease-in-out infinite;
    margin-right: 10px;
  }

  @keyframes spin {
    to { transform: rotate(360deg); }
  }
</style>

<?php
// Cargar datos desde el archivo JSON en la ubicación especificada
$jsonPath = '../../assets/js/mapa.json';

// Inicializar variables
$data = [];
$edificios = [];
$eventos = [];
$marcadores = [];
$mapaConfig = [
    'lat' => 31.766600,
    'lng' => -106.562487,
    'zoom' => 17
];

// Verificar si el archivo existe
if (file_exists($jsonPath)) {
    $jsonData = file_get_contents($jsonPath);
    $data = json_decode($jsonData, true);
    
    // Verificar si hubo error al decodificar el JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<div class='no-data-message'>Error al cargar los datos del mapa: " . json_last_error_msg() . "</div>";
    } else {
        // Extraer datos
        $edificios = isset($data['edificios']) ? $data['edificios'] : [];
        $eventos = isset($data['eventos']) ? $data['eventos'] : [];
        $marcadores = isset($data['marcadores']) ? $data['marcadores'] : [];
        $mapaConfig = isset($data['mapaConfig']) ? $data['mapaConfig'] : $mapaConfig;
    }
} else {
    echo "<div class='no-data-message'>No hay información disponible. Los datos se cargarán cuando el administrador los agregue.</div>";
}
?>

<!-- ===================== CONTENIDO ===================== -->
<div class="contenedor-principal">
  <h1>Campus</h1>

  <!-- MAPA -->
  <div id="map"></div>

  <!-- INFORMACIÓN DE LOS EDIFICIOS -->
  <div class="seccion-lugares">
    <h3>Información de los Edificios</h3>
    <div class="acordeon">
      <?php if (!empty($edificios)): ?>
        <?php foreach ($edificios as $edificio): ?>
          <div class="acordeon-item" id="<?php echo htmlspecialchars($edificio['id']); ?>">
            <div class="acordeon-header">
              <span class="color-indicator" style="background-color: <?php echo htmlspecialchars($edificio['color'] ?? '#19a473'); ?>"></span>
              <span class="acordeon-title"><?php echo htmlspecialchars($edificio['nombre']); ?></span>
            </div>
            <div class="acordeon-content">
              <?php if (!empty($edificio['descripcion'])): ?>
                <p><b>Descripción:</b> <?php echo htmlspecialchars($edificio['descripcion']); ?></p>
              <?php endif; ?>
              <?php if (isset($edificio['descripciones']) && is_array($edificio['descripciones'])): ?>
                <?php foreach ($edificio['descripciones'] as $descripcion): ?>
                  <p><b><?php echo htmlspecialchars($descripcion['titulo']); ?>:</b> <?php echo htmlspecialchars($descripcion['contenido']); ?></p>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-data-message">
          No hay información disponible de edificios. El administrador puede agregar edificios desde el panel de administración.
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- CALENDARIO -->
  <h2>Calendario Académico</h2>
  <div id="calendar"></div>
</div>

<!-- ===================== JS EXTERNOS ===================== -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ===================== JS LOCAL ===================== -->
<script>
// Cargar datos desde PHP
const edificiosData = <?php echo json_encode($edificios); ?>;
const eventosData = <?php echo json_encode($eventos); ?>;
const mapaConfig = <?php echo json_encode($mapaConfig); ?>;

// Detectar dispositivo móvil
const isMobile = window.innerWidth <= 768;
const isSmallMobile = window.innerWidth <= 480;

// -------- MAPA --------
// Definir límites estrictos para la universidad
const boundsUniversidad = [
  [31.765665, -106.564959],  // Esquina inferior izquierda
  [31.767519, -106.560205]   // Esquina superior derecha
];

// Configuración del mapa según dispositivo
const mapOptions = {
  center: [31.766592, -106.562582],
  zoom: isMobile ? 16 : 17,
  minZoom: 16,
  maxZoom: isMobile ? 18 : 19,
  zoomControl: true,
  maxBounds: boundsUniversidad,
  maxBoundsViscosity: 1.0,
  inertia: false,
  bounceAtZoomLimits: false,
  touchZoom: true, // Habilitar zoom táctil
  scrollWheelZoom: !isMobile, // Deshabilitar zoom con rueda en móvil
  dragging: true,
  tap: false // Mejorar compatibilidad táctil
};

const map = L.map("map", mapOptions);

// Agregar capa de mapa satelital
L.tileLayer(
  "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
  { 
    attribution: "Tiles © Esri — Source: Esri, Earthstar Geographics, Maxar",
    maxZoom: 19,
    minZoom: 16
  }
).addTo(map);

// Posicionar controles según dispositivo
if (isMobile) {
  map.zoomControl.setPosition('bottomright');
}

// Agregar rectángulo que muestra el área permitida (opcional)
L.rectangle(boundsUniversidad, {
  color: "#19a473",
  weight: 2,
  fillOpacity: 0.03,
  dashArray: '5, 5',
  interactive: false
}).addTo(map);

// Forzar los límites iniciales
map.setMaxBounds(boundsUniversidad);

// Evento para mantener el mapa dentro de los límites
map.on('moveend', function() {
  if (!map.getBounds().intersects(boundsUniversidad)) {
    map.fitBounds(boundsUniversidad);
  }
});

// --- MARCADORES DESDE DATOS ---
const marcadoresBase = [
  { coords: [31.766365511367177, -106.56166338142302], nombre: "Edificio A", id: "edificioA" },
  { coords: [31.766841645023472, -106.56102909656026], nombre: "Edificio B", id: "edificioB" },
  { coords: [31.76627811886781, -106.56245778374044], nombre: "Edificio C", id: "edificioC" },
  { coords: [31.766257798593916, -106.563129401242694], nombre: "Edificio D", id: "edificioD" },
  { coords: [31.766248767145573, -106.56389772444955], nombre: "Edificio E", id: "edificioE" },
  { coords: [31.767125033013915, -106.56140786196244], nombre: "Cafetería", id: "cafetería" },
  { coords: [31.766970077979806, -106.56308318968154], nombre: "Cancha de Fútbol", id: "canchadefutbol" },
  { coords: [31.766968880896098, -106.56343654851547], nombre: "Cancha de Voleibol", id: "canchadevoleibol" },
  { coords: [31.767213023750685, -106.56247437733018], nombre: "Cancha de Voleibol Playero", id: "canchadevoleibolplayero" },
  { coords: [31.766961941341236, -106.56280712333786], nombre: "Cancha de Basquetbol", id: "canchadebasquetbol" },
  { coords: [31.766974870070786, -106.56248066354152], nombre: "Quiosco", id: "quiosco" },
  { coords: [31.766993499754374, -106.56201627006439], nombre: "Punto de reunión", id: "puntodereunion" }
];

// Función para encontrar información de edificio por ID
function encontrarEdificioPorId(id) {
  return edificiosData.find(edificio => edificio.id === id);
}

// Tamaño de icono según dispositivo
const iconSize = isSmallMobile ? 25 : isMobile ? 28 : 30;
const iconAnchor = iconSize / 2;
const fontSize = isSmallMobile ? 10 : isMobile ? 11 : 12;

// Crear marcadores
marcadoresBase.forEach((lugar) => {
  const edificio = encontrarEdificioPorId(lugar.id);
  const color = edificio ? edificio.color : '#19a473';
  const nombre = edificio ? edificio.nombre : lugar.nombre;
  
  // Crear icono personalizado con color del edificio
  const iconoPersonalizado = L.divIcon({
    className: 'custom-marker',
    html: `
      <div style="
        background-color: ${color};
        width: ${iconSize}px;
        height: ${iconSize}px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: ${fontSize}px;
        cursor: pointer;
      ">
        ${nombre.charAt(0)}
      </div>
    `,
    iconSize: [iconSize, iconSize],
    iconAnchor: [iconAnchor, iconAnchor]
  });
  
  const marker = L.marker(lugar.coords, { 
    icon: iconoPersonalizado,
    title: nombre // Para accesibilidad
  })
  .addTo(map)
  .bindPopup(`
    <div style="text-align: center; min-width: ${isSmallMobile ? '120px' : '150px'}; padding: 10px;">
      <div style="
        background-color: ${color};
        width: ${isSmallMobile ? '35px' : '40px'};
        height: ${isSmallMobile ? '35px' : '40px'};
        border-radius: 50%;
        margin: 0 auto 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: ${isSmallMobile ? '14px' : '16px'};
        border: 2px solid white;
        box-shadow: 0 0 0 2px rgba(0,0,0,0.1);
      ">
        ${nombre.charAt(0)}
      </div>
      <h4 style="margin: 0; color: ${color}; font-size: ${isSmallMobile ? '14px' : '16px'};">${nombre}</h4>
      <p style="margin: 5px 0 0; color: #666; font-size: ${isSmallMobile ? '10px' : '12px'};">Toca para más información</p>
    </div>
  `)
  .on('click', function() {
    // Desplazarse a la sección del acordeón
    const elem = document.querySelector(`#${lugar.id}`);
    if(elem){
      // Abrir el acordeón
      const header = elem.querySelector('.acordeon-header');
      const content = elem.querySelector('.acordeon-content');
      
      // Cerrar todos los acordeones primero
      document.querySelectorAll('.acordeon-content').forEach(c => {
        c.style.display = 'none';
        c.previousElementSibling.classList.remove('active');
      });
      
      // Abrir el acordeón seleccionado
      header.classList.add('active');
      content.style.display = 'block';
      
      // Desplazarse suavemente
      setTimeout(() => {
        elem.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }, 100);
      
      // Cerrar el popup
      marker.closePopup();
    }
  });
});

// -------- CALENDARIO --------
document.addEventListener("DOMContentLoaded", () => {
  const calendarEl = document.getElementById("calendar");

  // Formatear eventos para FullCalendar
  const eventosFormateados = eventosData.map(evento => {
    return {
      id: evento.id,
      title: evento.title,
      start: evento.start,
      end: evento.end || null,
      backgroundColor: evento.backgroundColor || '#19a473',
      borderColor: evento.borderColor || evento.backgroundColor || '#19a473',
      extendedProps: {
        description: evento.extendedProps?.description || evento.description || '',
        tipo: evento.extendedProps?.tipo || evento.tipo || 'academico'
      }
    };
  });

  // Configuración del calendario según dispositivo
  const calendarConfig = {
    initialView: isSmallMobile ? "dayGridMonth" : isMobile ? "dayGridMonth" : "dayGridMonth",
    locale: 'es',
    firstDay: 1,
    headerToolbar: {
      left: isSmallMobile ? "prev,next" : "prev,next today",
      center: "title",
      right: isSmallMobile ? "" : (isMobile ? "dayGridMonth,timeGridWeek" : "dayGridMonth,timeGridWeek,timeGridDay")
    },
    buttonText: {
      today: isSmallMobile ? "Hoy" : "Hoy",
      month: isSmallMobile ? "Mes" : "Mes",
      week: isSmallMobile ? "Sem." : "Semana",
      day: "Día"
    },
    events: eventosFormateados.length > 0 ? eventosFormateados : [
      { 
        title: "Sin eventos", 
        start: new Date().toISOString().split('T')[0],
        backgroundColor: '#f0f0f0',
        borderColor: '#ddd',
        color: '#666'
      }
    ],
    height: isSmallMobile ? 350 : isMobile ? 400 : 'auto',
    eventClick: function(info) {
      const evento = info.event;
      const descripcion = evento.extendedProps.description;
      const tipo = evento.extendedProps.tipo;
      
      // Mostrar información detallada del evento
      Swal.fire({
        title: evento.title,
        html: `
          <div style="text-align: left; font-size: ${isMobile ? '14px' : '16px'}">
            <p><strong>Fecha:</strong> ${evento.start ? new Date(evento.start).toLocaleDateString('es-ES') : ''}</p>
            ${evento.end ? `<p><strong>Hasta:</strong> ${new Date(evento.end).toLocaleDateString('es-ES')}</p>` : ''}
            <p><strong>Tipo:</strong> ${tipo}</p>
            ${descripcion ? `<p><strong>Descripción:</strong> ${descripcion}</p>` : '<p><em>Sin descripción adicional</em></p>'}
          </div>
        `,
        icon: 'info',
        confirmButtonColor: '#19a473',
        confirmButtonText: 'Cerrar',
        background: '#EDE5D6',
        width: isMobile ? '90%' : '500px',
        customClass: {
          popup: isMobile ? 'swal-popup-mobile' : ''
        }
      });
    },
    eventContent: function(arg) {
      const evento = arg.event;
      const title = evento.title;
      const isSmallEvent = isSmallMobile || arg.view.type === 'dayGridMonth';
      
      // Crear contenido personalizado para el evento
      const container = document.createElement('div');
      container.style.cssText = `
        padding: ${isSmallEvent ? '1px 2px' : '2px 4px'};
        border-radius: 3px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        font-size: ${isSmallEvent ? '10px' : '12px'};
      `;
      
      container.innerHTML = `
        <div style="display: flex; align-items: center; gap: 3px;">
          <div style="
            width: ${isSmallEvent ? '6px' : '8px'};
            height: ${isSmallEvent ? '6px' : '8px'};
            border-radius: 50%;
            background-color: ${evento.backgroundColor};
            flex-shrink: 0;
          "></div>
          <span title="${title}">${title}</span>
        </div>
      `;
      
      return { domNodes: [container] };
    },
    datesSet: function(info) {
      // Actualizar título con mes y año en español
      const titleEl = document.querySelector('.fc-toolbar-title');
      if (titleEl) {
        const monthNames = [
          'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
          'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        const currentMonth = monthNames[info.view.currentStart.getMonth()];
        const currentYear = info.view.currentStart.getFullYear();
        titleEl.textContent = `${currentMonth} ${currentYear}`;
      }
    }
  };

  const calendar = new FullCalendar.Calendar(calendarEl, calendarConfig);

  calendar.render();
  
  // Si no hay eventos, mostrar mensaje
  if (eventosFormateados.length === 0) {
    setTimeout(() => {
      const noEventsMsg = document.createElement('div');
      noEventsMsg.className = 'no-data-message';
      noEventsMsg.textContent = 'No hay eventos programados.';
      
      const calendarHeader = document.querySelector('.fc-header-toolbar');
      if (calendarHeader) {
        calendarHeader.parentNode.insertBefore(noEventsMsg, calendarHeader.nextSibling);
      }
    }, 100);
  }
  
  // Ajustar calendario en redimensionamiento
  window.addEventListener('resize', () => {
    calendar.updateSize();
  });
});

// -------- ACORDEÓN MEJORADO --------
document.querySelectorAll(".acordeon-header").forEach(header => {
  // Mejorar respuesta táctil
  header.addEventListener("click", (e) => {
    e.preventDefault();
    const content = header.nextElementSibling;
    const isActive = header.classList.contains('active');
    
    // Cerrar todos los acordeones
    document.querySelectorAll('.acordeon-content').forEach(c => {
      c.style.display = 'none';
      c.previousElementSibling.classList.remove('active');
    });
    
    // Abrir el acordeón clickeado si no estaba activo
    if (!isActive) {
      header.classList.add('active');
      content.style.display = 'block';
    }
  });
  
  // Mejorar feedback táctil
  header.addEventListener('touchstart', function() {
    this.style.backgroundColor = '#117a50';
  }, { passive: true });
  
  header.addEventListener('touchend', function() {
    this.style.backgroundColor = '';
  }, { passive: true });
});

// Función para abrir acordeón específico desde URL hash
function abrirAcordeonDesdeHash() {
  const hash = window.location.hash;
  if (hash) {
    const elemento = document.querySelector(hash);
    if (elemento && elemento.classList.contains('acordeon-item')) {
      const header = elemento.querySelector('.acordeon-header');
      const content = elemento.querySelector('.acordeon-content');
      if (content) {
        // Cerrar todos los acordeones
        document.querySelectorAll('.acordeon-content').forEach(c => {
          c.style.display = 'none';
          c.previousElementSibling.classList.remove('active');
        });
        // Abrir el solicitado
        header.classList.add('active');
        content.style.display = 'block';
        // Desplazarse a él
        setTimeout(() => {
          elemento.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
      }
    }
  }
}

// Ejecutar al cargar la página
window.addEventListener('load', abrirAcordeonDesdeHash);
window.addEventListener('hashchange', abrirAcordeonDesdeHash);

// Mejorar rendimiento en móvil
if (isMobile) {
  // Reducir animaciones en móvil
  document.documentElement.style.scrollBehavior = 'smooth';
  
  // Prevenir zoom en inputs (si los hubiera)
  document.addEventListener('touchmove', function(e) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
      e.preventDefault();
    }
  }, { passive: false });
}
</script>

<?php include("../../includes/footer.php"); ?>