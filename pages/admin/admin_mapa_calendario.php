<?php
include "../../includes/header.php";

// ===================== CONFIGURACIÓN PHP =====================
$json_file = __DIR__ . '/../../assets/js/mapa.json';
$data = [];
$edificios = [];
$eventos = [];
$marcadores = [];
$mapaConfig = [
    'lat' => 31.766600,
    'lng' => -106.562487,
    'zoom' => 17
];
$ultimaSincronizacion = null;

// Cargar datos iniciales desde el JSON
function cargarDatosDesdeJSON($archivo) {
    if (file_exists($archivo)) {
        $contenido = file_get_contents($archivo);
        return json_decode($contenido, true);
    }
    return null;
}

function guardarDatosEnJSON($archivo, $datos) {
    $json_data = json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    if (!is_writable(dirname($archivo))) {
        error_log("Directorio no tiene permisos de escritura: " . dirname($archivo));
        return false;
    }
    
    $result = file_put_contents($archivo, $json_data);
    
    if ($result === false) {
        error_log("Error al escribir en archivo: " . $archivo);
        return false;
    }
    
    return true;
}

function getSafe($array, $key, $default = '') {
    return isset($array[$key]) ? $array[$key] : $default;
}

// Cargar datos iniciales
$data = cargarDatosDesdeJSON($json_file);
if ($data) {
    $edificios = getSafe($data, 'edificios', []);
    $eventos = getSafe($data, 'eventos', []);
    $marcadores = getSafe($data, 'marcadores', []);
    $mapaConfig = getSafe($data, 'mapaConfig', $mapaConfig);
    $ultimaSincronizacion = isset($data['ultimaActualizacion']) ? date('d/m/Y H:i:s', strtotime($data['ultimaActualizacion'])) : null;
}

// Procesar acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'guardar_edificio':
            $id = trim(getSafe($_POST, 'id-edificio'));
            $nombre = trim(getSafe($_POST, 'nombre-edificio'));
            $descripcion = trim(getSafe($_POST, 'descripcion-edificio'));
            $color = getSafe($_POST, 'color-edificio', '#19a473');
            
            // Procesar servicios
            $descripciones = [];
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'servicio-titulo-') === 0) {
                    $index = str_replace('servicio-titulo-', '', $key);
                    $titulo = trim($value);
                    $contenido = trim(getSafe($_POST, 'servicio-descripcion-' . $index, ''));
                    
                    if (!empty($titulo) && !empty($contenido)) {
                        $descripciones[] = [
                            'titulo' => $titulo,
                            'contenido' => $contenido
                        ];
                    }
                }
            }
            
            if (empty($id) || empty($nombre)) {
                echo '<div class="alert alert-danger alert-position">ID y Nombre son obligatorios</div>';
                break;
            }
            
            // Buscar y actualizar o crear nuevo edificio
            $edificioEncontrado = false;
            foreach ($edificios as &$edificio) {
                if (getSafe($edificio, 'id') === $id) {
                    $edificio['nombre'] = $nombre;
                    $edificio['descripcion'] = $descripcion;
                    $edificio['color'] = $color;
                    $edificio['descripciones'] = $descripciones;
                    $edificioEncontrado = true;
                    break;
                }
            }
            
            if (!$edificioEncontrado) {
                $edificios[] = [
                    'id' => $id,
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                    'color' => $color,
                    'descripciones' => $descripciones
                ];
            }
            
            // Guardar en JSON
            $data['edificios'] = $edificios;
            $data['ultimaActualizacion'] = date('c');
            if (guardarDatosEnJSON($json_file, $data)) {
                $ultimaSincronizacion = date('d/m/Y H:i:s');
                echo '<div class="alert alert-success alert-position">Edificio guardado correctamente</div>';
            } else {
                echo '<div class="alert alert-danger alert-position">Error al guardar el edificio</div>';
            }
            break;
            
        case 'guardar_evento':
            $id = getSafe($_POST, 'evento-id');
            $titulo = trim(getSafe($_POST, 'titulo-evento'));
            $inicio = getSafe($_POST, 'inicio-evento');
            $fin = getSafe($_POST, 'fin-evento');
            $descripcion = trim(getSafe($_POST, 'descripcion-evento'));
            $color = getSafe($_POST, 'color-evento', '#19a473');
            $tipo = getSafe($_POST, 'tipo-evento', 'academico');
            
            if (empty($titulo) || empty($inicio)) {
                echo '<div class="alert alert-danger alert-position">Título y Fecha de inicio son obligatorios</div>';
                break;
            }
            
            // Formatear fechas para FullCalendar (formato YYYY-MM-DD)
            $start = date('Y-m-d', strtotime($inicio));
            $end = $fin ? date('Y-m-d', strtotime($fin)) : null;
            
            // Si es un evento de un solo día, no usar end date
            if ($end && $start === $end) {
                $end = null;
            }
            
            // Crear evento con estructura CORRECTA para FullCalendar
            $eventoData = [
                'id' => $id ? intval($id) : null,
                'title' => $titulo,
                'start' => $start,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'description' => $descripcion,
                    'tipo' => $tipo
                ]
            ];
            
            // Solo agregar 'end' si existe
            if ($end) {
                $eventoData['end'] = $end;
            }
            
            if (!empty($id)) {
                // Actualizar evento existente
                $eventoEncontrado = false;
                foreach ($eventos as &$evento) {
                    if (isset($evento['id']) && $evento['id'] == $id) {
                        $evento = $eventoData;
                        $eventoEncontrado = true;
                        break;
                    }
                }
                if (!$eventoEncontrado) {
                    // Si no se encuentra, crear nuevo con el ID proporcionado
                    if (!$eventoData['id']) {
                        $eventoData['id'] = count($eventos) > 0 ? max(array_column($eventos, 'id')) + 1 : 1;
                    }
                    $eventos[] = $eventoData;
                }
            } else {
                // Crear nuevo evento
                $nuevoId = count($eventos) > 0 ? max(array_column($eventos, 'id')) + 1 : 1;
                $eventoData['id'] = $nuevoId;
                $eventos[] = $eventoData;
            }
            
            $data['eventos'] = $eventos;
            $data['ultimaActualizacion'] = date('c');
            if (guardarDatosEnJSON($json_file, $data)) {
                $ultimaSincronizacion = date('d/m/Y H:i:s');
                echo '<div class="alert alert-success alert-position">Evento guardado correctamente</div>';
            } else {
                echo '<div class="alert alert-danger alert-position">Error al guardar el evento</div>';
            }
            break;
    }
}

