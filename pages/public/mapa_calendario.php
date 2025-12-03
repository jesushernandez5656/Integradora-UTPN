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
    min-height: 600px;
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

  /* ==== ESTILOS MEJORADOS PARA FULLCALENDAR ==== */
  .fc {
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  }

  .fc-toolbar {
    flex-wrap: wrap;
    gap: 10px;
  }

  .fc-toolbar-title {
    font-size: 1.5em;
    font-weight: 600;
    color: #3b3b3b;
  }

  .fc-button {
    background-color: #19a473 !important;
    border-color: #19a473 !important;
    font-weight: 500;
  }

  .fc-button:hover {
    background-color: #148a60 !important;
    border-color: #148a60 !important;
  }

  .fc-button-active {
    background-color: #0d6e4c !important;
    border-color: #0d6e4c !important;
  }

  .fc-event {
    background-color: #19a473;
    border-color: #19a473;
    cursor: pointer;
    font-size: 0.85em;
    padding: 2px 4px;
  }

  .fc-day-today {
    background-color: #e8f5e8 !important;
  }

  .fc-event-title {
    font-weight: 500;
  }

  /* Responsive */
  @media (max-width: 768px) {
    #calendar {
      min-height: 400px;
      padding: 10px;
    }
    
    .fc-toolbar {
      flex-direction: column;
      align-items: flex-start;
    }
    
    .fc-toolbar-chunk {
      margin-bottom: 10px;
    }
  }
</style>

<?php
// Cargar datos desde el archivo JSON en la ubicación especificada
$jsonPath = '../../assets/js/mapa.json';

// Verificar si el archivo existe
if (file_exists($jsonPath)) {
    $jsonData = file_get_contents($jsonPath);
    $data = json_decode($jsonData, true);
    
    // Verificar si hubo error al decodificar el JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<p>Error al cargar los datos del mapa: " . json_last_error_msg() . "</p>";
        $data = []; // Establecer datos vacíos para evitar errores
    }
} else {
    echo "<p>Error: No se encontró el archivo de datos en $jsonPath</p>";
    $data = []; // Establecer datos vacíos para evitar errores
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
      <?php if (!empty($data['edificios'])): ?>
        <?php foreach ($data['edificios'] as $edificio): ?>
          <div class="acordeon-item" id="<?php echo $edificio['id']; ?>">
            <div class="acordeon-header"><?php echo $edificio['nombre']; ?></div>
            <div class="acordeon-content">
              <?php foreach ($edificio['descripciones'] as $descripcion): ?>
                <p><b><?php echo $descripcion['titulo']; ?>:</b> <?php echo $descripcion['contenido']; ?></p>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay información disponible de edificios.</p>
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

// -------- CALENDARIO ACTUALIZADO --------
document.addEventListener("DOMContentLoaded", () => {
  const calendarEl = document.getElementById("calendar");

  // Verificar que el elemento del calendario existe
  if (!calendarEl) {
    console.error('Elemento del calendario no encontrado');
    return;
  }

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    locale: 'es', // Establecer idioma español
    firstDay: 1, // Lunes como primer día de la semana
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay"
    },
    buttonText: {
      today: "Hoy",
      month: "Mes",
      week: "Semana",
      day: "Día"
    },
    events: [], // Se cargarán dinámicamente desde el JSON
    eventClick: function(info) {
      // Mostrar información del evento al hacer clic
      const descripcion = info.event.extendedProps.description || 'Sin descripción adicional';
      const tipo = info.event.extendedProps.tipo || 'académico';
      
      alert(`Evento: ${info.event.title}\n\nFecha: ${info.event.start.toLocaleDateString('es-ES')}\nTipo: ${tipo}\n\n${descripcion}`);
    },
    loading: function(isLoading) {
      if (isLoading) {
        console.log('Cargando eventos...');
      } else {
        console.log('Eventos cargados');
      }
    }
  });

  // Cargar eventos desde el JSON
  cargarEventosDesdeJSON(calendar);

  calendar.render();
  console.log("Calendario inicializado correctamente");
});

