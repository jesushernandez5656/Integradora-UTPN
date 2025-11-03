// Configuración de la API
const API_BASE_URL = '/INTEGRADORA-UTPN/pages/api/consejos-json.php?action=listar';
const API_CATEGORIAS_URL = '/INTEGRADORA-UTPN/pages/api/consejos-json.php?action=listar_categorias';

// Estado de la aplicación
let consejos = [];
let categorias = [];
let filtroCategoria = 'todos';
let busqueda = '';

// Inicialización
document.addEventListener('DOMContentLoaded', () => {
    console.log('✅ DOM cargado, iniciando aplicación...');
    cargarDatos();
    configurarEventosGlobales();
});

// Cargar todos los datos
async function cargarDatos() {
    try {
        console.log('🔄 Iniciando carga de datos...');
        
        // Cargar categorías y consejos en paralelo
        const [responseCategorias, responseConsejos] = await Promise.all([
            fetch(API_CATEGORIAS_URL),
            fetch(API_BASE_URL)
        ]);
        
        console.log('📡 Respuestas recibidas');
        
        // Procesar categorías
        const dataCategorias = await responseCategorias.json();
        if (dataCategorias.success) {
            categorias = dataCategorias.data || [];
            console.log('✅ Categorías cargadas:', categorias.length);
            cargarCategorias();
        }
        
        // Procesar consejos
        const dataConsejos = await responseConsejos.json();
        if (dataConsejos.success) {
            consejos = dataConsejos.data || [];
            console.log('✅ Consejos cargados:', consejos.length);
            mostrarConsejos();
        } else {
            throw new Error(dataConsejos.error || 'Error al cargar consejos');
        }
        
    } catch (error) {
        console.error('💥 Error al cargar datos:', error);
        mostrarError('No se pudieron cargar los consejos. Por favor, recarga la página.');
    }
}

// Cargar categorías en el DOM
function cargarCategorias() {
    const categoryGrid = document.querySelector('.category-grid');
    if (!categoryGrid) return;
    
    // Mantener el botón "Todos"
    const todosButton = categoryGrid.querySelector('[data-category="todos"]');
    
    // Agregar categorías dinámicas
    categorias.forEach(cat => {
        const categoryCard = document.createElement('div');
        categoryCard.className = 'category-card';
        categoryCard.dataset.category = cat.id;
        categoryCard.innerHTML = `
            <span class="category-icon">${cat.icono}</span>
            <h3>${cat.nombre}</h3>
        `;
        categoryGrid.appendChild(categoryCard);
    });
    
    // Configurar eventos de categorías
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.category-card').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            filtroCategoria = card.dataset.category;
            mostrarConsejos();
        });
    });
}