// Procesar acciones GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'cargar_todo':
            $data = cargarDatosDesdeJSON($json_file);
            if ($data) {
                $edificios = getSafe($data, 'edificios', []);
                $eventos = getSafe($data, 'eventos', []);
                $marcadores = getSafe($data, 'marcadores', []);
                $mapaConfig = getSafe($data, 'mapaConfig', $mapaConfig);
                $ultimaSincronizacion = isset($data['ultimaActualizacion']) ? date('d/m/Y H:i:s', strtotime($data['ultimaActualizacion'])) : null;
                echo '<div class="alert alert-success alert-position">Datos cargados correctamente desde JSON</div>';
            } else {
                echo '<div class="alert alert-warning alert-position">Error al cargar los datos</div>';
            }
            break;
            
        case 'guardar_todo':
            $datosCompletos = [
                'edificios' => $edificios,
                'eventos' => $eventos,
                'marcadores' => $marcadores,
                'mapaConfig' => $mapaConfig,
                'ultimaActualizacion' => date('c')
            ];
            
            if (guardarDatosEnJSON($json_file, $datosCompletos)) {
                $ultimaSincronizacion = date('d/m/Y H:i:s');
                echo '<div class="alert alert-success alert-position">Todos los datos guardados correctamente</div>';
            } else {
                echo '<div class="alert alert-danger alert-position">Error al guardar los datos</div>';
            }
            break;
            
        case 'eliminar_edificio':
            $id = getSafe($_GET, 'id');
            $edificios = array_filter($edificios, function($edificio) use ($id) {
                return getSafe($edificio, 'id') !== $id;
            });
            
            $edificios = array_values($edificios);
            
            $data['edificios'] = $edificios;
            $data['ultimaActualizacion'] = date('c');
            if (guardarDatosEnJSON($json_file, $data)) {
                $ultimaSincronizacion = date('d/m/Y H:i:s');
                echo '<div class="alert alert-warning alert-position">Edificio eliminado correctamente</div>';
            } else {
                echo '<div class="alert alert-danger alert-position">Error al eliminar el edificio</div>';
            }
            break;
            
        case 'eliminar_evento':
            $id = getSafe($_GET, 'id');
            $eventos = array_filter($eventos, function($evento) use ($id) {
                return getSafe($evento, 'id') != $id;
            });
            
            $eventos = array_values($eventos);
            
            $data['eventos'] = $eventos;
            $data['ultimaActualizacion'] = date('c');
            if (guardarDatosEnJSON($json_file, $data)) {
                $ultimaSincronizacion = date('d/m/Y H:i:s');
                echo '<div class="alert alert-warning alert-position">Evento eliminado correctamente</div>';
            } else {
                echo '<div class="alert alert-danger alert-position">Error al eliminar el evento</div>';
            }
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Mapa y Calendario</title>
    
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
  :root {
    --txt: #2e2e2e;
    --primary: #19a473;
    --primary-dark: #148a60;
    --danger: #dc3545;
    --danger-dark: #c82333;
  }

  body {
    margin: 0;
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    color: var(--txt);
    background-color: #f8f9fa;
    overflow-x: hidden;
  }

  .contenedor-principal {
    max-width: 1400px;
    margin: 20px auto;
    padding: 15px;
  }

  h1, h2, h3, h4 {
    font-weight: 700;
    color: #3b3b3b;
    margin-bottom: 15px;
  }

  h1 {
    font-size: 1.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: center;
    border-bottom: 3px solid var(--primary);
    padding-bottom: 10px;
    margin-top: 10px;
  }

  /* ==== RESPONSIVE GENERAL ==== */
  @media (max-width: 768px) {
    .contenedor-principal {
      padding: 12px;
      margin: 10px auto;
    }
    
    h1 {
      font-size: 1.5rem;
      margin-bottom: 15px;
    }
    
    h2 {
      font-size: 1.3rem;
    }
    
    h3 {
      font-size: 1.2rem;
    }
    
    h4 {
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
      padding-bottom: 8px;
    }
    
    h2 {
      font-size: 1.2rem;
      margin-bottom: 12px;
    }
    
    h3 {
      font-size: 1.1rem;
    }
    
    h4 {
      font-size: 1rem;
    }
  }

  /* ==== NAV TABS ==== */
  .nav-tabs {
    flex-wrap: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 20px;
    padding-bottom: 1px;
  }

  .nav-tabs::-webkit-scrollbar {
    display: none;
  }

  .nav-tabs .nav-item {
    flex-shrink: 0;
    margin-right: 5px;
  }

  .nav-tabs .nav-link {
    font-size: 0.9rem;
    padding: 8px 12px;
    border-radius: 8px 8px 0 0;
    white-space: nowrap;
  }

  .nav-tabs .nav-link.active {
    background-color: var(--primary);
    color: white;
    border-color: var(--primary);
  }

  @media (max-width: 768px) {
    .nav-tabs .nav-link {
      font-size: 0.85rem;
      padding: 6px 10px;
    }
    
    .nav-tabs .nav-link i {
      margin-right: 3px;
    }
  }

  @media (max-width: 480px) {
    .nav-tabs .nav-link {
      font-size: 0.8rem;
      padding: 5px 8px;
    }
    
    .nav-tabs .nav-link i {
      display: block;
      margin: 0 auto 3px;
      font-size: 0.9rem;
    }
    
    .nav-tabs .nav-link span {
      display: block;
      text-align: center;
    }
  }

  /* ==== SECCIONES ADMIN ==== */
  .seccion-admin {
    background-color: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 25px;
  }

  @media (max-width: 768px) {
    .seccion-admin {
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
    }
  }

  @media (max-width: 480px) {
    .seccion-admin {
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 15px;
    }
  }

  /* ==== FORMULARIOS ==== */
  .formulario-edificio {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 2px dashed #dee2e6;
  }

  @media (max-width: 768px) {
    .formulario-edificio {
      padding: 12px;
    }
  }

  @media (max-width: 480px) {
    .formulario-edificio {
      padding: 10px;
    }
    
    .form-label {
      font-size: 0.9rem;
      margin-bottom: 5px;
      font-weight: 600;
    }
    
    .form-control, .form-select {
      font-size: 16px; /* Importante para evitar zoom en iOS */
      padding: 12px 15px;
      border-radius: 8px;
      border: 2px solid #dee2e6;
    }
    
    .form-control-color {
      height: 48px;
      width: 48px;
      padding: 3px;
      border-radius: 8px;
    }
  }

  /* ==== SERVICIOS Y ÁREAS - CORREGIDO ==== */
  .servicios-input-container {
    margin-top: 15px;
  }

  .servicio-input {
    background: #fff;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 15px;
    position: relative;
    border-left: 5px solid var(--primary);
  }

  .servicio-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px dashed #f0f0f0;
  }

  .servicio-info {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .servicio-numero {
    background-color: var(--primary);
    color: white;
    min-width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    font-weight: bold;
    flex-shrink: 0;
  }

  .servicio-titulo-label {
    font-weight: 600;
    color: #333;
    font-size: 1rem;
  }

  /* CAMPOS DE TEXTO - MÁS GRANDES Y CLAROS */
  .servicio-input .form-control {
    font-size: 16px !important; /* Fijo para evitar zoom */
    padding: 16px !important;
    border: 2px solid #e9ecef !important;
    border-radius: 10px !important;
    margin-bottom: 12px !important;
    min-height: 56px !important;
    width: 100% !important;
    background-color: #fff !important;
  }

  .servicio-input .form-control:focus {
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 3px rgba(25, 164, 115, 0.2) !important;
    outline: none !important;
  }

  .servicio-input textarea.form-control {
    min-height: 100px !important;
    resize: vertical;
  }

  /* BOTÓN DE ELIMINAR - UNO SOLO Y GRANDE */
  .btn-eliminar-servicio {
    background-color: var(--danger);
    border: none;
    color: white;
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    transition: all 0.2s ease;
    flex-shrink: 0;
    font-size: 1.1rem;
  }

  .btn-eliminar-servicio:hover {
    background-color: var(--danger-dark);
    transform: scale(1.05);
  }

  .btn-agregar-servicio {
    background-color: var(--primary);
    border: none;
    color: white;
    font-weight: 600;
    width: 100%;
    padding: 18px;
    font-size: 1.1rem;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.2s ease;
    margin-top: 15px;
    min-height: 60px;
  }

  .btn-agregar-servicio:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(25, 164, 115, 0.3);
  }

  /* RESPONSIVE ESPECÍFICO PARA SERVICIOS */
  @media (max-width: 768px) {
    .servicio-input {
      padding: 12px;
      border-radius: 10px;
    }

    .servicio-input .form-control {
      font-size: 16px !important;
      padding: 18px !important;
      min-height: 60px !important;
    }

    .servicio-input textarea.form-control {
      min-height: 120px !important;
    }

    .servicio-numero {
      min-width: 36px;
      height: 36px;
      font-size: 1.1rem;
    }

    .btn-eliminar-servicio {
      width: 48px;
      height: 48px;
      font-size: 1.2rem;
    }

    /* OCULTAR EL SEGUNDO BOTÓN DE ELIMINAR */
    .servicio-input .mt-2,
    .servicio-input .text-end,
    .servicio-input .btn-sm {
      display: none !important;
    }
  }

  @media (max-width: 480px) {
    .servicio-input {
      padding: 10px;
      border-left-width: 4px;
    }

    .servicio-header {
      flex-direction: row !important; /* Mantener en fila */
      align-items: center !important;
      margin-bottom: 12px;
    }

    .servicio-input .form-control {
      font-size: 16px !important;
      padding: 20px !important;
      min-height: 64px !important;
      border-radius: 12px !important;
    }

    .servicio-input textarea.form-control {
      min-height: 140px !important;
    }

    .servicio-numero {
      min-width: 40px;
      height: 40px;
      font-size: 1.2rem;
    }

    .servicio-titulo-label {
      font-size: 1.1rem;
    }

    .btn-eliminar-servicio {
      width: 44px;
      height: 44px;
    }

    .btn-agregar-servicio {
      padding: 20px;
      font-size: 1.2rem;
      min-height: 64px;
      border-radius: 14px;
    }
  }

  /* ESTILOS ESPECÍFICOS PARA ETIQUETAS DE SERVICIOS */
  .servicio-input .input-label {
    display: block;
    font-weight: 600;
    color: #555;
    margin-bottom: 6px;
    font-size: 0.9rem;
  }

  @media (max-width: 480px) {
    .servicio-input .input-label {
      font-size: 1rem;
      margin-bottom: 8px;
    }
  }

  /* ==== CALENDARIO ==== */
  #calendar {
    background-color: #fff;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-top: 15px;
    min-height: 400px;
  }

  @media (max-width: 768px) {
    #calendar {
      padding: 12px;
      min-height: 350px;
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
    
    .fc-view-harness {
      min-height: 300px !important;
    }
  }

  @media (max-width: 480px) {
    #calendar {
      padding: 10px;
      min-height: 300px;
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
  }

  /* ==== BOTONES GENERALES ==== */
  .btn-admin {
    background-color: var(--primary);
    border-color: var(--primary);
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 8px 16px;
  }

  .btn-admin:hover {
    background-color: var(--primary-dark);
    border-color: var(--primary-dark);
  }

  .btn-danger-admin {
    background-color: var(--danger);
    border-color: var(--danger);
  }

  @media (max-width: 768px) {
    .btn-admin, .btn {
      font-size: 0.85rem;
      padding: 6px 12px;
    }
  }

  @media (max-width: 480px) {
    .btn-admin, .btn {
      font-size: 0.8rem;
      padding: 5px 10px;
      min-height: 44px;
    }
    
    .btn i {
      margin-right: 3px;
    }
  }

  /* ==== LISTAS ==== */
  .lista-edificios {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 5px;
  }

  .lista-edificios::-webkit-scrollbar {
    width: 6px;
  }

  .lista-edificios::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
  }

  .lista-edificios::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
  }

  @media (max-width: 768px) {
    .lista-edificios {
      max-height: 300px;
    }
  }

  .edificio-item {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 8px;
    transition: all 0.2s ease;
  }

  .edificio-item:hover {
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }

  .color-preview {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 8px;
    border: 2px solid #ddd;
    flex-shrink: 0;
  }

  .servicio-item {
    background: #e9ecef;
    padding: 6px 10px;
    margin: 4px 0;
    border-radius: 5px;
    border-left: 4px solid var(--primary);
    font-size: 0.9rem;
  }

  /* ==== EVENTOS ==== */
  .fc-event {
    cursor: pointer;
  }
  
  .evento-item {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 8px;
    border-left: 4px solid var(--primary);
  }
  
  .evento-color {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 6px;
    flex-shrink: 0;
  }

  /* ==== MODAL ==== */
  .modal-header {
    background-color: var(--primary);
    color: white;
    padding: 12px 15px;
  }

  .modal-title {
    font-size: 1.1rem;
    margin-bottom: 0;
  }

  @media (max-width: 768px) {
    .modal-dialog {
      margin: 10px;
      max-width: calc(100% - 20px);
    }
    
    .modal-header {
      padding: 10px 12px;
    }
    
    .modal-body, .modal-footer {
      padding: 12px;
    }
  }

  /* ==== ALERTS Y NOTIFICACIONES ==== */
  .alert-position {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    max-width: 300px;
    font-size: 0.9rem;
  }

  @media (max-width: 768px) {
    .alert-position {
      top: 10px;
      right: 10px;
      left: 10px;
      max-width: none;
      font-size: 0.85rem;
    }
  }

  .sync-status {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: var(--primary);
    color: white;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    max-width: 300px;
    word-wrap: break-word;
  }

  /* ==== MEJORAS TÁCTILES ==== */
  .btn, .fc-button, .nav-link, .form-control-color {
    min-height: 40px;
    touch-action: manipulation;
  }

  @media (max-width: 480px) {
    .btn, .fc-button, .nav-link, .form-control-color {
      min-height: 44px;
    }
  }

  /* ==== REORGANIZACIÓN EN MÓVIL ==== */
  @media (max-width: 768px) {
    .row > div {
      margin-bottom: 15px;
    }
    
    .row .col-md-6, .row .col-md-4, .row .col-md-8 {
      width: 100%;
    }
  }

  /* ==== TARJETAS ==== */
  .card {
    margin-bottom: 15px;
  }

  .card-header {
    padding: 10px 15px;
    font-size: 0.95rem;
  }

  .card-body {
    padding: 15px;
  }

  /* ==== MENSAJES SIN DATOS ==== */
  .text-center.text-muted {
    padding: 20px;
  }

  @media (max-width: 480px) {
    .text-center.text-muted {
      padding: 15px;
    }
  }

  /* ==== GRID ==== */
  .row {
    margin-left: -8px;
    margin-right: -8px;
  }

  .row > * {
    padding-left: 8px;
    padding-right: 8px;
  }

  /* ==== VALIDACIÓN ==== */
  .is-invalid {
    border-color: var(--danger) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
  }
  
  .is-invalid:focus {
    border-color: var(--danger);
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
  }
