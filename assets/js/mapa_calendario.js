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
      { title: "Entrega de Proyecto", start: "2025-09-10" },
      { title: "Revisión de Avances", start: "2025-09-12" },
      { title: "Exposición Parcial", start: "2025-09-15" },
      { title: "Práctica de Laboratorio", start: "2025-09-18" },
      { title: "Reunión Académica", start: "2025-09-20" },
      { title: "Entrega de Reporte", start: "2025-09-22" },
      { title: "Examen Final", start: "2025-09-25" },
      { title: "Clausura del Curso", start: "2025-09-28" }
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
