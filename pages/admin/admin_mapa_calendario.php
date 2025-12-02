<?php
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
            
            $eventoData = [
                'id' => $id ? intval($id) : null,
                'title' => $titulo,
                'start' => $start,
                'end' => $end,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'description' => $descripcion,
                'tipo' => $tipo
            ];
            
            if (!empty($id)) {
                // Actualizar evento existente
                $eventoEncontrado = false;
                foreach ($eventos as &$evento) {
                    if (isset($evento['id']) && $evento['id'] == $id) {
                        $evento = array_merge($evento, $eventoData);
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

      /* Estilos para el calendario */
#calendar {
  background: white;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  min-height: 600px;
}

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

/* Estilos para la lista de eventos */
.lista-eventos {
  max-height: 600px;
  overflow-y: auto;
}

.evento-item {
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 10px;
  transition: all 0.3s ease;
  border-left: 4px solid #19a473;
}

.evento-item:hover {
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transform: translateY(-2px);
}

.evento-color {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  display: inline-block;
  margin-right: 8px;
}

/* Responsive */
@media (max-width: 768px) {
  #calendar {
    min-height: 400px;
  }
  
  .fc-toolbar {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .fc-toolbar-chunk {
    margin-bottom: 10px;
  }
}
      :root {
        --txt: #2e2e2e;
      }

      body {
        margin: 0;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: var(--txt);
        background-color: #f8f9fa;
      }

      .contenedor-principal {
        max-width: 1400px;
        margin: 20px auto;
        padding: 20px;
      }

      h1, h2, h3 {
        font-weight: 700;
        color: #3b3b3b;
        margin-bottom: 20px;
      }

      h1 {
        font-size: 2.2rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: center;
        border-bottom: 3px solid #19a473;
        padding-bottom: 10px;
      }

      .seccion-admin {
        background-color: #fff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-bottom: 30px;
      }

      #calendar {
        background-color: #fff;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-top: 20px;
      }

      .formulario-edificio {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        border: 2px dashed #dee2e6;
      }

      .btn-admin {
        background-color: #19a473;
        border-color: #19a473;
        color: white;
        font-weight: 600;
      }

      .btn-admin:hover {
        background-color: #148a60;
        border-color: #148a60;
      }

      .btn-danger-admin {
        background-color: #dc3545;
        border-color: #dc3545;
      }

      .lista-edificios {
        max-height: 500px;
        overflow-y: auto;
      }

      .edificio-item {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
      }

      .edificio-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      }

      .color-preview {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 10px;
        border: 2px solid #ddd;
      }

      .servicio-item {
        background: #e9ecef;
        padding: 8px 12px;
        margin: 5px 0;
        border-radius: 5px;
        border-left: 4px solid #19a473;
      }

      .modal-header {
        background-color: #19a473;
        color: white;
      }

      .nav-tabs .nav-link.active {
        background-color: #19a473;
        color: white;
        border-color: #19a473;
      }

      .tab-pane {
        padding: 20px 0;
      }

      .servicio-input .input-group {
        margin-bottom: 8px;
      }

      .alert-position {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
      }

      .sync-status {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #19a473;
        color: white;
        padding: 10px 15px;
        border-radius: 20px;
        font-size: 0.9em;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      }

      .fc-event {
        cursor: pointer;
      }
      
      .evento-item {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        border-left: 4px solid #19a473;
      }
      
      .evento-color {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
      }
    </style>
</head>
<body>

