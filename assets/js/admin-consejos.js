// ==================== CONFIGURACIÓN ====================
const API_BASE_URL = '/INTEGRADORA-UTPN/pages/api/consejos-json.php';

// ==================== ESTADO GLOBAL ====================
let consejos = [];
let categorias = [];
let deleteTarget = null;
let deleteType = null;
let isEditing = false;
let editingId = null;

// ==================== INICIALIZACIÓN ====================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Inicializando administración de consejos...');
    inicializarSistema();
});

function inicializarSistema() {
    cargarCategorias();
    cargarConsejos();
    cargarEstadisticas();
    configurarEventListeners();
    configurarEventDelegation();
    configurarClicsMoviles(); // ⭐ NUEVO: Configuración específica para móvil
}

// ==================== EVENT DELEGATION ROBUSTO ====================

function configurarEventDelegation() {
    console.log('🔧 Configurando event delegation robusto...');
    
    // Event delegation para la tabla de consejos
    const consejosTable = document.getElementById('consejosTable');
    if (consejosTable) {
        consejosTable.addEventListener('click', manejarClickConsejos);
    }
    
    // Event delegation para la tabla de categorías
    const categoriasTable = document.getElementById('categoriasTable');
    if (categoriasTable) {
        categoriasTable.addEventListener('click', manejarClickCategorias);
    }
}

function manejarClickConsejos(event) {
    const target = event.target;
    console.log('🖱️ Click en tabla consejos:', target);
    
    // Detectar clic en CUALQUIER parte del botón de editar (botón o icono)
    const elementoEditar = target.closest('.btn-warning');
    if (elementoEditar) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        
        console.log('✅ Click detectado en botón EDITAR');
        
        // Obtener el ID de múltiples formas
        let id = elementoEditar.getAttribute('data-id');
        console.log('📋 data-id:', id);
        
        if (!id) {
            // Intentar extraer del onclick
            const onclickAttr = elementoEditar.getAttribute('onclick');
            console.log('📋 onclick attribute:', onclickAttr);
            if (onclickAttr) {
                const match = onclickAttr.match(/editConsejo\((\d+)\)/);
                id = match ? match[1] : null;
                console.log('📋 ID extraído de onclick:', id);
            }
        }
        
        if (!id) {
            // Último recurso: buscar en el texto del botón o elementos cercanos
            const fila = elementoEditar.closest('tr');
            if (fila) {
                const primeraCelda = fila.cells[0];
                if (primeraCelda) {
                    const match = primeraCelda.textContent.match(/#(\d+)/);
                    id = match ? match[1] : null;
                    console.log('📋 ID extraído de texto:', id);
                }
            }
        }
        
        if (id) {
            console.log('✏️ Editando consejo ID:', id);
            editConsejo(id);
        } else {
            console.error('❌ No se pudo obtener el ID del consejo');
        }
        return;
    }
    
    // Detectar clic en CUALQUIER parte del botón de eliminar (botón o icono)
    const elementoEliminar = target.closest('.btn-danger');
    if (elementoEliminar && !elementoEliminar.disabled) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        
        console.log('✅ Click detectado en botón ELIMINAR');
        
        // Obtener el ID de múltiples formas
        let id = elementoEliminar.getAttribute('data-id');
        console.log('📋 data-id:', id);
        
        if (!id) {
            // Intentar extraer del onclick
            const onclickAttr = elementoEliminar.getAttribute('onclick');
            console.log('📋 onclick attribute:', onclickAttr);
            if (onclickAttr) {
                const match = onclickAttr.match(/deleteConsejo\((\d+)\)/);
                id = match ? match[1] : null;
                console.log('📋 ID extraído de onclick:', id);
            }
        }
        
        if (!id) {
            // Último recurso: buscar en el texto del botón o elementos cercanos
            const fila = elementoEliminar.closest('tr');
            if (fila) {
                const primeraCelda = fila.cells[0];
                if (primeraCelda) {
                    const match = primeraCelda.textContent.match(/#(\d+)/);
                    id = match ? match[1] : null;
                    console.log('📋 ID extraído de texto:', id);
                }
            }
        }
        
        if (id) {
            console.log('🗑️ Eliminando consejo ID:', id);
            deleteConsejo(id);
        } else {
            console.error('❌ No se pudo obtener el ID del consejo');
        }
        return;
    }
}