</style>
</head>
<body>

<div class="contenedor-principal">
  <h1>Panel de Administración - Mapa y Calendario</h1>

  <ul class="nav nav-tabs" id="adminTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="edificios-tab" data-bs-toggle="tab" data-bs-target="#edificios" type="button" role="tab">
        <i class="fas fa-building me-2"></i><span>Edificios</span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="calendario-tab" data-bs-toggle="tab" data-bs-target="#calendario" type="button" role="tab">
        <i class="fas fa-calendar me-2"></i><span>Calendario</span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="sincronizacion-tab" data-bs-toggle="tab" data-bs-target="#sincronizacion" type="button" role="tab">
        <i class="fas fa-sync me-2"></i><span>Sincronización</span>
      </button>
    </li>
  </ul>

  <div class="tab-content" id="adminTabsContent">
    
    <!-- Pestaña Edificios -->
    <div class="tab-pane fade show active" id="edificios" role="tabpanel">
      <div class="seccion-admin">
        <h2><i class="fas fa-building me-2"></i>Gestión de Edificios</h2>
        
        <div class="row">
          <div class="col-md-6">
            <div class="formulario-edificio">
              <h4 id="form-title"><i class="fas fa-plus me-2"></i>Agregar Nuevo Edificio</h4>
              <form id="form-edificio" action="" method="POST">
                <input type="hidden" id="edificio-id" name="edificio-id">
                <input type="hidden" name="action" value="guardar_edificio">
                
                <div class="mb-3">
                  <label class="form-label">Nombre del Edificio *</label>
                  <input type="text" class="form-control" id="nombre-edificio" name="nombre-edificio" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">ID del Edificio *</label>
                  <input type="text" class="form-control" id="id-edificio" name="id-edificio" required placeholder="Ej: edificioA, edificioB">
                  <div class="form-text">Este ID se usará para enlazar con el mapa</div>
                </div>

                <div class="mb-3">
                  <label class="form-label">Descripción</label>
                  <textarea class="form-control" id="descripcion-edificio" name="descripcion-edificio" rows="2" placeholder="Breve descripción del edificio..."></textarea>
                </div>

                <div class="mb-3">
                  <label class="form-label">Color identificador *</label>
                  <input type="color" class="form-control form-control-color" id="color-edificio" name="color-edificio" value="#19a473" title="Elige un color">
                </div>

                <div class="mb-3">
  <label class="form-label">Servicios y Áreas *</label>
  <div class="servicios-input-container" id="servicios-container">
    <!-- Los servicios se agregarán aquí dinámicamente -->
  </div>
  <button type="button" class="btn-agregar-servicio mt-3" onclick="agregarServicio()">
    <i class="fas fa-plus-circle"></i>
    <span>Agregar Servicio</span>
  </button>
  <div class="form-text mt-2">
    <small><i class="fas fa-info-circle me-1"></i>Agrega los servicios y áreas disponibles en este edificio. Completa tanto el título como la descripción.</small>
  </div>