<div class="contenedor-principal">
  <h1>Panel de Administración - Mapa y Calendario</h1>

  <ul class="nav nav-tabs" id="adminTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="edificios-tab" data-bs-toggle="tab" data-bs-target="#edificios" type="button" role="tab">
        <i class="fas fa-building me-2"></i>Edificios
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="calendario-tab" data-bs-toggle="tab" data-bs-target="#calendario" type="button" role="tab">
        <i class="fas fa-calendar me-2"></i>Calendario
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="sincronizacion-tab" data-bs-toggle="tab" data-bs-target="#sincronizacion" type="button" role="tab">
        <i class="fas fa-sync me-2"></i>Sincronización
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
                  <div id="servicios-container"></div>
                  <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="agregarServicio()">
                    <i class="fas fa-plus me-1"></i>Agregar Servicio
                  </button>
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
            <div class="d-flex justify-content-between mb-3">
                <a href="?action=cargar_todo" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-sync me-1"></i>Recargar desde JSON
                </a>
                <a href="?action=guardar_todo" class="btn btn-sm btn-outline-success">
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
                                <h5>
                                    <span class="color-preview" style="background-color: <?= htmlspecialchars($edificio['color'] ?? '#19a473') ?>"></span>
                                    <?= htmlspecialchars($edificio['nombre'] ?? 'Sin nombre') ?>
                                </h5>
                                <p class="text-muted mb-2"><?= htmlspecialchars($edificio['descripcion'] ?? 'Sin descripción') ?></p>
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
                            <div class="btn-group ms-3">
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
          <div class="col-md-8">
            <button class="btn btn-admin" data-bs-toggle="modal" data-bs-target="#modalEvento">
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
                      <h6>
                        <span class="evento-color" style="background-color: <?= htmlspecialchars($evento['backgroundColor'] ?? '#19a473') ?>"></span>
                        <?= htmlspecialchars($evento['title'] ?? 'Sin título') ?>
                      </h6>
                      <p class="text-muted mb-1 small">
                        <i class="fas fa-clock me-1"></i>
                        <?= date('d/m/Y', strtotime($evento['start'])) ?>
                        <?php if (!empty($evento['end'])): ?>
                          - <?= date('d/m/Y', strtotime($evento['end'])) ?>
                        <?php endif; ?>
                      </p>
                      <?php if (!empty($evento['description'])): ?>
                        <p class="mb-1 small"><?= htmlspecialchars($evento['description']) ?></p>
                      <?php endif; ?>
                      <span class="badge bg-secondary"><?= htmlspecialchars($evento['tipo'] ?? 'academico') ?></span>
                    </div>
                    <div class="btn-group ms-2">
                      <button class="btn btn-sm btn-outline-primary" onclick="editarEvento(<?= htmlspecialchars($evento['id'] ?? '0') ?>)" title="Editar">
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
          <div class="col-md-6">
            <div class="card">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-download me-2"></i>Cargar desde JSON</h5>
              </div>
              <div class="card-body">
                <p>Carga todos los datos desde el archivo mapa.json para trabajar con la información actual.</p>
                <a href="?action=cargar_todo" class="btn btn-primary w-100">
                  <i class="fas fa-sync me-2"></i>Cargar Todos los Datos
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-save me-2"></i>Guardar en JSON</h5>
              </div>
              <div class="card-body">
                <p>Guarda todos los cambios realizados en el archivo mapa.json para que se reflejen en el sitio principal.</p>
                <a href="?action=guardar_todo" class="btn btn-success w-100">
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
                    <p><i class="fas fa-circle text-success me-2"></i>Estado: Sincronizado</p>
                    <p><i class="fas fa-clock me-2"></i>Última sincronización: <?= $ultimaSincronizacion ?></p>
                  <?php else: ?>
                    <p><i class="fas fa-circle text-warning me-2"></i>Estado: Sin sincronizar</p>
                    <p><i class="fas fa-clock me-2"></i>Última sincronización: Nunca</p>
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
  <div class="modal-dialog">
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

          <div class="row">
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
                <div class="form-text">Dejar vacío para evento de un solo día</div>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion-evento" name="descripcion-evento" rows="3" placeholder="Descripción detallada del evento..."></textarea>
          </div>

          <div class="row">
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="form-evento" class="btn btn-admin">
          <i class="fas fa-save me-1"></i>Guardar Evento
        </button>
        <button type="button" class="btn btn-danger-admin" id="btn-eliminar-evento" style="display: none;" onclick="eliminarEvento()">
          <i class="fas fa-trash me-1"></i>Eliminar Evento
        </button>
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

