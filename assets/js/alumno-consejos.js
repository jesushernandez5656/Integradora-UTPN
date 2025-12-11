// Configuración de la API
const API_BASE_URL = '/INTEGRADORA-UTPN/pages/api/consejos-json.php?action=listar';
const API_CATEGORIAS_URL = '/INTEGRADORA-UTPN/pages/api/consejos-json.php?action=listar_categorias';
const API_ESTADISTICAS_URL = '/INTEGRADORA-UTPN/pages/api/consejos-json.php?action=estadisticas';

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
        
        // Cargar categorías, consejos y estadísticas en paralelo
        const [responseCategorias, responseConsejos, responseEstadisticas] = await Promise.all([
            fetch(API_CATEGORIAS_URL),
            fetch(API_BASE_URL),
            fetch(API_ESTADISTICAS_URL)
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
        
        // Procesar estadísticas
        const dataEstadisticas = await responseEstadisticas.json();
        if (dataEstadisticas.success && dataEstadisticas.data) {
            console.log('✅ Estadísticas cargadas desde API:', dataEstadisticas.data);
            actualizarEstadisticas(dataEstadisticas.data);
        } else {
            console.warn('⚠️ Estadísticas no disponibles desde API, calculando manualmente...');
            actualizarEstadisticas(calcularEstadisticasManualmente());
        }
        
    } catch (error) {
        console.error('💥 Error al cargar datos:', error);
        mostrarError('No se pudieron cargar los consejos. Por favor, recarga la página.');
    }
}

// Actualizar estadísticas dinámicamente
function actualizarEstadisticas(stats) {
    console.log('📊 Actualizando estadísticas en la UI...', stats);
    
    if (!stats || typeof stats !== 'object') {
        console.error('❌ Estadísticas inválidas:', stats);
        stats = calcularEstadisticasManualmente();
    }
    
    const totalConsejos = parseInt(stats.total_consejos) || calcularTotalConsejos();
    const totalCategorias = parseInt(stats.total_categorias) || categorias.length;
    
    console.log('📊 Valores finales a mostrar:', { consejos: totalConsejos, categorias: totalCategorias });
    
    const totalConsejosElement = document.getElementById('totalConsejos');
    if (totalConsejosElement) {
        animarNumero(totalConsejosElement, 0, totalConsejos, 1000);
    }
    
    const totalCategoriasElement = document.getElementById('totalCategorias');
    if (totalCategoriasElement) {
        animarNumero(totalCategoriasElement, 0, totalCategorias, 1000);
    }
    
    console.log('✅ Estadísticas actualizadas en la UI');
}

// Calcular estadísticas manualmente como fallback
function calcularEstadisticasManualmente() {
    return {
        total_consejos: calcularTotalConsejos(),
        total_categorias: categorias.length
    };
}

// Calcular total de consejos activos
function calcularTotalConsejos() {
    return consejos.filter(c => c.activo === 1).length;
}

// Animar números con efecto contador
function animarNumero(elemento, inicio, fin, duracion) {
    const rango = fin - inicio;
    const incremento = rango / (duracion / 16); // 60 FPS
    let actual = inicio;
    
    const timer = setInterval(() => {
        actual += incremento;
        
        if ((incremento > 0 && actual >= fin) || (incremento < 0 && actual <= fin)) {
            actual = fin;
            clearInterval(timer);
        }
        
        elemento.textContent = Math.round(actual);
    }, 16);
}

// Cargar categorías en el DOM
function cargarCategorias() {
    const categoryGrid = document.querySelector('.category-grid');
    if (!categoryGrid) return;
    
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
    
    const consejosFiltrados = consejos.filter(consejo => {
        const cumpleBusqueda = !busqueda || 
            consejo.titulo.toLowerCase().includes(busqueda) ||
            consejo.descripcion.toLowerCase().includes(busqueda);
            
        const cumpleCategoria = filtroCategoria === 'todos' || 
            consejo.categoria_id.toString() === filtroCategoria;
            
        return cumpleBusqueda && cumpleCategoria && consejo.activo === 1;
    });
    
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
    
    container.innerHTML = '';
    consejosFiltrados.forEach((consejo, index) => {
        const cardHTML = crearTarjetaConsejo(consejo, index);
        container.innerHTML += cardHTML;
    });
    
    // Animar las tarjetas
    animarTarjetas();
    
    // Configurar eventos usando event delegation
    configurarEventosTarjetas();
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
        <div class="tip-card" style="animation-delay: ${index * 0.1}s; opacity: 0;">
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
    const cards = document.querySelectorAll('.tip-card');
    cards.forEach(card => {
        card.style.opacity = '1';
    });
}

// Configurar eventos de las tarjetas usando Event Delegation
function configurarEventosTarjetas() {
    console.log('🔧 Configurando eventos de tarjetas...');
    
    const container = document.getElementById('consejosContainer');
    if (!container) return;
    
    container.removeEventListener('click', handleReadMoreClick);
    container.addEventListener('click', handleReadMoreClick);
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
        <div class="modal-header">
            <span class="modal-icon">${consejo.icono}</span>
            <h2>${consejo.titulo}</h2>
        </div>
        <div class="modal-badges">
            <span class="badge ${badge.class}">${badge.text}</span>
            <span class="category-badge">${consejo.categoria_nombre || 'General'}</span>
        </div>
        <div class="modal-content-body">
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