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