// Gestión de edificios
function agregarServicio() {
  servicioCounter++;
  const container = document.getElementById('servicios-container');
  const servicioHTML = `
    <div class="servicio-input mb-2" id="servicio-${servicioCounter}">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Título del servicio" name="servicio-titulo-${servicioCounter}">
        <input type="text" class="form-control" placeholder="Descripción" name="servicio-descripcion-${servicioCounter}">
        <button type="button" class="btn btn-outline-danger" onclick="eliminarServicio(${servicioCounter})">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
  `;
  container.innerHTML += servicioHTML;
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
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Título del servicio" name="servicio-titulo-${servicioCounter}" value="${descripcion.titulo}">
              <input type="text" class="form-control" placeholder="Descripción" name="servicio-descripcion-${servicioCounter}" value="${descripcion.contenido}">
              <button type="button" class="btn btn-outline-danger" onclick="eliminarServicio(${servicioCounter})">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        `;
        document.getElementById('servicios-container').innerHTML += servicioHTML;
      });
    }
    
    document.getElementById('form-title').innerHTML = '<i class="fas fa-edit me-2"></i>Editar Edificio';
    document.getElementById('nombre-edificio').focus();
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
  
  calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    locale: 'es',
    firstDay: 1,
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay"
    },
    events: eventos,
    eventClick: function(info) {
      abrirModalEvento(info.event);
    },
    dateClick: function(info) {
      nuevoEvento(info.dateStr);
    }
  });

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
  const inicio = new Date(event.start);
  const fin = event.end ? new Date(event.end) : null;
  
  document.getElementById('inicio-evento').value = inicio.toISOString().split('T')[0];
  document.getElementById('fin-evento').value = fin ? fin.toISOString().split('T')[0] : '';
  
  document.getElementById('btn-eliminar-evento').style.display = 'block';
  modal.show();
}

function nuevoEvento(fecha) {
  eventoActual = null;
  const modal = new bootstrap.Modal(document.getElementById('modalEvento'));
  
  document.getElementById('modalEventoTitle').textContent = 'Agregar Nuevo Evento';
  document.getElementById('form-evento').reset();
  document.getElementById('evento-id').value = '';
  
  // Establecer fecha por defecto
  const fechaInicio = fecha ? new Date(fecha) : new Date();
  document.getElementById('inicio-evento').value = fechaInicio.toISOString().split('T')[0];
  document.getElementById('fin-evento').value = '';
  
  document.getElementById('btn-eliminar-evento').style.display = 'none';
  modal.show();
}

function editarEvento(id) {
  const eventos = <?= json_encode($eventos) ?>;
  const evento = eventos.find(e => e.id == id);
  
  if (evento) {
    const modal = new bootstrap.Modal(document.getElementById('modalEvento'));
    
    document.getElementById('modalEventoTitle').textContent = 'Editar Evento';
    document.getElementById('evento-id').value = evento.id;
    document.getElementById('titulo-evento').value = evento.title;
    document.getElementById('descripcion-evento').value = evento.description || '';
    document.getElementById('color-evento').value = evento.backgroundColor || '#19a473';
    document.getElementById('tipo-evento').value = evento.tipo || 'academico';
    
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
  document.getElementById('form-edificio').addEventListener('submit', function(e) {
    if (!validarFormularioEdificio()) {
      e.preventDefault();
    }
  });
  
  agregarServicio();
  inicializarCalendario();
  
  const syncStatus = document.createElement('div');
  syncStatus.className = 'sync-status';
  syncStatus.innerHTML = '<i class="fas fa-sync-alt me-2"></i>Sincronizado con JSON';
  document.body.appendChild(syncStatus);
});

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
// -------- CALENDARIO --------
document.addEventListener("DOMContentLoaded", () => {
  const calendarEl = document.getElementById("calendar");

  // Verificar que el elemento del calendario existe
  if (!calendarEl) {
    console.error('Elemento del calendario no encontrado');
    return;
  }

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    locale: 'es', // Idioma español
    firstDay: 1, // Lunes como primer día de la semana
    selectable: true,
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
    select: function(info) {
      const title = prompt("Nombre del evento:");
      if (title) {
        calendar.addEvent({
          title: title,
          start: info.start,
          end: info.end,
          allDay: info.allDay,
          backgroundColor: '#19a473',
          borderColor: '#19a473'
        });
      }
    },
    eventClick: function(info) {
      alert('Evento: ' + info.event.title);
    },
    datesSet: function(info) {
      console.log("Vista cambiada:", info.view.type);
    }
  });

  // Cargar eventos desde el JSON
  cargarEventosYMarcadores(calendar);

  calendar.render();
  console.log("Calendario inicializado correctamente");
});

// Función para cargar eventos y marcadores
function cargarEventosYMarcadores(calendar) {
  fetch('assets/js/mapa.json')
    .then(response => {
      if (!response.ok) {
        throw new Error('Error al cargar el JSON');
      }
      return response.json();
    })
    .then(data => {
      console.log("Datos cargados:", data);
      
      // Cargar eventos en el calendario
      if (data.eventos && data.eventos.length > 0) {
        console.log("Eventos encontrados:", data.eventos.length);
        calendar.removeAllEvents();
        calendar.addEventSource(data.eventos);
        
        // Mostrar eventos en la lista
        mostrarListaEventos(data.eventos);
      } else {
        console.log("No hay eventos en el JSON");
        document.getElementById('lista-eventos').innerHTML = `
          <div class="text-center text-muted p-4">
            <i class="fas fa-calendar-day fa-3x mb-3"></i>
            <p>No hay eventos registrados</p>
          </div>
        `;
      }

      // Cargar marcadores en el mapa
      if (data.marcadores && data.marcadores.length > 0) {
        console.log("Marcadores encontrados:", data.marcadores.length);
        data.marcadores.forEach((marcador) => {
          L.marker([marcador.lat, marcador.lng])
            .addTo(map)
            .bindPopup(`<b>${marcador.nombre}</b>`)
            .on('click', function() {
              const elem = document.querySelector(`#${marcador.edificioId}`);
              if(elem){
                elem.scrollIntoView({ behavior: 'smooth', block: 'start' });
              }
            });
        });
      }

      // Configurar el mapa si hay configuración
      if (data.mapaConfig) {
        map.setView([data.mapaConfig.lat, data.mapaConfig.lng], data.mapaConfig.zoom);
      }
    })
    .catch(error => {
      console.error('Error cargando datos del JSON:', error);
      // Cargar marcadores por defecto si hay error
      cargarMarcadoresPorDefecto();
      mostrarErrorEnCalendario();
    });
}