</div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                  <button type="submit" class="btn btn-admin">
                    <i class="fas fa-save me-1"></i>Guardar Edificio
                  </button>
                  <button type="button" class="btn btn-secondary" onclick="resetFormEdificio()">
                    <i class="fas fa-times me-1"></i>Cancelar
                  </button>
                </div>
              </form>
            </div>
          </div>

          <div class="col-md-6">
            <h4><i class="fas fa-list me-2"></i>Edificios Existentes</h4>
            <div class="d-flex justify-content-between mb-3 flex-wrap">
                <a href="?action=cargar_todo" class="btn btn-sm btn-outline-primary mb-2 mb-md-0">
                    <i class="fas fa-sync me-1"></i>Recargar desde JSON
                </a>
                <a href="?action=guardar_todo" class="btn btn-sm btn-outline-success mb-2 mb-md-0">
                    <i class="fas fa-save me-1"></i>Guardar en JSON
                </a>
            </div>
            <div class="lista-edificios" id="lista-edificios">
                <?php if (empty($edificios)): ?>
                    <div class="text-center text-muted p-4">
                        <i class="fas fa-building fa-3x mb-3"></i>
                        <p>No hay edificios registrados</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($edificios as $edificio): ?>
                    <div class="edificio-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h5 class="d-flex align-items-center">
                                    <span class="color-preview" style="background-color: <?= htmlspecialchars($edificio['color'] ?? '#19a473') ?>"></span>
                                    <span class="edificio-nombre"><?= htmlspecialchars($edificio['nombre'] ?? 'Sin nombre') ?></span>
                                </h5>
                                <p class="text-muted mb-2 small"><?= htmlspecialchars($edificio['descripcion'] ?? 'Sin descripción') ?></p>
                                <div class="servicios-lista">
                                    <?php if (isset($edificio['descripciones']) && is_array($edificio['descripciones'])): ?>
                                        <?php foreach ($edificio['descripciones'] as $desc): ?>
                                            <div class="servicio-item">
                                                <strong><?= htmlspecialchars($desc['titulo'] ?? '') ?>:</strong> <?= htmlspecialchars($desc['contenido'] ?? '') ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="btn-group ms-2 flex-shrink-0">
                                <button class="btn btn-sm btn-outline-primary" onclick="editarEdificio('<?= htmlspecialchars($edificio['id'] ?? '') ?>')" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?action=eliminar_edificio&id=<?= htmlspecialchars($edificio['id'] ?? '') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este edificio?')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pestaña Calendario -->
    <div class="tab-pane fade" id="calendario" role="tabpanel">
      <div class="seccion-admin">
        <h2><i class="fas fa-calendar me-2"></i>Gestión de Calendario</h2>
        
        <div class="row mb-4">
          <div class="col-12">
            <div class="d-flex flex-wrap gap-2 mb-3">
              <button class="btn btn-admin" data-bs-toggle="modal" data-bs-target="#modalEvento" onclick="nuevoEvento()">
                <i class="fas fa-plus me-1"></i>Agregar Nuevo Evento
              </button>
              <a href="?action=guardar_todo" class="btn btn-outline-success">
                <i class="fas fa-save me-1"></i>Guardar en JSON
              </a>
              <a href="?action=cargar_todo" class="btn btn-outline-primary">
                <i class="fas fa-sync me-1"></i>Recargar desde JSON
              </a>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <h4>Lista de Eventos</h4>
            <div class="lista-edificios" id="lista-eventos">
              <?php if (empty($eventos)): ?>
                <div class="text-center text-muted p-4">
                  <i class="fas fa-calendar-day fa-3x mb-3"></i>
                  <p>No hay eventos registrados</p>
                </div>
              <?php else: ?>
                <?php foreach ($eventos as $evento): ?>
                <div class="evento-item">
                  <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                      <h6 class="d-flex align-items-center">
                        <span class="evento-color" style="background-color: <?= htmlspecialchars($evento['backgroundColor'] ?? '#19a473') ?>"></span>
                        <span class="evento-titulo"><?= htmlspecialchars($evento['title'] ?? 'Sin título') ?></span>
                      </h6>
                      <p class="text-muted mb-1 small">
                        <i class="fas fa-clock me-1"></i>
                        <?= date('d/m/Y', strtotime($evento['start'])) ?>
                        <?php if (!empty($evento['end'])): ?>
                          - <?= date('d/m/Y', strtotime($evento['end'])) ?>
                        <?php endif; ?>
                      </p>
                      <?php if (!empty($evento['extendedProps']['description'])): ?>
                        <p class="mb-1 small"><?= htmlspecialchars(substr($evento['extendedProps']['description'], 0, 100)) ?><?= strlen($evento['extendedProps']['description']) > 100 ? '...' : '' ?></p>
                      <?php elseif (!empty($evento['description'])): ?>
                        <p class="mb-1 small"><?= htmlspecialchars(substr($evento['description'], 0, 100)) ?><?= strlen($evento['description']) > 100 ? '...' : '' ?></p>
                      <?php endif; ?>
                      <span class="badge bg-secondary"><?= htmlspecialchars($evento['extendedProps']['tipo'] ?? $evento['tipo'] ?? 'academico') ?></span>
                    </div>
                    <div class="btn-group ms-2 flex-shrink-0">
                      <button class="btn btn-sm btn-outline-primary" onclick="editarEventoDesdeLista(<?= htmlspecialchars($evento['id'] ?? '0') ?>)" title="Editar">
                        <i class="fas fa-edit"></i>
                      </button>
                      <a href="?action=eliminar_evento&id=<?= htmlspecialchars($evento['id'] ?? '') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este evento?')" title="Eliminar">
                        <i class="fas fa-trash"></i>
                      </a>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-md-8">
            <div id="calendar"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pestaña Sincronización -->
    <div class="tab-pane fade" id="sincronizacion" role="tabpanel">
      <div class="seccion-admin">
        <h2><i class="fas fa-sync me-2"></i>Sincronización con JSON</h2>
        
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="card h-100">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-download me-2"></i>Cargar desde JSON</h5>
              </div>
              <div class="card-body d-flex flex-column">
                <p class="flex-grow-1">Carga todos los datos desde el archivo mapa.json para trabajar con la información actual.</p>
                <a href="?action=cargar_todo" class="btn btn-primary mt-auto">
                  <i class="fas fa-sync me-2"></i>Cargar Todos los Datos
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="card h-100">
              <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-save me-2"></i>Guardar en JSON</h5>
              </div>
              <div class="card-body d-flex flex-column">
                <p class="flex-grow-1">Guarda todos los cambios realizados en el archivo mapa.json para que se reflejen en el sitio principal.</p>
                <a href="?action=guardar_todo" class="btn btn-success mt-auto">
                  <i class="fas fa-save me-2"></i>Guardar Todos los Datos
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Estado de Sincronización</h5>
              </div>
              <div class="card-body">
                <div id="estado-sincronizacion">
                  <?php if (isset($ultimaSincronizacion)): ?>
                    <p class="mb-2"><i class="fas fa-circle text-success me-2"></i><strong>Estado:</strong> Sincronizado</p>
                    <p class="mb-0"><i class="fas fa-clock me-2"></i><strong>Última sincronización:</strong> <?= $ultimaSincronizacion ?></p>
                  <?php else: ?>
                    <p class="mb-2"><i class="fas fa-circle text-warning me-2"></i><strong>Estado:</strong> Sin sincronizar</p>
                    <p class="mb-0"><i class="fas fa-clock me-2"></i><strong>Última sincronización:</strong> Nunca</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal para eventos del calendario -->
