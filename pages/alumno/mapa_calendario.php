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
    margin-top: 20px;
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
    background-color: #19a473;
    color: white;
    padding: 12px 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
    display: flex;
    align-items: center;
  }

  .acordeon-header:hover {
    background-color: #148a60;
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

  /* Color del marcador del edificio */
  .color-indicator {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 10px;
    border: 2px solid white;
    box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
  }

  /* Estilos para eventos del calendario */
  .fc-event {
    cursor: pointer;
    border-radius: 4px;
  }

  .fc-event:hover {
    opacity: 0.9;
    transform: translateY(-1px);
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
        echo "<p>Error al cargar los datos del mapa: " . json_last_error_msg() . "</p>";
    } else {
        // Extraer datos
        $edificios = isset($data['edificios']) ? $data['edificios'] : [];
        $eventos = isset($data['eventos']) ? $data['eventos'] : [];
        $marcadores = isset($data['marcadores']) ? $data['marcadores'] : [];
        $mapaConfig = isset($data['mapaConfig']) ? $data['mapaConfig'] : $mapaConfig;
    }
} else {
    echo "<p>No hay información disponible. Los datos se cargarán cuando el administrador los agregue.</p>";
}
?>

<!-- ===================== CONTENIDO ===================== -->
<div class="contenedor-principal">
  <h1>Mapa Interactivo y Calendario de Actividades</h1>

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
              <?php echo htmlspecialchars($edificio['nombre']); ?>
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
        <p>No hay información disponible de edificios. El administrador puede agregar edificios desde el panel de administración.</p>
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

// -------- MAPA --------
// Definir límites estrictos para la universidad - USANDO TUS COORDENADAS EXACTAS
const boundsUniversidad = [
  [31.765665, -106.564959],  // Esquina inferior izquierda
  [31.767519, -106.560205]   // Esquina superior derecha
];

const map = L.map("map", {
  center: [31.766592, -106.562582], // Punto central dentro de los límites
  zoom: 17,
  minZoom: 16,
  maxZoom: 19,
  zoomControl: true,
  maxBounds: boundsUniversidad,
  maxBoundsViscosity: 1.0,  // Evita que el usuario se salga de los límites
  inertia: false,  // Desactiva inercia para mejor control
  bounceAtZoomLimits: false  // Evita rebotes en los límites de zoom
});

// Agregar capa de mapa satelital
L.tileLayer(
  "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
  { 
    attribution: "Tiles © Esri — Source: Esri, Earthstar Geographics, Maxar",
    maxZoom: 19,
    minZoom: 16
  }
).addTo(map);

