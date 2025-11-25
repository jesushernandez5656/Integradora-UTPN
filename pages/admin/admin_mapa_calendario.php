<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Mapa y Calendario</title>
    
    <!-- ===================== CSS EXTERNOS ===================== -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- ===================== ESTILOS LOCALES ===================== -->
    <style>
      :root {
        --txt: #2e2e2e;
      }

      body {
        margin: 0;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, "Helvetica Neue", Arial, sans-serif;
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

      /* ==== CALENDARIO ==== */
      #calendar {
        background-color: #fff;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-top: 20px;
      }

      /* ==== FORMULARIOS ==== */
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
    </style>
</head>
<body>

<!-- ===================== CONTENIDO ===================== -->
<div class="contenedor-principal">
  <h1>Panel de Administración - Mapa y Calendario</h1>

  <!-- Pestañas de Navegación -->
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
      <button class="nav-link" id="mapa-tab" data-bs-toggle="tab" data-bs-target="#mapa" type="button" role="tab">
        <i class="fas fa-map me-2"></i>Mapa
      </button>
    </li>
  </ul>

  <div class="tab-content" id="adminTabsContent">
    
    <!-- Pestaña Edificios -->
    <div class="tab-pane fade show active" id="edificios" role="tabpanel">
      <div class="seccion-admin">
        <h2><i class="fas fa-building me-2"></i>Gestión de Edificios</h2>
        
        <div class="row">
          <!-- Formulario para agregar/editar edificios -->
          <div class="col-md-6">
            <div class="formulario-edificio">
              <h4 id="form-title"><i class="fas fa-plus me-2"></i>Agregar Nuevo Edificio</h4>
              <form id="form-edificio">
                <input type="hidden" id="edificio-id">
                
                <div class="mb-3">
                  <label class="form-label">Nombre del Edificio *</label>
                  <input type="text" class="form-control" id="nombre-edificio" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Descripción</label>
                  <textarea class="form-control" id="descripcion-edificio" rows="2" placeholder="Breve descripción del edificio..."></textarea>
                </div>

                <div class="mb-3">
                  <label class="form-label">Color identificador *</label>
                  <input type="color" class="form-control form-control-color" id="color-edificio" value="#19a473" title="Elige un color">
                </div>

                <div class="mb-3">
                  <label class="form-label">Servicios y Áreas *</label>
                  <div id="servicios-container">
                    <!-- Los servicios se agregarán dinámicamente aquí -->
                  </div>
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

          <!-- Lista de edificios existentes -->
          <div class="col-md-6">
            <h4><i class="fas fa-list me-2"></i>Edificios Existentes</h4>
            <div class="lista-edificios" id="lista-edificios">
              <!-- Los edificios se cargarán aquí dinámicamente -->
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
            <button class="btn btn-admin" data-bs-toggle="modal" data-bs-target="#modalEvento">
              <i class="fas fa-plus me-1"></i>Agregar Nuevo Evento
            </button>
            <button class="btn btn-outline-secondary" onclick="exportarCalendario()">
              <i class="fas fa-download me-1"></i>Exportar Eventos
            </button>
            <button class="btn btn-outline-warning" onclick="importarCalendario()">
              <i class="fas fa-upload me-1"></i>Importar Eventos
            </button>
          </div>
        </div>

        <div id="calendar"></div>
      </div>
    </div>

    <!-- Pestaña Mapa -->
    <div class="tab-pane fade" id="mapa" role="tabpanel">
      <div class="seccion-admin">
        <h2><i class="fas fa-map me-2"></i>Configuración del Mapa</h2>
        
        <div class="row">
          <div class="col-md-6">
            <h4>Marcadores del Mapa</h4>
            <div class="lista-edificios" id="lista-marcadores">
              <!-- Los marcadores se cargarán aquí dinámicamente -->
            </div>
          </div>
          <div class="col-md-6">
            <h4>Configuración General</h4>
            <div class="formulario-edificio">
              <div class="mb-3">
                <label class="form-label">Coordenadas del Centro del Mapa</label>
                <div class="input-group">
                  <span class="input-group-text">Latitud</span>
                  <input type="number" class="form-control" id="map-lat" step="0.000001" value="31.766600">
                  <span class="input-group-text">Longitud</span>
                  <input type="number" class="form-control" id="map-lng" step="0.000001" value="-106.562487">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Nivel de Zoom</label>
                <input type="range" class="form-range" id="map-zoom" min="15" max="20" value="17">
                <div class="form-text">Zoom actual: <span id="zoom-value">17</span></div>
              </div>
              <button class="btn btn-admin" onclick="guardarConfigMapa()">
                <i class="fas fa-save me-1"></i>Guardar Configuración
              </button>
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
        <form id="form-evento">
          <input type="hidden" id="evento-id">
          
          <div class="mb-3">
            <label class="form-label">Título del Evento *</label>
            <input type="text" class="form-control" id="titulo-evento" required>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Fecha de Inicio *</label>
                <input type="datetime-local" class="form-control" id="inicio-evento" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Fecha de Fin</label>
                <input type="datetime-local" class="form-control" id="fin-evento">
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion-evento" rows="3" placeholder="Descripción detallada del evento..."></textarea>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Color del Evento</label>
                <input type="color" class="form-control form-control-color" id="color-evento" value="#19a473">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Tipo de Evento</label>
                <select class="form-select" id="tipo-evento">
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
        <button type="button" class="btn btn-admin" onclick="guardarEvento()">
          <i class="fas fa-save me-1"></i>Guardar Evento
        </button>
        <button type="button" class="btn btn-danger-admin" id="btn-eliminar-evento" style="display: none;" onclick="eliminarEvento()">
          <i class="fas fa-trash me-1"></i>Eliminar Evento
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Input oculto para importar archivos -->
<input type="file" id="import-file" accept=".json" style="display: none;">