<div class="modal fade" id="modalEvento" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEventoTitle">Agregar Nuevo Evento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="form-evento" action="" method="POST">
          <input type="hidden" id="evento-id" name="evento-id">
          <input type="hidden" name="action" value="guardar_evento">
          
          <div class="mb-3">
            <label class="form-label">Título del Evento *</label>
            <input type="text" class="form-control" id="titulo-evento" name="titulo-evento" required>
          </div>

          <div class="row g-2">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Fecha de Inicio *</label>
                <input type="date" class="form-control" id="inicio-evento" name="inicio-evento" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Fecha de Fin</label>
                <input type="date" class="form-control" id="fin-evento" name="fin-evento">
                <div class="form-text small">Dejar vacío para evento de un solo día</div>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion-evento" name="descripcion-evento" rows="3" placeholder="Descripción detallada del evento..."></textarea>
          </div>

          <div class="row g-2">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Color del Evento</label>
                <input type="color" class="form-control form-control-color" id="color-evento" name="color-evento" value="#19a473">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Tipo de Evento</label>
                <select class="form-select" id="tipo-evento" name="tipo-evento">
                  <option value="academico">Académico</option>
                  <option value="deportivo">Deportivo</option>
                  <option value="cultural">Cultural</option>
                  <option value="administrativo">Administrativo</option>
                  <option value="urgente">Urgente</option>
                  <option value="otros">Otros</option>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="d-flex flex-wrap w-100 gap-2">
          <button type="button" class="btn btn-secondary flex-grow-1" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" form="form-evento" class="btn btn-admin flex-grow-1">
            <i class="fas fa-save me-1"></i>Guardar Evento
          </button>
          <button type="button" class="btn btn-danger-admin flex-grow-1" id="btn-eliminar-evento" style="display: none;" onclick="eliminarEvento()">
            <i class="fas fa-trash me-1"></i>Eliminar Evento
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Variables globales
let servicioCounter = 0;
let calendar;
let eventoActual = null;