// Función para mostrar la lista de eventos
function mostrarListaEventos(eventos) {
  const listaContainer = document.getElementById('lista-eventos');
  
  if (!listaContainer) {
    console.error('Elemento lista-eventos no encontrado');
    return;
  }

  if (eventos.length === 0) {
    listaContainer.innerHTML = `
      <div class="text-center text-muted p-4">
        <i class="fas fa-calendar-day fa-3x mb-3"></i>
        <p>No hay eventos registrados</p>
      </div>
    `;
    return;
  }

  let html = '';
  eventos.forEach(evento => {
    const fechaInicio = new Date(evento.start).toLocaleDateString('es-ES');
    const fechaFin = evento.end ? new Date(evento.end).toLocaleDateString('es-ES') : '';
    
    html += `
      <div class="evento-item mb-3 p-3 border rounded">
        <div class="d-flex justify-content-between align-items-start">
          <div class="flex-grow-1">
            <h6 class="mb-1">
              <span class="evento-color" style="background-color: ${evento.backgroundColor || '#19a473'}"></span>
              ${evento.title || 'Sin título'}
            </h6>
            <p class="text-muted mb-1 small">
              <i class="fas fa-clock me-1"></i>
              ${fechaInicio}
              ${fechaFin ? ` - ${fechaFin}` : ''}
            </p>
            ${evento.description ? `<p class="mb-1 small">${evento.description}</p>` : ''}
            <span class="badge bg-secondary">${evento.tipo || 'academico'}</span>
          </div>
        </div>
      </div>
    `;
  });
  
  listaContainer.innerHTML = html;
}

// Función para mostrar error en el calendario
function mostrarErrorEnCalendario() {
  const listaContainer = document.getElementById('lista-eventos');
  if (listaContainer) {
    listaContainer.innerHTML = `
      <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Error al cargar los eventos. Verifica la conexión.
      </div>
    `;
  }
}

// Función de respaldo para marcadores
function cargarMarcadoresPorDefecto() {
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
}

  calendar.render();

</script>

</body>
</html>