function manejarClickCategorias(event) {
    const target = event.target;
    
    const botonEditar = target.closest('.btn-warning');
    const botonEliminar = target.closest('.btn-danger');
    
    if (botonEditar && !botonEditar.disabled) {
        event.preventDefault();
        event.stopPropagation();
        
        let id = botonEditar.getAttribute('data-id');
        if (!id) {
            const onclickAttr = botonEditar.getAttribute('onclick');
            if (onclickAttr) {
                const match = onclickAttr.match(/editCategory\((\d+)\)/);
                id = match ? match[1] : null;
            }
        }
        
        if (id) editCategory(id);
        return;
    }
    
    if (botonEliminar && !botonEliminar.disabled) {
        event.preventDefault();
        event.stopPropagation();
        
        let id = botonEliminar.getAttribute('data-id');
        if (!id) {
            const onclickAttr = botonEliminar.getAttribute('onclick');
            if (onclickAttr) {
                const match = onclickAttr.match(/deleteCategory\((\d+)\)/);
                id = match ? match[1] : null;
            }
        }
        
        if (id) deleteCategory(id);
        return;
    }
}

// ⭐ NUEVA FUNCIÓN: Configuración específica para móvil
function configurarClicsMoviles() {
    if (window.innerWidth <= 768) {
        console.log('📱 Configurando clics específicos para móvil...');
        
        const botonesEliminar = document.querySelectorAll('.btn-danger:not([disabled])');
        botonesEliminar.forEach((btn, index) => {
            console.log(`📱 Configurando botón eliminar móvil ${index + 1}`);
            
            // Agregar event listener directo como respaldo
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                console.log('📱 Click directo en botón eliminar móvil');
                
                let id = this.getAttribute('data-id');
                if (!id) {
                    const onclickAttr = this.getAttribute('onclick');
                    if (onclickAttr) {
                        const match = onclickAttr.match(/deleteConsejo\((\d+)\)/);
                        id = match ? match[1] : null;
                    }
                }
                
                if (id) {
                    console.log('📱 Eliminando desde móvil ID:', id);
                    deleteConsejo(id);
                }
            }, { passive: false });
        });
    }
}

// ==================== CONFIGURAR EVENT LISTENERS ====================
function configurarEventListeners() {
    // Cerrar modales con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarTodosLosModales();
        }
    });

    // Cerrar modal al hacer clic fuera
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });

    // Prevenir Enter en formularios excepto textarea
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
            }
        });
    });
}

// ==================== FUNCIONES DE API ====================