// Detectar dispositivo
const isMobile = window.innerWidth <= 768;
const isSmallMobile = window.innerWidth <= 480;

// Gestión de edificios
// Función mejorada para agregar servicios
function agregarServicio() {
  servicioCounter++;
  
  const servicioHTML = `
    <div class="servicio-input" id="servicio-${servicioCounter}">
      <div class="servicio-header">
        <div class="servicio-info">
          <span class="servicio-numero">${servicioCounter}</span>
          <span class="servicio-titulo-label">Servicio ${servicioCounter}</span>
        </div>
        <button type="button" class="btn-eliminar-servicio" onclick="eliminarServicio(${servicioCounter})" title="Eliminar servicio">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <div class="mb-3">
        <label class="input-label">Título del servicio *</label>
        <input type="text" 
               class="form-control servicio-titulo" 
               name="servicio-titulo-${servicioCounter}" 
               placeholder="Ej: Biblioteca, Laboratorio, Oficina..." 
               required
               autocomplete="off">
      </div>
      
      <div class="mb-3">
        <label class="input-label">Descripción *</label>
        <textarea class="form-control servicio-descripcion" 
                  name="servicio-descripcion-${servicioCounter}" 
                  rows="3" 
                  placeholder="Descripción detallada del servicio o área..."
                  required></textarea>
      </div>
    </div>
  `;
  
  const container = document.getElementById('servicios-container');
  container.insertAdjacentHTML('beforeend', servicioHTML);
  
  // Scroll suave al nuevo servicio
  setTimeout(() => {
    const nuevoServicio = document.getElementById(`servicio-${servicioCounter}`);
    if (nuevoServicio) {
      nuevoServicio.scrollIntoView({ 
        behavior: 'smooth', 
        block: 'center' 
      });
    }
  }, 100);
}