// Función para cargar eventos desde el JSON
function cargarEventosDesdeJSON(calendar) {
  fetch('../../assets/js/mapa.json')
    .then(response => {
      if (!response.ok) {
        throw new Error('Error al cargar el JSON');
      }
      return response.json();
    })
    .then(data => {
      console.log("Datos cargados del JSON:", data);
      
      // Cargar eventos en el calendario
      if (data.eventos && data.eventos.length > 0) {
        console.log(`Se encontraron ${data.eventos.length} eventos`);
        
        // Limpiar eventos existentes y agregar los nuevos
        calendar.removeAllEvents();
        
        // Procesar cada evento para asegurar el formato correcto
        const eventosProcesados = data.eventos.map(evento => {
          return {
            id: evento.id || Math.random().toString(36).substr(2, 9),
            title: evento.title || 'Evento sin título',
            start: evento.start,
            end: evento.end || null,
            backgroundColor: evento.backgroundColor || '#19a473',
            borderColor: evento.borderColor || '#19a473',
            description: evento.description || '',
            tipo: evento.tipo || 'academico',
            allDay: true // Por defecto, eventos de todo el día
          };
        });
        
        calendar.addEventSource(eventosProcesados);
        console.log('Eventos agregados al calendario:', eventosProcesados);
        
      } else {
        console.log("No hay eventos en el JSON, cargando eventos por defecto");
        // Cargar eventos por defecto si no hay en el JSON
        cargarEventosPorDefecto(calendar);
      }
    })
    .catch(error => {
      console.error('Error cargando eventos desde JSON:', error);
      // Cargar eventos por defecto en caso de error
      cargarEventosPorDefecto(calendar);
    });
}

// Función para cargar eventos por defecto
function cargarEventosPorDefecto(calendar) {
  const eventosPorDefecto = [
    { 
      title: "Entrega de Proyecto", 
      start: "2025-09-10",
      backgroundColor: '#19a473',
      description: "Fecha límite para la entrega del proyecto final"
    },
    { 
      title: "Revisión de Avances", 
      start: "2025-09-12",
      backgroundColor: '#19a473',
      description: "Revisión de avances del proyecto con el tutor"
    },
    { 
      title: "Exposición Parcial", 
      start: "2025-09-15",
      backgroundColor: '#19a473',
      description: "Presentación de avances ante el comité evaluador"
    },
    { 
      title: "Práctica de Laboratorio", 
      start: "2025-09-18",
      backgroundColor: '#19a473',
      description: "Sesión práctica en el laboratorio especializado"
    },
    { 
      title: "Reunión Académica", 
      start: "2025-09-20",
      backgroundColor: '#19a473',
      description: "Reunión general del departamento académico"
    },
    { 
      title: "Entrega de Reporte", 
      start: "2025-09-22",
      backgroundColor: '#19a473',
      description: "Entrega del reporte técnico final"
    },
    { 
      title: "Examen Final", 
      start: "2025-09-25",
      backgroundColor: '#19a473',
      description: "Examen final del semestre"
    },
    { 
      title: "Clausura del Curso", 
      start: "2025-09-28",
      backgroundColor: '#19a473',
      description: "Ceremonia de clausura y entrega de reconocimientos"
    }
  ];

  calendar.addEventSource(eventosPorDefecto);
  console.log('Eventos por defecto cargados');
}
</script>

<!-- ===================== ACORDEÓN ===================== -->
<script>
  document.querySelectorAll(".acordeon-header").forEach(header => {
    header.addEventListener("click", () => {
      const content = header.nextElementSibling;
      const isOpen = content.style.display === "block";
      
      // Cerrar todos los acordeones primero
      document.querySelectorAll(".acordeon-content").forEach(c => {
        c.style.display = "none";
      });
      
      // Abrir/cerrar el acordeón actual
      content.style.display = isOpen ? "none" : "block";
    });
  });

  // Abrir el primer acordeón por defecto
  document.addEventListener('DOMContentLoaded', function() {
    const firstAcordeon = document.querySelector('.acordeon-content');
    if (firstAcordeon) {
      firstAcordeon.style.display = 'block';
    }
  });
</script>

<?php include("../../includes/footer.php"); ?>