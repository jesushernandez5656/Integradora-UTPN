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

  h1, h2 {
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

  <div id="map"></div>

  <h2>Calendario Académico</h2>
  <div id="calendar"></div>

  <div class="seccion-lugares">


    <!-- Puedes seguir agregando más secciones si gustas -->
  </div>
</div>

<!-- ===================== JS EXTERNOS ===================== -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<!-- ===================== JS LOCAL ===================== -->
<script src="../../assets/js/mapa_calendario.js"></script>

<?php include("../../includes/footer.php"); ?>