<!-- ===================== JS EXTERNOS ===================== -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- ===================== JS LOCAL ===================== -->
<script>
// ===================== SISTEMA DE SINCRONIZACIÓN =====================
const STORAGE_KEYS = {
  EDIFICIOS: 'utpn_edificios_data',
  EVENTOS: 'utpn_calendario_eventos',
  MAPA_CONFIG: 'utpn_mapa_config',
  MARCADORES: 'utpn_mapa_marcadores',
  TIMESTAMP: 'utpn_data_timestamp'
};

// Función para notificar cambios a otras pestañas
function notificarCambio(tipo) {
  // Actualizar timestamp
  const timestamp = Date.now();
  localStorage.setItem(STORAGE_KEYS.TIMESTAMP, timestamp.toString());
  
  // Disparar evento personalizado
  window.dispatchEvent(new CustomEvent('utpnDataChanged', {
    detail: { tipo, timestamp }
  }));
  
  console.log(`Cambio notificado: ${tipo} - ${new Date(timestamp).toLocaleTimeString()}`);
}

// Escuchar cambios en otras pestañas
window.addEventListener('storage', function(e) {
  if (e.key === STORAGE_KEYS.TIMESTAMP) {
    console.log('Cambio detectado en otra pestaña, recargando datos...');
    recargarDatos();
  }
});

// También escuchar eventos personalizados (misma pestaña)
window.addEventListener('utpnDataChanged', function(e) {
  console.log('Cambio detectado vía evento personalizado');
  recargarDatos();
});

function recargarDatos() {
  if (typeof cargarEdificios === 'function') cargarEdificios();
  if (typeof recargarCalendario === 'function') recargarCalendario();
  if (typeof cargarConfiguracionMapa === 'function') cargarConfiguracionMapa();
  mostrarNotificacion('Datos actualizados', 'info');
}

// ===================== ALMACENAMIENTO LOCAL =====================

// Datos por defecto
const datosPorDefecto = {
  edificios: [
    {
      id: 1,
      nombre: "Edificio A",
      descripcion: "Edificio principal administrativo",
      color: "#19a473",
      servicios: [
        { nombre: "Escolares", descripcion: "Oficina encargada de la gestión académica de los estudiantes" },
        { nombre: "Psicología", descripcion: "Área de atención psicológica para estudiantes" }
      ]
    },
    {
      id: 2,
      nombre: "Edificio B",
      descripcion: "Edificio de laboratorios",
      color: "#3498db",
      servicios: [
        { nombre: "Laboratorio de Redes", descripcion: "Laboratorio especializado en redes de comunicación" },
        { nombre: "Laboratorio Mecatrónica", descripcion: "Espacio para prácticas de robótica y automatización" }
      ]
    }
  ],
  eventos: [
    {
      id: 1,
      title: "Entrega de Proyecto Final",
      start: new Date().toISOString().split('T')[0],
      color: "#19a473",
      description: "Entrega final del proyecto de semestre",
      tipo: "academico"
    },
    {
      id: 2,
      title: "Reunión Académica",
      start: new Date(Date.now() + 86400000).toISOString().split('T')[0] + 'T10:00',
      end: new Date(Date.now() + 86400000).toISOString().split('T')[0] + 'T12:00',
      color: "#e74c3c",
      description: "Reunión general de profesores",
      tipo: "administrativo"
    }
  ],
  mapaConfig: {
    lat: 31.766600,
    lng: -106.562487,
    zoom: 17
  },
  marcadores: [
    { id: 1, nombre: "Edificio A", lat: 31.766365, lng: -106.561663, edificioId: 1 },
    { id: 2, nombre: "Edificio B", lat: 31.766841, lng: -106.561029, edificioId: 2 }
  ]
};