function eliminarServicio(id) {
  const elemento = document.getElementById(`servicio-${id}`);
  if (elemento) elemento.remove();
}

function validarFormularioEdificio() {
  const id = document.getElementById('id-edificio').value.trim();
  const nombre = document.getElementById('nombre-edificio').value.trim();
  
  if (!id || !nombre) {
    mostrarNotificacion('ID y Nombre son obligatorios', 'warning');
    return false;
  }

  let serviciosCompletos = 0;
  document.querySelectorAll('.servicio-input').forEach(servicioDiv => {
    const tituloInput = servicioDiv.querySelector('input[name^="servicio-titulo"]');
    const descripcionInput = servicioDiv.querySelector('input[name^="servicio-descripcion"]');
    
    if (tituloInput && descripcionInput && 
        tituloInput.value.trim() && descripcionInput.value.trim()) {
      serviciosCompletos++;
    }
  });

  if (serviciosCompletos === 0) {
    mostrarNotificacion('Debe agregar al menos un servicio completo', 'warning');
    return false;
  }

  return true;
}

function editarEdificio(id) {
  const edificios = <?= json_encode($edificios) ?>;
  const edificio = edificios.find(e => e.id === id);
  
  if (edificio) {
    document.getElementById('edificio-id').value = edificio.id;
    document.getElementById('id-edificio').value = edificio.id;
    document.getElementById('nombre-edificio').value = edificio.nombre;
    document.getElementById('descripcion-edificio').value = edificio.descripcion || '';
    document.getElementById('color-edificio').value = edificio.color;
    
    document.getElementById('servicios-container').innerHTML = '';
    servicioCounter = 0;
    
    if (edificio.descripciones) {
      edificio.descripciones.forEach(descripcion => {
        servicioCounter++;
        const servicioHTML = `
          <div class="servicio-input mb-2" id="servicio-${servicioCounter}">
            <div class="input-group ${isSmallMobile ? 'flex-column' : ''}">
              <input type="text" class="form-control ${isSmallMobile ? 'mb-2' : ''}" placeholder="Título del servicio" name="servicio-titulo-${servicioCounter}" value="${descripcion.titulo}">
              <input type="text" class="form-control ${isSmallMobile ? 'mb-2' : ''}" placeholder="Descripción" name="servicio-descripcion-${servicioCounter}" value="${descripcion.contenido}">
              <button type="button" class="btn btn-outline-danger ${isSmallMobile ? 'w-100' : ''}" onclick="eliminarServicio(${servicioCounter})">
                <i class="fas fa-times"></i> ${isSmallMobile ? 'Eliminar' : ''}
              </button>
            </div>
          </div>
        `;
        document.getElementById('servicios-container').innerHTML += servicioHTML;
      });
    }
    
    document.getElementById('form-title').innerHTML = '<i class="fas fa-edit me-2"></i>Editar Edificio';
    document.getElementById('nombre-edificio').focus();
    
    // Scroll suave al formulario en móvil
    if (isMobile) {
      const form = document.querySelector('.formulario-edificio');
      form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }
}

function resetFormEdificio() {
  document.getElementById('form-edificio').reset();
  document.getElementById('edificio-id').value = '';
  document.getElementById('servicios-container').innerHTML = '';
  document.getElementById('form-title').innerHTML = '<i class="fas fa-plus me-2"></i>Agregar Nuevo Edificio';
  servicioCounter = 0;
  agregarServicio();
}

// Gestión de calendario
function inicializarCalendario() {
  const calendarEl = document.getElementById('calendar');
  if (!calendarEl) return;
  
  const eventos = <?= json_encode($eventos) ?>;
  
  // Configurar FullCalendar según dispositivo
  const calendarConfig = {
    initialView: isMobile ? "dayGridMonth" : "dayGridMonth",
    locale: 'es',
    firstDay: 1,
    headerToolbar: {
      left: isSmallMobile ? "prev,next" : "prev,next today",
      center: "title",
      right: isSmallMobile ? "" : (isMobile ? "dayGridMonth,timeGridWeek" : "dayGridMonth,timeGridWeek,timeGridDay")
    },
    events: eventos,
    eventClick: function(info) {
      abrirModalEvento(info.event);
    },
    dateClick: function(info) {
      nuevoEvento(info.dateStr);
    },
    height: isMobile ? 400 : 500
  };

  calendar = new FullCalendar.Calendar(calendarEl, calendarConfig);
  calendar.render();
}

function abrirModalEvento(event) {
  eventoActual = event;
  const modal = new bootstrap.Modal(document.getElementById('modalEvento'));
  
  document.getElementById('modalEventoTitle').textContent = 'Editar Evento';
  document.getElementById('evento-id').value = event.id;
  document.getElementById('titulo-evento').value = event.title;
  document.getElementById('descripcion-evento').value = event.extendedProps?.description || '';
  document.getElementById('color-evento').value = event.backgroundColor || '#19a473';
  document.getElementById('tipo-evento').value = event.extendedProps?.tipo || 'academico';
  
  // Formatear fechas para el input date
  const inicio = event.start ? new Date(event.start) : new Date();
  const fin = event.end ? new Date(event.end) : null;
  
  document.getElementById('inicio-evento').value = inicio.toISOString().split('T')[0];
  document.getElementById('fin-evento').value = fin ? fin.toISOString().split('T')[0] : '';
  
  document.getElementById('btn-eliminar-evento').style.display = 'block';
  modal.show();
}

function nuevoEvento(fecha = null) {
  eventoActual = null;
  const modal = new bootstrap.Modal(document.getElementById('modalEvento'));
  
  document.getElementById('modalEventoTitle').textContent = 'Agregar Nuevo Evento';
  document.getElementById('form-evento').reset();
  document.getElementById('evento-id').value = '';
  
  // Establecer fecha por defecto
  const fechaInicio = fecha ? new Date(fecha) : new Date();
  const hoy = new Date();
  const fechaFormateada = hoy.toISOString().split('T')[0];
  
  document.getElementById('inicio-evento').value = fechaFormateada;
  document.getElementById('fin-evento').value = '';
  document.getElementById('color-evento').value = '#19a473';
  document.getElementById('tipo-evento').value = 'academico';
  
  document.getElementById('btn-eliminar-evento').style.display = 'none';
  modal.show();
}

function editarEventoDesdeLista(id) {
  const eventos = <?= json_encode($eventos) ?>;
  const evento = eventos.find(e => e.id == id);
  
  if (evento) {
    const modal = new bootstrap.Modal(document.getElementById('modalEvento'));
    
    document.getElementById('modalEventoTitle').textContent = 'Editar Evento';
    document.getElementById('evento-id').value = evento.id;
    document.getElementById('titulo-evento').value = evento.title;
    document.getElementById('descripcion-evento').value = evento.extendedProps?.description || evento.description || '';
    document.getElementById('color-evento').value = evento.backgroundColor || '#19a473';
    document.getElementById('tipo-evento').value = evento.extendedProps?.tipo || evento.tipo || 'academico';
    
    // Formatear fechas para el input date
    const inicio = new Date(evento.start);
    const fin = evento.end ? new Date(evento.end) : null;
    
    document.getElementById('inicio-evento').value = inicio.toISOString().split('T')[0];
    document.getElementById('fin-evento').value = fin ? fin.toISOString().split('T')[0] : '';
    
    document.getElementById('btn-eliminar-evento').style.display = 'block';
    modal.show();
  }
}

function eliminarEvento() {
  if (confirm('¿Estás seguro de que quieres eliminar este evento?')) {
    const id = document.getElementById('evento-id').value;
    window.location.href = `?action=eliminar_evento&id=${id}`;
  }
}

// Validar formulario de evento
document.getElementById('form-evento').addEventListener('submit', function(e) {
  const inicio = document.getElementById('inicio-evento').value;
  const fin = document.getElementById('fin-evento').value;
  
  if (!inicio) {
    e.preventDefault();
    mostrarNotificacion('La fecha de inicio es obligatoria', 'warning');
    return false;
  }
  
  if (fin && new Date(fin) < new Date(inicio)) {
    e.preventDefault();
    mostrarNotificacion('La fecha de fin no puede ser anterior a la fecha de inicio', 'warning');
    return false;
  }
  
  return true;
});

// Utilidades
function mostrarNotificacion(mensaje, tipo = 'info') {
  const alertasAnteriores = document.querySelectorAll('.alert-position');
  alertasAnteriores.forEach(alerta => alerta.remove());

  const alertClass = {
    'success': 'alert-success',
    'warning': 'alert-warning',
    'error': 'alert-danger',
    'info': 'alert-info'
  }[tipo] || 'alert-info';

  const iconClass = {
    'success': 'fa-check-circle',
    'warning': 'fa-exclamation-triangle',
    'error': 'fa-times-circle',
    'info': 'fa-info-circle'
  }[tipo] || 'fa-info-circle';

  const alertHTML = `
    <div class="alert ${alertClass} alert-dismissible fade show alert-position" role="alert">
      <i class="fas ${iconClass} me-2"></i>
      <strong>${mensaje}</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  `;
  
  document.body.insertAdjacentHTML('beforeend', alertHTML);
  
  setTimeout(() => {
    const alert = document.querySelector('.alert-position');
    if (alert) alert.remove();
  }, 4000);
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
  // Validar formulario de edificio
  document.getElementById('form-edificio').addEventListener('submit', function(e) {
    if (!validarFormularioEdificio()) {
      e.preventDefault();
    }
  });
  
  // Agregar primer servicio por defecto
  agregarServicio();
  
  // Inicializar calendario
  inicializarCalendario();
  
  // Establecer fecha mínima en los inputs de fecha
  const hoy = new Date();
  const fechaMinima = hoy.toISOString().split('T')[0];
  const inicioInput = document.getElementById('inicio-evento');
  const finInput = document.getElementById('fin-evento');
  
  if (inicioInput) inicioInput.min = fechaMinima;
  if (finInput) finInput.min = fechaMinima;
  
  // Añadir estado de sincronización
  const syncStatus = document.createElement('div');
  syncStatus.className = 'sync-status';
  syncStatus.innerHTML = `<i class="fas fa-sync-alt me-2"></i>Última sincronización: <?= $ultimaSincronizacion ?? "Nunca" ?>`;
  document.body.appendChild(syncStatus);
  
  // Actualizar pestañas activas
  const tabs = document.querySelectorAll('#adminTabs .nav-link');
  tabs.forEach(tab => {
    tab.addEventListener('shown.bs.tab', function(event) {
      if (event.target.id === 'calendario-tab') {
        // Refrescar calendario al cambiar a la pestaña
        setTimeout(() => {
          if (calendar) {
            calendar.updateSize();
          }
        }, 100);
      }
    });
  });
  
  // Ajustar comportamiento en móvil
  if (isMobile) {
    // Hacer botones más táctiles
    document.querySelectorAll('.btn, .nav-link').forEach(element => {
      element.style.touchAction = 'manipulation';
    });
    
    // Ajustar scroll suave
    document.documentElement.style.scrollBehavior = 'smooth';
    
    // Prevenir zoom en inputs
    document.addEventListener('touchmove', function(e) {
      if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
        e.preventDefault();
      }
    }, { passive: false });
  }
  
  // Ajustar tamaño del calendario en redimensionamiento
  window.addEventListener('resize', function() {
    if (calendar) {
      calendar.updateSize();
    }
  });
});
</script>
<?php include "../../includes/footer.php"; ?>
</body>
</html>