// Mostrar consejos filtrados
function mostrarConsejos() {
    const container = document.getElementById('consejosContainer');
    if (!container) {
        console.error('❌ No se encontró el contenedor de consejos');
        return;
    }
    
    const consejosFiltrados = filtrarConsejos();
    console.log('🎨 Mostrando consejos filtrados:', consejosFiltrados.length);
    
    if (consejosFiltrados.length === 0) {
        container.innerHTML = `
            <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                <i class="fas fa-search" style="font-size: 4em; color: var(--gray-medium); margin-bottom: 20px;"></i>
                <h3 style="color: var(--gray-medium);">No se encontraron consejos</h3>
                <p style="color: var(--gray-medium);">Intenta con otra categoría o búsqueda</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = consejosFiltrados.map((consejo, index) => 
        crearTarjetaConsejo(consejo, index)
    ).join('');
    
    // Animar las tarjetas
    animarTarjetas();
    
    // Configurar eventos usando event delegation
    configurarEventosTarjetas();
}

// Filtrar consejos
function filtrarConsejos() {
    return consejos.filter(consejo => {
        const cumpleBusqueda = !busqueda || 
            consejo.titulo.toLowerCase().includes(busqueda) ||
            consejo.descripcion.toLowerCase().includes(busqueda);
            
        const cumpleCategoria = filtroCategoria === 'todos' || 
            consejo.categoria_id.toString() === filtroCategoria;
            
        return cumpleBusqueda && cumpleCategoria && consejo.activo === 1;
    });
}

// Crear tarjeta de consejo
function crearTarjetaConsejo(consejo, index) {
    const prioridad = consejo.prioridad.toLowerCase();
    const badges = {
        'high': { class: 'badge-high', text: 'ALTA PRIORIDAD' },
        'medium': { class: 'badge-medium', text: 'PRIORIDAD MEDIA' },
        'low': { class: 'badge-low', text: 'PRIORIDAD BAJA' }
    };
    
    const badge = badges[prioridad] || badges.medium;
    
    return `
        <div class="tip-card loading" data-consejo-id="${consejo.id}" style="animation-delay: ${index * 0.1}s">
            <div class="tip-header">
                <div class="tip-icon-circle">${consejo.icono}</div>
                <h3>${consejo.titulo}</h3>
            </div>
            <p>${consejo.descripcion}</p>
            <div class="tip-footer">
                <span class="category-badge">${consejo.categoria_nombre || 'General'}</span>
                <a href="#" class="read-more" data-id="${consejo.id}">
                    Ver más <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    `;
}

// Animar tarjetas
function animarTarjetas() {
    setTimeout(() => {
        document.querySelectorAll('.tip-card').forEach(card => {
            card.style.opacity = '1';
        });
    }, 50);
}

// Configurar eventos de las tarjetas usando Event Delegation
function configurarEventosTarjetas() {
    console.log('🔧 Configurando eventos de tarjetas...');
    
    const container = document.getElementById('consejosContainer');
    if (!container) return;
    
    // Usar event delegation - un solo listener en el contenedor padre
    container.removeEventListener('click', handleReadMoreClick);
    container.addEventListener('click', handleReadMoreClick);
    
    const readMoreLinks = container.querySelectorAll('.read-more');
    console.log(`📍 Enlaces encontrados: ${readMoreLinks.length}`);
}

// Manejador de clicks separado para mejor control
function handleReadMoreClick(e) {
    const readMoreLink = e.target.closest('.read-more');
    
    if (readMoreLink) {
        e.preventDefault();
        e.stopPropagation();
        
        const consejoId = parseInt(readMoreLink.dataset.id);
        console.log(`🖱️ Click en consejo ID: ${consejoId}`);
        
        if (consejoId) {
            abrirModal(consejoId);
        }
    }
}

// Abrir modal con detalles
function abrirModal(consejoId) {
    console.log(`🔓 Abriendo modal para consejo ID: ${consejoId}`);
    
    const consejo = consejos.find(c => c.id === consejoId);
    if (!consejo) {
        console.error('❌ Consejo no encontrado');
        return;
    }
    
    const modal = document.getElementById('tipModal');
    const modalBody = document.getElementById('modalBody');
    
    if (!modal || !modalBody) {
        console.error('❌ Modal o modalBody no encontrados');
        return;
    }
    
    const prioridad = consejo.prioridad.toLowerCase();
    const badges = {
        'high': { class: 'badge-high', text: 'ALTA PRIORIDAD' },
        'medium': { class: 'badge-medium', text: 'PRIORIDAD MEDIA' },
        'low': { class: 'badge-low', text: 'PRIORIDAD BAJA' }
    };
    const badge = badges[prioridad] || badges.medium;
    
    modalBody.innerHTML = `
        <div style="text-align: center; margin-bottom: 30px;">
            <span style="font-size: 4em;">${consejo.icono}</span>
        </div>
        <h2>${consejo.titulo}</h2>
        <span class="badge ${badge.class}">${badge.text}</span>
        <span class="category-badge" style="margin-left: 10px;">${consejo.categoria_nombre || 'General'}</span>
        <div style="margin-top: 30px;">
            ${consejo.contenido_completo || `<p>${consejo.descripcion}</p>`}
        </div>
    `;
    
    modal.classList.add('active');
    console.log('✅ Modal abierto');
    
    // Prevenir scroll del body
    document.body.style.overflow = 'hidden';
}

// Cerrar modal
function cerrarModal() {
    const modal = document.getElementById('tipModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        console.log('✅ Modal cerrado');
    }
}

// Configurar eventos globales (modal y búsqueda)
function configurarEventosGlobales() {
    const modal = document.getElementById('tipModal');
    const closeBtn = document.querySelector('.close-modal');
    
    // Botón de cerrar
    if (closeBtn) {
        closeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            cerrarModal();
        });
    }
    
    // Click fuera del modal
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                cerrarModal();
            }
        });
    }
    
    // Tecla Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            cerrarModal();
        }
    });
    
    // Buscar (si existe el input)
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            busqueda = e.target.value.toLowerCase();
            mostrarConsejos();
        });
    }
}

// Mostrar error
function mostrarError(mensaje) {
    const container = document.getElementById('consejosContainer');
    if (container) {
        container.innerHTML = `
            <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                <i class="fas fa-exclamation-triangle" style="font-size: 4em; color: #c62828; margin-bottom: 20px;"></i>
                <h3 style="color: var(--gray-medium);">Error al cargar</h3>
                <p style="color: var(--gray-medium);">${mensaje}</p>
                <button onclick="location.reload()" style="margin-top: 20px; padding: 12px 24px; background: var(--teal); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 1em;">
                    Recargar página
                </button>
            </div>
        `;
    }
}

// Exponer cerrarModal globalmente para el onclick en el HTML
window.cerrarModal = cerrarModal;

console.log('📄 Archivo alumno-consejos.js cargado correctamente');