// Funciones de almacenamiento
function guardarDatos(clave, datos) {
  localStorage.setItem(clave, JSON.stringify(datos));
  notificarCambio(clave);
}

function cargarDatos(clave) {
  const datos = localStorage.getItem(clave);
  if (datos) {
    return JSON.parse(datos);
  }
  // Si no hay datos, usar los por defecto
  const claveBase = clave.split('_')[1].toLowerCase();
  return datosPorDefecto[claveBase] || [];
}

function inicializarAlmacenamiento() {
  // Inicializar todos los almacenamientos si no existen
  Object.keys(STORAGE_KEYS).forEach(key => {
    if (key !== 'TIMESTAMP' && !localStorage.getItem(STORAGE_KEYS[key])) {
      const claveBase = key.toLowerCase();
      guardarDatos(STORAGE_KEYS[key], datosPorDefecto[claveBase]);
    }
  });
}

// ===================== GESTIÓN DE EDIFICIOS =====================
let edificios = [];
let servicioCounter = 0;

function cargarEdificios() {
  edificios = cargarDatos(STORAGE_KEYS.EDIFICIOS);
  actualizarListaEdificios();
  actualizarListaMarcadores();
}

function actualizarListaEdificios() {
  const lista = document.getElementById('lista-edificios');
  if (!lista) return;
  
  lista.innerHTML = '';

  if (edificios.length === 0) {
    lista.innerHTML = '<div class="text-center text-muted p-4"><i class="fas fa-building fa-3x mb-3"></i><p>No hay edificios registrados</p></div>';
    return;
  }

  edificios.forEach(edificio => {
    const edificioHTML = `
      <div class="edificio-item">
        <div class="d-flex justify-content-between align-items-start">
          <div class="flex-grow-1">
            <h5>
              <span class="color-preview" style="background-color: ${edificio.color}"></span>
              ${edificio.nombre}
            </h5>
            <p class="text-muted mb-2">${edificio.descripcion || 'Sin descripción'}</p>
            <div class="servicios-lista">
              ${edificio.servicios.map(servicio => 
                `<div class="servicio-item">
                  <strong>${servicio.nombre}:</strong> ${servicio.descripcion}
                </div>`
              ).join('')}
            </div>
          </div>
          <div class="btn-group ms-3">
            <button class="btn btn-sm btn-outline-primary" onclick="editarEdificio(${edificio.id})" title="Editar">
              <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger" onclick="eliminarEdificio(${edificio.id})" title="Eliminar">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
      </div>
    `;
    lista.innerHTML += edificioHTML;
  });
}

