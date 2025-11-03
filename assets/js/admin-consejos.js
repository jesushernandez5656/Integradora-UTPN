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
    cargarCategorias();
    cargarConsejos();
    cargarEstadisticas();
    configurarEventListeners();
});

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
            cargarConsejos();
            cargarEstadisticas();
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
    const consejo = consejos.find(c => c.id === id);
    if (!consejo) return;

    isEditing = true;
    editingId = id;

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
    deleteTarget = id;
    deleteType = 'consejo';
    const consejo = consejos.find(c => c.id === id);
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
                cargarConsejos();
            } else {
                cargarCategorias();
            }
            cargarEstadisticas();
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
            cargarCategorias();
            cargarEstadisticas();
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
    const category = categorias.find(c => c.id === id);
    if (!category) return;

    document.getElementById('categoryId').value = category.id;
    document.getElementById('categoryNombre').value = category.nombre;
    document.getElementById('categoryIcono').value = category.icono;
    document.getElementById('categoryDescripcion').value = category.descripcion || '';
    document.getElementById('categoryModalTitle').textContent = 'Editar Categoría';

    openModal('createCategoryModal');
}

function deleteCategory(id) {
    const category = categorias.find(c => c.id === id);
    if (category.total_consejos > 0) {
        mostrarAlerta('No se puede eliminar una categoría con consejos asociados', 'danger');
        return;
    }

    deleteTarget = id;
    deleteType = 'category';
    document.getElementById('deleteMessage').textContent = 
        `¿Estás seguro de eliminar la categoría "${category.nombre}"? Esta acción no se puede deshacer.`;
    openModal('deleteModal');
}

// ==================== FUNCIONES DE UI ====================

function loadConsejos() {
    const tbody = document.getElementById('consejosTableBody');

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

    tbody.innerHTML = consejos.map(consejo => `
        <tr>
            <td><strong>#${consejo.id}</strong></td>
            <td>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 1.5em;">${consejo.icono}</span>
                    <strong>${consejo.titulo}</strong>
                </div>
            </td>
            <td><span class="badge badge-${consejo.categoria}">${consejo.categoria_nombre || getCategoryName(consejo.categoria)}</span></td>
            <td><span class="badge badge-${consejo.prioridad}">${getPriorityName(consejo.prioridad)}</span></td>
            <td>${formatDate(consejo.fecha)}</td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn-warning btn-sm" onclick="editConsejo(${consejo.id})" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="deleteConsejo(${consejo.id})" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
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
                        <strong>${cat.nombre}</strong>
                        ${cat.descripcion ? `<br><small style="color: var(--gray-medium);">${cat.descripcion}</small>` : ''}
                    </div>
                </div>
            </td>
            <td><span style="font-size: 1.5em;">${cat.icono}</span></td>
            <td><span class="badge badge-${cat.slug}">${cat.total_consejos} consejos</span></td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn-warning btn-sm" onclick="editCategory(${cat.id})" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="deleteCategory(${cat.id})" ${cat.total_consejos > 0 ? 'disabled title="No se puede eliminar con consejos asociados"' : ''}>
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
        categorias.map(cat => `<option value="${cat.id}">${cat.icono} ${cat.nombre}</option>`).join('');
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
    const date = new Date(dateString);
    return date.toLocaleDateString('es-MX', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}