// Agregar rectángulo que muestra el área permitida (opcional, para referencia)
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
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 2px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 12px;
        cursor: pointer;
      ">
        ${nombre.charAt(0)}
      </div>
    `,
    iconSize: [30, 30],
    iconAnchor: [15, 15]
  });
  
  const marker = L.marker(lugar.coords, { icon: iconoPersonalizado })
    .addTo(map)
    .bindPopup(`
      <div style="text-align: center; min-width: 150px; padding: 10px;">
        <div style="
          background-color: ${color};
          width: 40px;
          height: 40px;
          border-radius: 50%;
          margin: 0 auto 10px;
          display: flex;
          align-items: center;
          justify-content: center;
          color: white;
          font-weight: bold;
          font-size: 16px;
          border: 3px solid white;
          box-shadow: 0 0 0 2px rgba(0,0,0,0.1);
        ">
          ${nombre.charAt(0)}
        </div>
        <h4 style="margin: 0; color: ${color}; font-size: 16px;">${nombre}</h4>
        <p style="margin: 5px 0 0; color: #666; font-size: 12px;">Haz clic para más información</p>
      </div>
    `)
    .on('click', function() {
      // Desplazarse a la sección del acordeón
      const elem = document.querySelector(`#${lugar.id}`);
      if(elem){
        // Abrir el acordeón
        const content = elem.querySelector('.acordeon-content');
        if (content.style.display === 'block') {
          content.style.display = 'none';
        } else {
          // Cerrar todos los acordeones primero
          document.querySelectorAll('.acordeon-content').forEach(c => c.style.display = 'none');
          content.style.display = 'block';
        }
        
        // Desplazarse suavemente
        elem.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
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

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    locale: 'es',
    firstDay: 1,
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay"
    },
    events: eventosFormateados.length > 0 ? eventosFormateados : [
      { 
        title: "Sin eventos programados", 
        start: new Date().toISOString().split('T')[0],
        backgroundColor: '#f0f0f0',
        borderColor: '#ddd',
        color: '#666'
      }
    ],
    eventClick: function(info) {
      const evento = info.event;
      const descripcion = evento.extendedProps.description;
      const tipo = evento.extendedProps.tipo;
      
      // Mostrar información detallada del evento
      Swal.fire({
        title: evento.title,
        html: `
          <div style="text-align: left;">
            <p><strong>Fecha:</strong> ${evento.start ? new Date(evento.start).toLocaleDateString('es-ES') : ''}</p>
            ${evento.end ? `<p><strong>Hasta:</strong> ${new Date(evento.end).toLocaleDateString('es-ES')}</p>` : ''}
            <p><strong>Tipo:</strong> ${tipo}</p>
            ${descripcion ? `<p><strong>Descripción:</strong> ${descripcion}</p>` : '<p><em>Sin descripción adicional</em></p>'}
          </div>
        `,
        icon: 'info',
        confirmButtonColor: '#19a473',
        confirmButtonText: 'Cerrar',
        background: '#EDE5D6'
      });
    },
    eventContent: function(arg) {
      const evento = arg.event;
      const title = evento.title;
      
      // Crear contenido personalizado para el evento
      const container = document.createElement('div');
      container.style.cssText = `
        padding: 2px 4px;
        border-radius: 3px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        font-size: 12px;
      `;
      
      container.innerHTML = `
        <div style="display: flex; align-items: center; gap: 4px;">
          <div style="
            width: 8px;
            height: 8px;
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
  });

  calendar.render();
  
  // Si no hay eventos, mostrar mensaje
  if (eventosFormateados.length === 0) {
    setTimeout(() => {
      const noEventsMsg = document.createElement('div');
      noEventsMsg.style.cssText = `
        text-align: center;
        padding: 20px;
        color: #666;
        font-style: italic;
        background: #f9f9f9;
        border-radius: 8px;
        margin-top: 10px;
      `;
      noEventsMsg.textContent = 'No hay eventos programados. El administrador puede agregar eventos desde el panel de administración.';
      
      const calendarHeader = document.querySelector('.fc-header-toolbar');
      if (calendarHeader) {
        calendarHeader.parentNode.insertBefore(noEventsMsg, calendarHeader.nextSibling);
      }
    }, 100);
  }
});

// -------- ACORDEÓN --------
document.querySelectorAll(".acordeon-header").forEach(header => {
  header.addEventListener("click", () => {
    const content = header.nextElementSibling;
    const isOpen = content.style.display === "block";
    
    // Cerrar todos los demás acordeones
    document.querySelectorAll(".acordeon-content").forEach(c => {
      if (c !== content) {
        c.style.display = "none";
      }
    });
    
    // Alternar el actual
    content.style.display = isOpen ? "none" : "block";
  });
});

// Función para abrir acordeón específico desde URL hash
function abrirAcordeonDesdeHash() {
  const hash = window.location.hash;
  if (hash) {
    const elemento = document.querySelector(hash);
    if (elemento && elemento.classList.contains('acordeon-item')) {
      const content = elemento.querySelector('.acordeon-content');
      if (content) {
        // Cerrar todos los acordeones
        document.querySelectorAll('.acordeon-content').forEach(c => c.style.display = 'none');
        // Abrir el solicitado
        content.style.display = 'block';
        // Desplazarse a él
        elemento.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }
  }
}

// Ejecutar al cargar la página
window.addEventListener('load', abrirAcordeonDesdeHash);
window.addEventListener('hashchange', abrirAcordeonDesdeHash);
</script>

<?php include("../../includes/footer.php"); ?>