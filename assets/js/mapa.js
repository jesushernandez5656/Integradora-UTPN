document.addEventListener("DOMContentLoaded", function () {
  // Coordenadas base — ajusta según tu universidad
  const map = L.map('map', {
    zoomControl: false // se ocultará el zoom por defecto en móvil
  }).setView([21.505, -104.9], 17);

  // Agregar el mapa base
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
  }).addTo(map);

  // Control de zoom adaptado
  L.control.zoom({ position: 'bottomright' }).addTo(map);

  // --- Marcadores del campus ---
  const puntos = [
    {
      nombre: "Edificio Principal",
      coords: [21.5052, -104.9003],
      desc: "Oficinas administrativas y aulas principales."
    },
    {
      nombre: "Cancha Deportiva",
      coords: [21.5058, -104.9010],
      desc: "Área deportiva y eventos estudiantiles."
    },
    {
      nombre: "Cafetería",
      coords: [21.5049, -104.8996],
      desc: "Comedor universitario y zona de descanso."
    },
    {
      nombre: "Estacionamiento",
      coords: [21.5063, -104.9001],
      desc: "Área de estacionamiento para personal y alumnos."
    }
  ];

  // Agregar marcadores con popups
  puntos.forEach(p => {
    L.marker(p.coords)
      .addTo(map)
      .bindPopup(`<b>${p.nombre}</b><br>${p.desc}`);
  });

  // Ajustar mapa al tamaño de pantalla
  function ajustarAltura() {
    const altoPantalla = window.innerHeight;
    const headerAltura = document.querySelector("header")?.offsetHeight || 0;
    const footerAltura = document.querySelector("footer")?.offsetHeight || 0;
    const espacioDisponible = altoPantalla - headerAltura - footerAltura - 50;
    document.getElementById("map").style.height = `${espacioDisponible}px`;
  }

  ajustarAltura();
  window.addEventListener("resize", ajustarAltura);
});