function agregarServicio() {
  servicioCounter++;
  const container = document.getElementById('servicios-container');
  const servicioHTML = `
    <div class="servicio-input mb-2" id="servicio-${servicioCounter}">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Nombre del servicio" name="servicio-nombre">
        <input type="text" class="form-control" placeholder="Descripción" name="servicio-descripcion">
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
  if (elemento) {
    elemento.remove();
  }
}

function guardarEdificio(event) {
  event.preventDefault();
  
  const id = document.getElementById('edificio-id').value;
  const nombre = document.getElementById('nombre-edificio').value;
  const descripcion = document.getElementById('descripcion-edificio').value;
  const color = document.getElementById('color-edificio').value;

  // Recoger servicios
  const servicios = [];
  document.querySelectorAll('.servicio-input').forEach(servicioDiv => {
    const nombreInput = servicioDiv.querySelector('input[name="servicio-nombre"]');
    const descripcionInput = servicioDiv.querySelector('input[name="servicio-descripcion"]');
    
    if (nombreInput && descripcionInput) {
      const nombreServicio = nombreInput.value.trim();
      const descripcionServicio = descripcionInput.value.trim();
      
      if (nombreServicio && descripcionServicio) {
        servicios.push({ 
          nombre: nombreServicio, 
          descripcion: descripcionServicio 
        });
      }
    }
  });

  if (!nombre || servicios.length === 0) {
    mostrarNotificacion('Por favor, completa el nombre y al menos un servicio', 'warning');
    return;
  }

  if (id) {
    // Editar edificio existente
    const index = edificios.findIndex(e => e.id == id);
    if (index !== -1) {
      edificios[index] = { ...edificios[index], nombre, descripcion, color, servicios };
    }
  } else {
    // Agregar nuevo edificio
    const nuevoId = edificios.length > 0 ? Math.max(...edificios.map(e => e.id)) + 1 : 1;
    edificios.push({
      id: nuevoId,
      nombre,
      descripcion,
      color,
      servicios
    });
  }

  guardarDatos(STORAGE_KEYS.EDIFICIOS, edificios);
  cargarEdificios();
  resetFormEdificio();
  
  mostrarNotificacion('Edificio guardado correctamente', 'success');
}

function editarEdificio(id) {
  const edificio = edificios.find(e => e.id == id);
  if (edificio) {
    document.getElementById('edificio-id').value = edificio.id;
    document.getElementById('nombre-edificio').value = edificio.nombre;
    document.getElementById('descripcion-edificio').value = edificio.descripcion || '';
    document.getElementById('color-edificio').value = edificio.color;
    
    // Limpiar y cargar servicios
    document.getElementById('servicios-container').innerHTML = '';
    servicioCounter = 0;
    
    edificio.servicios.forEach(servicio => {
      servicioCounter++;
      const servicioHTML = `
        <div class="servicio-input mb-2" id="servicio-${servicioCounter}">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Nombre del servicio" name="servicio-nombre" value="${servicio.nombre}">
            <input type="text" class="form-control" placeholder="Descripción" name="servicio-descripcion" value="${servicio.descripcion}">
            <button type="button" class="btn btn-outline-danger" onclick="eliminarServicio(${servicioCounter})">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
      `;
      document.getElementById('servicios-container').innerHTML += servicioHTML;
    });
    
    document.getElementById('form-title').innerHTML = '<i class="fas fa-edit me-2"></i>Editar Edificio';
    document.getElementById('nombre-edificio').focus();
  }
}

function eliminarEdificio(id) {
  if (confirm('¿Estás seguro de que quieres eliminar este edificio? Esta acción no se puede deshacer.')) {
    edificios = edificios.filter(e => e.id != id);
    guardarDatos(STORAGE_KEYS.EDIFICIOS, edificios);
    cargarEdificios();
    mostrarNotificacion('Edificio eliminado correctamente', 'warning');
  }
}

function resetFormEdificio() {
  document.getElementById('form-edificio').reset();
  document.getElementById('edificio-id').value = '';
  document.getElementById('servicios-container').innerHTML = '';
  document.getElementById('form-title').innerHTML = '<i class="fas fa-plus me-2"></i>Agregar Nuevo Edificio';
  servicioCounter = 0;
  // Agregar un servicio vacío por defecto
  agregarServicio();
}

// ===================== GESTIÓN DE CALENDARIO =====================
let calendar;
let eventoActual = null;
let eventos = [];

function inicializarCalendario() {
  eventos = cargarDatos(STORAGE_KEYS.EVENTOS);
  
  const calendarEl = document.getElementById('calendar');
  if (!calendarEl) return;
  
  calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    locale: 'es',
    firstDay: 1,
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay"
    },
    events: eventos.map(evento => ({
      ...evento,
      start: evento.start,
      end: evento.end
    })),
    eventClick: function(info) {
      abrirModalEvento(info.event);
    },
    dateClick: function(info) {
      nuevoEvento(info.dateStr);
    }
  });

  calendar.render();
}

function recargarCalendario() {
  if (calendar) {
    eventos = cargarDatos(STORAGE_KEYS.EVENTOS);
    calendar.removeAllEvents();
    eventos.forEach(evento => calendar.addEvent(evento));
  }
}

function abrirModalEvento(event) {
  eventoActual = event;
  const modal = new bootstrap.Modal(document.getElementById('modalEvento'));
  
  document.getElementById('modalEventoTitle').textContent = 'Editar Evento';
  document.getElementById('evento-id').value = event.id;
  document.getElementById('titulo-evento').value = event.title;
  document.getElementById('descripcion-evento').value = event.extendedProps.description || '';
  document.getElementById('color-evento').value = event.backgroundColor || '#19a473';
  document.getElementById('tipo-evento').value = event.extendedProps.tipo || 'academico';
  
  // Formatear fechas para el input datetime-local
  const inicio = new Date(event.start);
  const fin = event.end ? new Date(event.end) : new Date(event.start);
  
  document.getElementById('inicio-evento').value = inicio.toISOString().slice(0, 16);
  document.getElementById('fin-evento').value = fin.toISOString().slice(0, 16);
  
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
  const ahora = new Date();
  const fechaInicio = fecha ? new Date(fecha) : ahora;
  fechaInicio.setHours(9, 0, 0, 0);
  
  const fechaFin = new Date(fechaInicio);
  fechaFin.setHours(17, 0, 0, 0);
  
  document.getElementById('inicio-evento').value = fechaInicio.toISOString().slice(0, 16);
  document.getElementById('fin-evento').value = fechaFin.toISOString().slice(0, 16);
  document.getElementById('btn-eliminar-evento').style.display = 'none';
  modal.show();
}

function guardarEvento() {
  const id = document.getElementById('evento-id').value;
  const title = document.getElementById('titulo-evento').value;
  const start = document.getElementById('inicio-evento').value;
  const end = document.getElementById('fin-evento').value;
  const description = document.getElementById('descripcion-evento').value;
  const color = document.getElementById('color-evento').value;
  const tipo = document.getElementById('tipo-evento').value;

  if (!title || !start) {
    mostrarNotificacion('Por favor, completa los campos obligatorios', 'warning');
    return;
  }

  const eventoData = {
    title,
    start,
    end: end || start,
    backgroundColor: color,
    borderColor: color,
    description,
    tipo
  };

  if (id && eventoActual) {
    // Actualizar evento existente
    const index = eventos.findIndex(e => e.id == id);
    if (index !== -1) {
      eventos[index] = { ...eventos[index], ...eventoData };
      
      // Actualizar en el calendario
      eventoActual.remove();
      calendar.addEvent(eventos[index]);
    }
  } else {
    // Crear nuevo evento
    const nuevoId = eventos.length > 0 ? Math.max(...eventos.map(e => e.id)) + 1 : 1;
    const nuevoEvento = {
      id: nuevoId,
      ...eventoData
    };
    
    calendar.addEvent(nuevoEvento);
    eventos.push(nuevoEvento);
  }

  guardarDatos(STORAGE_KEYS.EVENTOS, eventos);
  bootstrap.Modal.getInstance(document.getElementById('modalEvento')).hide();
  mostrarNotificacion('Evento guardado correctamente', 'success');
}

function eliminarEvento() {
  if (confirm('¿Estás seguro de que quieres eliminar este evento?')) {
    const id = document.getElementById('evento-id').value;
    
    eventoActual.remove();
    eventos = eventos.filter(e => e.id != id);
    guardarDatos(STORAGE_KEYS.EVENTOS, eventos);
    
    bootstrap.Modal.getInstance(document.getElementById('modalEvento')).hide();
    mostrarNotificacion('Evento eliminado correctamente', 'warning');
  }
}

function exportarCalendario() {
  const dataStr = JSON.stringify(eventos, null, 2);
  const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
  
  const exportFileDefaultName = `calendario_export_${new Date().toISOString().split('T')[0]}.json`;
  
  const linkElement = document.createElement('a');
  linkElement.setAttribute('href', dataUri);
  linkElement.setAttribute('download', exportFileDefaultName);
  linkElement.click();
  
  mostrarNotificacion('Eventos exportados correctamente', 'info');
}

function importarCalendario() {
  document.getElementById('import-file').click();
}

// Manejar la importación de archivos
document.getElementById('import-file').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      try {
        const eventosImportados = JSON.parse(e.target.result);
        if (Array.isArray(eventosImportados)) {
          eventos = eventosImportados;
          guardarDatos(STORAGE_KEYS.EVENTOS, eventos);
          
          // Recargar el calendario
          calendar.removeAllEvents();
          eventos.forEach(evento => calendar.addEvent(evento));
          
          mostrarNotificacion('Eventos importados correctamente', 'success');
        } else {
          mostrarNotificacion('El archivo no contiene una lista válida de eventos', 'warning');
        }
      } catch (error) {
        mostrarNotificacion('Error al importar el archivo: ' + error.message, 'warning');
      }
    };
    reader.readAsText(file);
  }
  
  // Limpiar el input
  e.target.value = '';
});

// ===================== GESTIÓN DEL MAPA =====================
function cargarConfiguracionMapa() {
  const config = cargarDatos(STORAGE_KEYS.MAPA_CONFIG);
  
  if (document.getElementById('map-lat')) {
    document.getElementById('map-lat').value = config.lat;
    document.getElementById('map-lng').value = config.lng;
    document.getElementById('map-zoom').value = config.zoom;
    document.getElementById('zoom-value').textContent = config.zoom;
  }
}

function guardarConfigMapa() {
  const config = {
    lat: parseFloat(document.getElementById('map-lat').value),
    lng: parseFloat(document.getElementById('map-lng').value),
    zoom: parseInt(document.getElementById('map-zoom').value)
  };
  
  guardarDatos(STORAGE_KEYS.MAPA_CONFIG, config);
  mostrarNotificacion('Configuración del mapa guardada', 'success');
}

function actualizarListaMarcadores() {
  const marcadores = cargarDatos(STORAGE_KEYS.MARCADORES);
  const lista = document.getElementById('lista-marcadores');
  if (!lista) return;
  
  lista.innerHTML = '';

  if (marcadores.length === 0) {
    lista.innerHTML = '<div class="text-center text-muted p-4"><i class="fas fa-map-marker-alt fa-3x mb-3"></i><p>No hay marcadores registrados</p></div>';
    return;
  }

  marcadores.forEach(marcador => {
    const edificio = edificios.find(e => e.id === marcador.edificioId);
    const marcadorHTML = `
      <div class="edificio-item">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6>${marcador.nombre}</h6>
            <small class="text-muted">Lat: ${marcador.lat}, Lng: ${marcador.lng}</small>
            ${edificio ? `<br><small>Edificio: ${edificio.nombre}</small>` : ''}
          </div>
          <div class="btn-group">
            <button class="btn btn-sm btn-outline-primary" onclick="editarMarcador(${marcador.id})">
              <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger" onclick="eliminarMarcador(${marcador.id})">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
      </div>
    `;
    lista.innerHTML += marcadorHTML;
  });
}

// ===================== UTILIDADES =====================
function mostrarNotificacion(mensaje, tipo = 'info') {
  // Eliminar notificaciones anteriores
  const alertasAnteriores = document.querySelectorAll('.alert-position');
  alertasAnteriores.forEach(alerta => alerta.remove());

  // Crear notificación
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
  
  // Auto-eliminar después de 4 segundos
  setTimeout(() => {
    const alert = document.querySelector('.alert-position');
    if (alert) alert.remove();
  }, 4000);
}

// ===================== INICIALIZACIÓN =====================
document.addEventListener('DOMContentLoaded', function() {
  // Inicializar almacenamiento
  inicializarAlmacenamiento();
  
  // Cargar datos
  cargarEdificios();
  inicializarCalendario();
  cargarConfiguracionMapa();
  
  // Event listeners
  if (document.getElementById('form-edificio')) {
    document.getElementById('form-edificio').addEventListener('submit', guardarEdificio);
  }
  
  if (document.getElementById('map-zoom')) {
    document.getElementById('map-zoom').addEventListener('input', function() {
      document.getElementById('zoom-value').textContent = this.value;
    });
  }
  
  // Agregar un servicio por defecto al cargar
  if (document.getElementById('servicios-container')) {
    agregarServicio();
  }
  
  // Mostrar estado de sincronización
  const syncStatus = document.createElement('div');
  syncStatus.className = 'sync-status';
  syncStatus.innerHTML = '<i class="fas fa-sync-alt me-2"></i>Sincronizado';
  document.body.appendChild(syncStatus);
  
  mostrarNotificacion('Panel de administración cargado correctamente', 'success');
});

// Funciones placeholder para marcadores (para futura implementación)
function editarMarcador(id) {
  mostrarNotificacion('Funcionalidad de editar marcador en desarrollo', 'info');
}

function eliminarMarcador(id) {
  if (confirm('¿Eliminar este marcador?')) {
    mostrarNotificacion('Marcador eliminado', 'warning');
  }
}
</script>

</body>
</html>