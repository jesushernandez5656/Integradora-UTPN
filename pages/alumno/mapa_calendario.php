<?php include("../../includes/header.php"); ?>

<!-- CSS de Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<!-- CSS de FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">

<!-- Tus CSS locales -->
<link rel="stylesheet" href="../../assets/css/mapa_calendario.css">
<link rel="stylesheet" href="../../assets/css/navbar.css">
<link rel="stylesheet" href="../../assets/css/footer.css">
<link rel="stylesheet" href="../../assets/css/style.css">

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

<div class="container">
  <h2>Mapa y Calendario de la UTPN</h2>

  <!-- MAPA -->
  <h3>Mapa de la Universidad</h3>
  <div id="map"></div>

  <!-- Información de los Edificios -->
  <div id="info-edificios">
    <!-- (Todo tu contenido del acordeón igual que antes) -->
  </div>

  <!-- CALENDARIO -->
  <h3>Calendario de Actividades</h3>
  <div id="calendar"></div>
</div>

<!-- JS de Leaflet -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- JS de FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<!-- Tus JS locales -->
<script src="../../assets/js/mapa_calendario.js"></script>

<script>
  // --- LÓGICA DEL ACORDEÓN ---
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
