<?php include("../../includes/header.php"); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">
<link rel="stylesheet" href="mapa.css"> <!-- Tu archivo de estilos separado -->

<div class="container">
  <h2>Mapa y Calendario UTPN</h2>

  <!-- Tarjeta del Mapa -->
  <div class="card">
    <h3>Mapa de la Universidad</h3>
    <div id="map"></div>
  </div>

  <!-- Tarjeta del Calendario -->
  <div class="card">
    <h3>Calendario de Actividades</h3>
    <div id="calendar"></div>
  </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<script>
// -------- MAPA --------
const map = L.map("map", {
  center: [31.766600, -106.56248729667603], 
  zoom: 17,
  minZoom: 17,      // 🔹 No podrá alejarse más de este nivel
  zoomControl: false // 🔹 Elimina los botones (+ / –)
});

// 🔹 Limitar el arrastre para mantener la universidad centrada
const bounds = [
  [31.7655, -106.5645], // esquina suroeste aproximada
  [31.7685, -106.5600]  // esquina noreste aproximada
];
map.setMaxBounds(bounds);

// --- SATELITAL ESRI ---
L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {
  attribution: "Tiles © Esri — Source: Esri, Earthstar Geographics, Maxar"
}).addTo(map);

// --- MARCADORES (con estructura editable) ---
L.marker([31.766987374805954, -106.56023111858856]).addTo(map).bindPopup(`
  <b>Entrada Principal</b><br>
  <ul>
    
  </ul>
`);

L.marker([31.766365511367177, -106.56166338142302]).addTo(map).bindPopup(`
  <b>Edificio A</b><br>
  <ul>
    <li>Example mark 1</li>
    <li>Example mark 2</li>
  </ul>
`);

L.marker([31.766841645023472, -106.56102909656026]).addTo(map).bindPopup(`
  <b>Edificio B</b><br>
  <ul>
    <li>Example mark 1</li>
    <li>Example mark 2</li>
  </ul>
`);

L.marker([31.76627811886781, -106.56245778374044]).addTo(map).bindPopup(`
  <b>Edificio C</b><br>
  <ul>
    <li>Example mark 1</li>
    <li>Example mark 2</li>
  </ul>
`);

L.marker([31.766257798593916, -106.563129401242694]).addTo(map).bindPopup(`
  <b>Edificio D</b><br>
  <ul>
    <li>Example mark 1</li>
    <li>Example mark 2</li>
  </ul>
`);

L.marker([31.766248767145573, -106.56389772444955]).addTo(map).bindPopup(`
  <b>Edificio E</b><br>
  <ul>
    <li>Example mark 1</li>
    <li>Example mark 2</li>
  </ul>
`);

L.marker([31.767125033013915, -106.56140786196244]).addTo(map).bindPopup(`
  <b>Cafetería</b>
`);

L.marker([31.766970077979806, -106.56308318968154]).addTo(map).bindPopup(`
  <b>Cancha de Fútbol</b>
`);

L.marker([31.766968880896098, -106.56343654851547]).addTo(map).bindPopup(`
  <b>Cancha de Voleibol</b>
`);

L.marker([31.767213023750685, -106.56247437733018]).addTo(map).bindPopup(`
  <b>Cancha de Voleibol Playero</b>
`);

L.marker([31.766961941341236, -106.56280712333786]).addTo(map).bindPopup(`
  <b>Cancha de Basquetbol</b>
`);

L.marker([31.766974870070786, -106.56248066354152]).addTo(map).bindPopup(`
  <b>Quiosco</b>
`);

L.marker([31.766993499754374, -106.56201627006439]).addTo(map).bindPopup(`
  <b>Punto de reunión</b>
`);



// -------- CALENDARIO --------
document.addEventListener("DOMContentLoaded", () => {
  const calendarEl = document.getElementById("calendar");
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    selectable: true,
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay"
    },
    events: [
      { title: "Reunión Académica", start: "2025-09-25" },
      { title: "Examen Final", start: "2025-09-28" },
    ],
    select: function(info) {
      const title = prompt("Nombre del evento:");
      if (title) {
        calendar.addEvent({
          title: title,
          start: info.start,
          end: info.end,
          allDay: info.allDay
        });
      }
    }
  });

  calendar.render();
});
</script>

<?php include("../../includes/footer.php"); ?> -->