async function cargarCategorias() {
    try {
        const response = await fetch(`${API_BASE_URL}?action=listar_categorias`);
        const result = await response.json();

        if (result.success) {
            categorias = result.data;
            console.log('📂 Categorías cargadas:', categorias.length);
            actualizarSelectCategorias();
            loadCategorias();
        } else {
            mostrarAlerta('Error al cargar categorías: ' + result.error, 'danger');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error de conexión al cargar categorías', 'danger');
    }
}

async function cargarConsejos() {
    try {
        const response = await fetch(`${API_BASE_URL}?action=listar`);
        const result = await response.json();

        if (result.success) {
            consejos = result.data;
            console.log('📝 Consejos cargados:', consejos.length);
            loadConsejos();
        } else {
            mostrarAlerta('Error al cargar consejos: ' + result.error, 'danger');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error de conexión al cargar consejos', 'danger');
    }
}

async function cargarEstadisticas() {
    try {
        const response = await fetch(`${API_BASE_URL}?action=estadisticas`);
        const result = await response.json();

        if (result.success) {
            updateStats(result.data);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function saveConsejo(event) {
    event.preventDefault();

    const formData = {
        titulo: document.getElementById('titulo').value.trim(),
        categoria_id: document.getElementById('categoria').value,
        prioridad: document.getElementById('prioridad').value,
        icono: document.getElementById('icono').value.trim() || '📌',
        descripcion_corta: document.getElementById('descripcion').value.trim(),
        contenido_completo: document.getElementById('contenidoCompleto').value.trim()
    };

    if (!formData.titulo || !formData.categoria_id || !formData.prioridad || !formData.descripcion_corta || !formData.contenido_completo) {
        mostrarAlerta('Por favor completa todos los campos requeridos', 'warning');
        return;
    }

    try {
        let response;
        if (isEditing && editingId) {
            response = await fetch(`${API_BASE_URL}?action=actualizar&id=${editingId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
        } else {
            response = await fetch(`${API_BASE_URL}?action=crear`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
        }

        const result = await response.json();

        if (result.success) {
            mostrarAlerta(isEditing ? 'Consejo actualizado exitosamente' : 'Consejo creado exitosamente', 'success');
            closeModal('createModal');
            await cargarConsejos();
            await cargarEstadisticas();
            resetForm();
        } else {
            mostrarAlerta('Error: ' + result.error, 'danger');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error de conexión al guardar', 'danger');
    }
}

function editConsejo(id) {
    console.log('🔧 Editando consejo ID:', id);
    
    const numericId = parseInt(id);
    const consejo = consejos.find(c => c.id == numericId);
    
    if (!consejo) {
        console.error('❌ Consejo no encontrado:', id);
        mostrarAlerta('Error: Consejo no encontrado', 'danger');
        return;
    }

    isEditing = true;
    editingId = numericId;

    document.getElementById('consejoId').value = consejo.id;
    document.getElementById('titulo').value = consejo.titulo;
    document.getElementById('categoria').value = consejo.categoria_id;
    document.getElementById('prioridad').value = consejo.prioridad;
    document.getElementById('icono').value = consejo.icono;
    document.getElementById('descripcion').value = consejo.descripcion;
    document.getElementById('contenidoCompleto').value = consejo.contenido_completo;
    document.getElementById('modalTitle').textContent = 'Editar Consejo';

    openModal('createModal');
}

function deleteConsejo(id) {
    console.log('🔧 Eliminando consejo ID:', id);
    
    const numericId = parseInt(id);
    const consejo = consejos.find(c => c.id == numericId);
    
    if (!consejo) {
        console.error('❌ Consejo no encontrado:', id);
        mostrarAlerta('Error: Consejo no encontrado', 'danger');
        return;
    }

    deleteTarget = numericId;
    deleteType = 'consejo';
    document.getElementById('deleteMessage').textContent = 
        `¿Estás seguro de eliminar el consejo "${consejo.titulo}"? Esta acción no se puede deshacer.`;
    openModal('deleteModal');
}

async function confirmDelete() {
    if (!deleteTarget || !deleteType) return;

    try {
        let response;
        if (deleteType === 'consejo') {
            response = await fetch(`${API_BASE_URL}?action=eliminar&id=${deleteTarget}`, {
                method: 'DELETE'
            });
        } else if (deleteType === 'category') {
            response = await fetch(`${API_BASE_URL}?action=eliminar_categoria&id=${deleteTarget}`, {
                method: 'DELETE'
            });
        }

        const result = await response.json();

        if (result.success) {
            mostrarAlerta(deleteType === 'consejo' ? 'Consejo eliminado exitosamente' : 'Categoría eliminada exitosamente', 'success');
            closeModal('deleteModal');
            
            if (deleteType === 'consejo') {
                await cargarConsejos();
            } else {
                await cargarCategorias();
            }
            await cargarEstadisticas();
        } else {
            mostrarAlerta('Error: ' + result.error, 'danger');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error de conexión al eliminar', 'danger');
    }

    deleteTarget = null;
    deleteType = null;
}

async function saveCategory(event) {
    event.preventDefault();

    const id = document.getElementById('categoryId').value;
    const formData = {
        nombre: document.getElementById('categoryNombre').value.trim(),
        icono: document.getElementById('categoryIcono').value.trim(),
        descripcion: document.getElementById('categoryDescripcion').value.trim()
    };

    if (!formData.nombre || !formData.icono) {
        mostrarAlerta('Nombre e ícono son requeridos', 'warning');
        return;
    }

    try {
        let response;
        if (id) {
            response = await fetch(`${API_BASE_URL}?action=actualizar_categoria&id=${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
        } else {
            response = await fetch(`${API_BASE_URL}?action=crear_categoria`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
        }

        const result = await response.json();

        if (result.success) {
            mostrarAlerta(id ? 'Categoría actualizada exitosamente' : 'Categoría creada exitosamente', 'success');
            closeModal('createCategoryModal');
            await cargarCategorias();
            await cargarEstadisticas();
            resetCategoryForm();
        } else {
            mostrarAlerta('Error: ' + result.error, 'danger');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error de conexión al guardar categoría', 'danger');
    }
}

function editCategory(id) {
    const category = categorias.find(c => c.id == id);
    if (!category) return;

    document.getElementById('categoryId').value = category.id;
    document.getElementById('categoryNombre').value = category.nombre;
    document.getElementById('categoryIcono').value = category.icono;
    document.getElementById('categoryDescripcion').value = category.descripcion || '';
    document.getElementById('categoryModalTitle').textContent = 'Editar Categoría';

    openModal('createCategoryModal');
}

function deleteCategory(id) {
    const category = categorias.find(c => c.id == id);
    if (!category) return;
    
    if (category.total_consejos > 0) {
        // Mostrar mensaje informativo
        mostrarAlertaModal(
            `No se puede eliminar la categoría "${category.nombre}"`,
            `Esta categoría tiene <strong>${category.total_consejos} consejo(s)</strong> asociado(s).<br><br>Primero debes eliminar o mover estos consejos a otra categoría.`,
            'warning',
            [
                {
                    text: 'Entendido',
                    class: 'btn-secondary',
                    action: function() {
                        closeModal('infoModal');
                    }
                },
                {
                    text: 'Ver Consejos',
                    class: 'btn-primary',
                    action: function() {
                        closeModal('infoModal');
                        switchTab('consejos');
                        // Filtrar consejos por esta categoría
                        const searchInput = document.getElementById('searchConsejos');
                        if (searchInput) {
                            searchInput.value = category.nombre;
                            filterConsejos();
                        }
                    }
                }
            ]
        );
        return;
    }

    deleteTarget = id;
    deleteType = 'category';
    document.getElementById('deleteMessage').textContent = 
        `¿Estás seguro de eliminar la categoría "${category.nombre}"? Esta acción no se puede deshacer.`;
    openModal('deleteModal');
}

// ==================== FUNCIONES NUEVAS ====================

function mostrarAlertaModal(titulo, mensaje, tipo = 'warning', botones = []) {
    // Cerrar modal si ya existe
    const existingModal = document.querySelector('[id^="infoModal-"]');
    if (existingModal) {
        closeModal(existingModal.id);
        setTimeout(() => existingModal.remove(), 300);
    }
    
    // Crear modal dinámico
    const modalId = 'infoModal-' + Date.now();
    const modalHTML = `
        <div id="${modalId}" class="modal active">
            <div class="modal-dialog" style="max-width: 500px;">
                <div class="modal-header">
                    <h2>
                        <i class="fas fa-${tipo === 'warning' ? 'exclamation-triangle' : 
                                          tipo === 'danger' ? 'exclamation-circle' : 
                                          tipo === 'success' ? 'check-circle' : 'info-circle'}"></i>
                        ${titulo}
                    </h2>
                    <button class="modal-close" onclick="closeModal('${modalId}')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-${tipo}">
                        <div>
                            ${mensaje}
                        </div>
                    </div>
                    
                    <div class="info-detalle" style="margin-top: 20px; padding: 15px; background: var(--cream); border-radius: 8px;">
                        <h4 style="color: var(--teal); margin-bottom: 10px;">
                            <i class="fas fa-info-circle"></i> ¿Qué puedes hacer?
                        </h4>
                        <ul style="margin: 0; padding-left: 20px;">
                            <li>Eliminar todos los consejos de esta categoría primero</li>
                            <li>Editar los consejos para moverlos a otra categoría</li>
                            <li>Crear una nueva categoría y reasignar los consejos</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    ${botones.length > 0 ? 
                        botones.map(btn => `
                            <button type="button" class="btn ${btn.class}" onclick="closeModal('${modalId}'); ${btn.action.toString().replace('function() {', '').replace('}', '')}">
                                ${btn.text}
                            </button>
                        `).join('') : 
                        `<button type="button" class="btn btn-secondary" onclick="closeModal('${modalId}')">Cerrar</button>`
                    }
                </div>
            </div>
        </div>
    `;
    
    // Agregar al body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Configurar cierre al hacer clic fuera
    const modal = document.getElementById(modalId);
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal(modalId);
        }
    });
    
    document.body.style.overflow = 'hidden';
}

function mostrarAdvertenciaEliminar(id) {
    const category = categorias.find(c => c.id == id);
    if (!category) return;
    
    // Vibrar en dispositivos móviles (si está disponible)
    if (navigator.vibrate && window.innerWidth <= 768) {
        navigator.vibrate([100, 50, 100]);
    }
    
    // Para móvil, mostrar toast
    if (window.innerWidth <= 768) {
        const toast = document.createElement('div');
        toast.id = 'mobile-toast-' + Date.now();
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            left: 10px;
            right: 10px;
            background: var(--warning);
            color: #856404;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 10001;
            text-align: center;
            font-weight: bold;
            animation: slideDownMobile 0.3s;
        `;
        
        toast.innerHTML = `
            <i class="fas fa-exclamation-circle" style="margin-right: 10px;"></i>
            <div style="font-size: 14px; line-height: 1.4;">
                <strong>No se puede eliminar</strong><br>
                Tiene ${category.total_consejos} consejo(s) asociados
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Auto-eliminar después de 4 segundos
        setTimeout(() => {
            toast.style.animation = 'slideUpMobile 0.3s';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 300);
        }, 4000);
    }
    
    // Mostrar modal informativo
    setTimeout(() => {
        mostrarAlertaModal(
            `Acción no permitida`,
            `La categoría <strong>"${category.nombre}"</strong> tiene <strong>${category.total_consejos} consejo(s)</strong> asociados.<br><br>
            <small>Para eliminar la categoría, primero debes:</small>`,
            'warning',
            [
                {
                    text: 'Eliminar consejos',
                    class: 'btn-danger',
                    action: function() {
                        switchTab('consejos');
                        // Filtrar por esta categoría
                        const searchInput = document.getElementById('searchConsejos');
                        if (searchInput) {
                            searchInput.value = category.nombre;
                            filterConsejos();
                        }
                    }
                },
                {
                    text: 'Crear nueva categoría',
                    class: 'btn-primary',
                    action: function() {
                        openModal('createCategoryModal');
                    }
                }
            ]
        );
    }, window.innerWidth <= 768 ? 500 : 0);
}

// ==================== FUNCIONES DE UI ====================

function loadConsejos() {
    const tbody = document.getElementById('consejosTableBody');
    console.log('🔧 Cargando consejos en la tabla...');

    if (consejos.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>No hay consejos registrados</h3>
                        <p>Comienza agregando tu primer consejo de seguridad</p>
                        <button class="btn btn-primary" onclick="openModal('createModal')" style="margin-top: 15px;">
                            <i class="fas fa-plus"></i> Crear Primer Consejo
                        </button>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    // ⭐ USAR DATA-ATTRIBUTES Y ONCLICK COMO FALLBACK
    tbody.innerHTML = consejos.map(consejo => `
        <tr>
            <td><strong>#${consejo.id}</strong></td>
            <td>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 1.5em;">${consejo.icono}</span>
                    <strong>${escapeHtml(consejo.titulo)}</strong>
                </div>
            </td>
            <td><span class="badge badge-${consejo.categoria}">${escapeHtml(consejo.categoria_nombre || getCategoryName(consejo.categoria))}</span></td>
            <td><span class="badge badge-${consejo.prioridad}">${getPriorityName(consejo.prioridad)}</span></td>
            <td>${formatDate(consejo.fecha)}</td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn-warning btn-sm" 
                            data-id="${consejo.id}"
                            onclick="editConsejo(${consejo.id})"
                            title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" 
                            data-id="${consejo.id}"
                            onclick="deleteConsejo(${consejo.id})"
                            title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    console.log('✅ Tabla de consejos actualizada');
    
    // ⭐ RECONFIGURAR EVENTOS PARA MÓVIL DESPUÉS DE CARGAR
    setTimeout(configurarClicsMoviles, 100);
}

function loadCategorias() {
    const tbody = document.getElementById('categoriasTableBody');

    if (categorias.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5">
                    <div class="empty-state">
                        <i class="fas fa-tags"></i>
                        <h3>No hay categorías registradas</h3>
                        <p>Crea categorías para organizar los consejos</p>
                        <button class="btn btn-primary" onclick="openModal('createCategoryModal')" style="margin-top: 15px;">
                            <i class="fas fa-plus"></i> Crear Primera Categoría
                        </button>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = categorias.map(cat => `
        <tr>
            <td><strong>#${cat.id}</strong></td>
            <td>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 1.5em;">${cat.icono}</span>
                    <div>
                        <strong>${escapeHtml(cat.nombre)}</strong>
                        ${cat.descripcion ? `<br><small style="color: var(--gray-medium);">${escapeHtml(cat.descripcion)}</small>` : ''}
                    </div>
                </div>
            </td>
            <td><span style="font-size: 1.5em;">${cat.icono}</span></td>
            <td><span class="badge badge-${cat.slug}">${cat.total_consejos} consejos</span></td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn-warning btn-sm" 
                            data-id="${cat.id}"
                            onclick="editCategory(${cat.id})"
                            title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" 
                            data-id="${cat.id}"
                            onclick="${cat.total_consejos > 0 ? 'mostrarAdvertenciaEliminar(' + cat.id + ')' : 'deleteCategory(' + cat.id + ')'}"
                            ${cat.total_consejos > 0 ? 'title="No se puede eliminar (tiene consejos)" style="cursor: not-allowed; opacity: 0.7;"' : 'title="Eliminar"'}>
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function updateStats(stats) {
    document.getElementById('totalConsejos').textContent = stats.total_consejos;
    document.getElementById('consejosActivos').textContent = stats.total_consejos;
    document.getElementById('totalCategorias').textContent = stats.total_categorias;
}

function actualizarSelectCategorias() {
    const select = document.getElementById('categoria');
    select.innerHTML = '<option value="">Seleccionar categoría</option>' +
        categorias.map(cat => `<option value="${cat.id}">${cat.icono} ${escapeHtml(cat.nombre)}</option>`).join('');
}

function filterConsejos() {
    const searchTerm = document.getElementById('searchConsejos').value.toLowerCase();
    const rows = document.querySelectorAll('#consejosTableBody tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    document.getElementById(tabName + '-tab').classList.add('active');
    event.target.classList.add('active');
}

function openModal(modalId) {
    document.getElementById(modalId).classList.add('active');
    document.body.style.overflow = 'hidden';

    if (modalId === 'createModal' && !isEditing) {
        resetForm();
    }

    if (modalId === 'createCategoryModal' && !document.getElementById('categoryId').value) {
        resetCategoryForm();
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
    document.body.style.overflow = 'auto';

    if (modalId === 'createModal') {
        resetForm();
    }
    if (modalId === 'createCategoryModal') {
        resetCategoryForm();
    }
}

function cerrarTodosLosModales() {
    document.querySelectorAll('.modal.active').forEach(modal => {
        modal.classList.remove('active');
    });
    document.body.style.overflow = 'auto';
}

function resetForm() {
    document.getElementById('consejoForm').reset();
    document.getElementById('consejoId').value = '';
    document.getElementById('modalTitle').textContent = 'Nuevo Consejo de Seguridad';
    isEditing = false;
    editingId = null;
}

function resetCategoryForm() {
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryModalTitle').textContent = 'Nueva Categoría';
}

function mostrarAlerta(mensaje, tipo) {
    const alertContainer = document.getElementById('alertContainer');
    const alertId = 'alert-' + Date.now();

    const alert = document.createElement('div');
    alert.id = alertId;
    alert.className = `alert alert-${tipo}`;
    alert.innerHTML = `
        <i class="fas fa-${tipo === 'success' ? 'check-circle' : tipo === 'danger' ? 'exclamation-circle' : tipo === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
        <span>${mensaje}</span>
    `;

    alertContainer.appendChild(alert);

    setTimeout(() => {
        const alertElement = document.getElementById(alertId);
        if (alertElement) {
            alertElement.style.animation = 'slideUp 0.3s reverse';
            setTimeout(() => alertElement.remove(), 300);
        }
    }, 5000);
}

// ==================== FUNCIONES HELPER ====================

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getCategoryName(categoria) {
    const names = {
        'contrasenas': 'Contraseñas',
        'phishing': 'Phishing',
        'redes-sociales': 'Redes Sociales',
        'wifi': 'Redes WiFi',
        'dispositivos': 'Dispositivos'
    };
    return names[categoria] || categoria;
}

function getPriorityName(prioridad) {
    const names = {
        'high': 'Alta',
        'medium': 'Media',
        'low': 'Baja'
    };
    return names[prioridad] || prioridad;
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-MX', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// ==================== DEBUG Y UTILIDADES ====================

// Función para debug específico de móvil
function debugMobile() {
    if (window.innerWidth <= 768) {
        console.log('=== 📱 DEBUG MÓVIL ===');
        const botonesEliminar = document.querySelectorAll('.btn-danger');
        
        console.log(`Botones eliminar encontrados: ${botonesEliminar.length}`);
        
        botonesEliminar.forEach((btn, i) => {
            const rect = btn.getBoundingClientRect();
            console.log(`Botón eliminar ${i}:`, {
                dataId: btn.getAttribute('data-id'),
                onclick: btn.getAttribute('onclick'),
                disabled: btn.disabled,
                tamaño: `${rect.width}x${rect.height}`,
                posición: `(${rect.x}, ${rect.y})`,
                visible: rect.width > 0 && rect.height > 0
            });
        });
    }
}

// Ejecutar debug después de cargar
setTimeout(debugMobile, 1500);

// Función para forzar la recreación de event listeners si es necesario
function reinicializarEventos() {
    console.log('🔄 Reinicializando eventos...');
    configurarEventDelegation();
    configurarClicsMoviles();
}