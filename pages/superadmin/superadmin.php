<?php
include('../../config/db.php');
session_start();

// Verificar que sea superadmin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'superadmin') {
  header("Location: ../../login_register.php");
  exit;
}

// Obtener alumnos
$query = $conn->prepare("SELECT id, name, email, created_at FROM users WHERE user_type = 'user' ORDER BY id ASC");
$query->execute();
$alumnos = $query->fetchAll(PDO::FETCH_ASSOC);

// Obtener administradores
$admins_query = $conn->prepare("SELECT id, name, email, created_at FROM users WHERE user_type = 'admin' ORDER BY id ASC");
$admins_query->execute();
$admins = $admins_query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Superadmin Dashboard</title>
<style>
/* --- General --- */
body {
  margin: 0;
  font-family: "Poppins", sans-serif;
  display: flex;
  background: #EDE5D6;
}

.sidebar {
  width: 220px;
  background: #00837F;
  color: white;
  min-height: 100vh;
  padding: 20px;
}

.sidebar h2 {
  text-align: center;
  margin-bottom: 30px;
}

.sidebar ul {
  list-style: none;
  padding: 0;
}

.sidebar ul li {
  margin: 20px 0;
}

.sidebar ul li a {
  text-decoration: none;
  color: white;
  display: block;
  padding: 10px;
  border-radius: 8px;
  transition: 0.3s;
  cursor: pointer;
}

.sidebar ul li a:hover,
.sidebar ul li a.active {
  background: rgba(174, 135, 76, 0.25);
}

.main-content {
  flex: 1;
  padding: 20px;
  transition: all 0.4s ease;
}

header {
  background: #EDE5D6;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 20px;
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
  color: #00837F;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}

th, td {
  padding: 12px;
  border-bottom: 1px solid #D0D1D1;
  text-align: left;
  color: #7E8080;
}

th {
  background: #00837F;
  color: white;
}

.btn {
  padding: 6px 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  color: white;
  transition: 0.3s;
}

/* Botones */
.delete-btn { background-color: #AE874C; }
.delete-btn:hover { background-color: #5c0000ff; }

.add-btn { background-color: #00837F; margin-bottom: 10px; }
.add-btn:hover { background-color: #006963; }

.perm-btn { background-color: #7E8080; }
.perm-btn:hover { background-color: #006963; }

.close-btn { background-color: #D0D1D1; color: #7E8080; }
.close-btn:hover { background-color: #AE874C; color: #fff; }

/* Modal */
.modal {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.6);
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: #EDE5D6;
  padding: 20px;
  border-radius: 10px;
  width: 400px;
  max-height: 80vh;
  overflow-y: auto;
  color: #7E8080;
}

/* Sections */
section {
  display: none;
  opacity: 0;
  transform: translateY(10px);
  transition: opacity 0.4s ease, transform 0.4s ease;
}

section.active {
  display: block;
  opacity: 1;
  transform: translateY(0);
}

/* --- Neon Checkbox --- */
.neon-checkbox {
  --primary: #00837F;
  --primary-dark: #006963;
  --primary-light: #AE874C;
  --size: 28px;
  position: relative;
  width: var(--size);
  height: var(--size);
  cursor: pointer;
  display: inline-block;
}

.neon-checkbox input { display: none; }
.neon-checkbox__frame { position: relative; width: 100%; height: 100%; }

.neon-checkbox__box {
  position: absolute;
  inset: 0;
  background: #EDE5D6;
  border-radius: 4px;
  border: 2px solid var(--primary-dark);
  transition: all 0.3s ease;
}

.neon-checkbox__check-container {
  position: absolute;
  inset: 2px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.neon-checkbox__check {
  width: 80%;
  height: 80%;
  fill: none;
  stroke: var(--primary);
  stroke-width: 3;
  stroke-linecap: round;
  stroke-linejoin: round;
  stroke-dasharray: 40;
  stroke-dashoffset: 40;
  transform-origin: center;
  transition: all 0.3s ease;
}

.neon-checkbox__glow {
  position: absolute;
  inset: -2px;
  border-radius: 6px;
  background: var(--primary-light);
  opacity: 0;
  filter: blur(8px);
  transform: scale(1.2);
  transition: all 0.3s ease;
}

.neon-checkbox:hover .neon-checkbox__box {
  border-color: var(--primary-light);
  transform: scale(1.05);
}

.neon-checkbox input:checked ~ .neon-checkbox__frame .neon-checkbox__box {
  background: rgba(0,131,127,0.2);
  border-color: var(--primary);
}

.neon-checkbox input:checked ~ .neon-checkbox__frame .neon-checkbox__check {
  stroke-dashoffset: 0;
  transform: scale(1.1);
}

.neon-checkbox input:checked ~ .neon-checkbox__frame .neon-checkbox__glow {
  opacity: 0.3;
}
</style>
</head>
<body>
<div class="sidebar">
  <h2>Superadmin</h2>
  <ul>
    <li><a class="nav-link active" data-section="alumnos-section">Usuarios</a></li>
    <li><a class="nav-link" data-section="admins-section">Administradores</a></li>
    <li><a href="home_superadmin.php">Inicio</a></li>
    <li><a href="../../logout.php">Cerrar sesión</a></li>
  </ul>
</div>

<div class="main-content">
<header><h1>Panel de Control</h1></header>

<!-- SECCIÓN DE ALUMNOS -->
<section id="alumnos-section" class="active">
  <h2>Lista de Alumnos</h2>
  <input type="text" id="searchAlumnos" placeholder="Buscar alumno..." style="width:100%;padding:8px;margin-bottom:10px;border-radius:5px;border:1px solid #ccc;">
  <table>
    <thead>
      <tr>
        <th>ID</th><th>Nombre</th><th>Correo</th><th>Fecha de creación</th><th>Acción</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($alumnos): ?>
        <?php foreach ($alumnos as $alumno): ?>
          <tr id="user-<?= $alumno['id'] ?>">
            <td><?= htmlspecialchars($alumno['id']) ?></td>
            <td><?= htmlspecialchars($alumno['name']) ?></td>
            <td><?= htmlspecialchars($alumno['email']) ?></td>
            <td><?= htmlspecialchars($alumno['created_at']) ?></td>
            <td>
              <button class="btn delete-btn" onclick="eliminarUsuario(<?= $alumno['id'] ?>)">Eliminar</button>
              <button class="btn perm-btn" onclick="abrirEditarRol(<?= $alumno['id'] ?>, 'user')">Editar</button>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5">No hay alumnos registrados.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</section>

<!-- SECCIÓN DE ADMINISTRADORES -->
<section id="admins-section">
  <h2>Administradores</h2>
  <input type="text" id="searchAdmins" placeholder="Buscar administrador..." style="width:100%;padding:8px;margin-bottom:10px;border-radius:5px;border:1px solid #ccc;">
  <button class="btn add-btn" onclick="abrirModalAgregar()">+ Agregar Admin</button>
  <table>
    <thead>
      <tr>
        <th>ID</th><th>Nombre</th><th>Correo</th><th>Fecha de creación</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($admins): ?>
        <?php foreach ($admins as $admin): ?>
          <tr id="admin-<?= $admin['id'] ?>">
            <td><?= htmlspecialchars($admin['id']) ?></td>
            <td><?= htmlspecialchars($admin['name']) ?></td>
            <td><?= htmlspecialchars($admin['email']) ?></td>
            <td><?= htmlspecialchars($admin['created_at']) ?></td>
            <td>
              <button class="btn perm-btn" onclick="abrirPermisos(<?= $admin['id'] ?>)">Permisos</button>
              <button class="btn delete-btn" onclick="eliminarAdmin(<?= $admin['id'] ?>)">Eliminar</button>
              <button class="btn add-btn" onclick="abrirEditarRol(<?= $admin['id'] ?>, 'admin')">Editar</button>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5">No hay administradores registrados.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</section>
</div>

<!-- MODAL AGREGAR ADMIN -->
<div id="modalAgregar" class="modal">
  <div class="modal-content">
    <h3>Agregar Administrador</h3>
    <form id="formAddAdmin">
      <label>Nombre:</label><br>
      <input type="text" name="name" required style="width:100%;margin-bottom:10px;"><br>
      <label>Correo:</label><br>
      <input type="email" name="email" required style="width:100%;margin-bottom:10px;"><br>
      <label>Contraseña:</label><br>
      <input type="password" name="password" required style="width:100%;margin-bottom:10px;"><br>
      <div style="text-align:right;">
        <button type="button" class="btn close-btn" onclick="cerrarModalAgregar()">Cancelar</button>
        <button type="submit" class="btn add-btn">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL PERMISOS -->
<div id="modalPermisos" class="modal">
  <div class="modal-content">
    <h3>Permisos del administrador</h3>
    <div id="permisosContainer"></div>
    <div style="text-align:right;margin-top:10px;">
      <button class="btn close-btn" onclick="cerrarModalPermisos()">Cancelar</button>
      <button class="btn add-btn" onclick="guardarPermisos()">Guardar</button>
    </div>
  </div>
</div>

<!-- MODAL EDITAR ROL -->
<div id="modalEditarRol" class="modal">
  <div class="modal-content">
    <h3>Editar Rol de Usuario</h3>
    <form id="formEditarRol">
      <input type="hidden" name="id" id="editId">
      <label>Rol:</label><br>
      <select name="rol" id="editRol" style="width:100%;margin-bottom:10px;padding:5px;border-radius:5px;">
        <option value="user">Usuario</option>
        <option value="admin">Administrador</option>
      </select><br>
      <div style="text-align:right;">
        <button type="button" class="btn close-btn" onclick="cerrarModalEditarRol()">Cancelar</button>
        <button type="submit" class="btn add-btn">Guardar cambios</button>
      </div>
    </form>
  </div>
</div>

<script>
// --- Alternar secciones ---
const navLinks = document.querySelectorAll('.nav-link');
const sections = document.querySelectorAll('section');
navLinks.forEach(link=>{
  link.addEventListener('click', ()=>{
    navLinks.forEach(l=>l.classList.remove('active'));
    link.classList.add('active');
    sections.forEach(sec=>sec.classList.remove('active'));
    document.getElementById(link.dataset.section).classList.add('active');
  });
});

// --- Buscadores ---
document.getElementById('searchAlumnos').addEventListener('keyup', function() {
  const filter = this.value.toLowerCase();
  const rows = document.querySelectorAll('#alumnos-section tbody tr');
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(filter) ? '' : 'none';
  });
});

document.getElementById('searchAdmins').addEventListener('keyup', function() {
  const filter = this.value.toLowerCase();
  const rows = document.querySelectorAll('#admins-section tbody tr');
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(filter) ? '' : 'none';
  });
});

// --- Modal Agregar Admin ---
const modalAdd = document.getElementById('modalAgregar');
function abrirModalAgregar(){ modalAdd.style.display='flex'; }
function cerrarModalAgregar(){ modalAdd.style.display='none'; }

document.getElementById('formAddAdmin').addEventListener('submit', async e=>{
  e.preventDefault();
  const formData = new FormData(e.target);
  const resp = await fetch('create_admin.php',{method:'POST',body:formData});
  const data = await resp.json();
  alert(data.message);
  if(data.success) location.reload();
});

// --- Eliminar usuario ---
async function eliminarUsuario(id) {
  if(confirm("¿Seguro que deseas eliminar este usuario?")){
    const formData = new FormData();
    formData.append("id", id);
    const response = await fetch("eliminar_usuario.php",{method:"POST",body:formData});
    const result = await response.json();
    alert(result.message);
    if(result.success) location.reload();
  }
}

// --- Eliminar admin ---
async function eliminarAdmin(id) {
  if(!confirm("¿Seguro que deseas eliminar este administrador?")) return;
  const resp = await fetch("delete_admin.php?id="+id);
  const data = await resp.json();
  alert(data.message);
  if(data.success) location.reload();
}

// --- Permisos ---
const modalPerm = document.getElementById('modalPermisos');
let adminIdActual = null;

async function abrirPermisos(id){
  adminIdActual = id;
  const resp = await fetch('get_permisos.php?id='+id);
  const data = await resp.json();
  if(!data.success){ alert(data.message); return; }

  const cont = document.getElementById('permisosContainer');
  cont.innerHTML = '';
  for(const [key,label] of Object.entries(data.pages)){
    const perm = data.permisos.find(p=>p.page===key);
    const checked = perm && perm.allowed==1 ? 'checked' : '';
    cont.innerHTML += `
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
        <span>${label}</span>
        <label class="neon-checkbox">
          <input type="checkbox" data-page="${key}" ${checked}>
          <div class="neon-checkbox__frame">
            <div class="neon-checkbox__box">
              <div class="neon-checkbox__check-container">
                <svg viewBox="0 0 24 24" class="neon-checkbox__check">
                  <path d="M3,12.5l7,7L21,5"></path>
                </svg>
              </div>
              <div class="neon-checkbox__glow"></div>
            </div>
          </div>
        </label>
      </div>`;
  }
  modalPerm.style.display='flex';
}

function cerrarModalPermisos(){ modalPerm.style.display='none'; }

async function guardarPermisos(){
  const checks = document.querySelectorAll('#permisosContainer input[type="checkbox"]');
  const permisos = [];
  checks.forEach(ch=>permisos.push({page:ch.dataset.page, allowed:ch.checked?1:0}));
  const resp = await fetch('save_permisos.php',{
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify({admin_id:adminIdActual, permisos})
  });
  const data = await resp.json();
  alert(data.message);
  if(data.success) cerrarModalPermisos();
}

// --- Editar Rol ---
const modalEditar = document.getElementById('modalEditarRol');
const formEditar = document.getElementById('formEditarRol');

function abrirEditarRol(id, rolActual) {
  document.getElementById('editId').value = id;
  document.getElementById('editRol').value = rolActual;
  modalEditar.style.display = 'flex';
}

function cerrarModalEditarRol() {
  modalEditar.style.display = 'none';
}

formEditar.addEventListener('submit', async e => {
  e.preventDefault();
  const formData = new FormData(formEditar);
  const resp = await fetch('update_rol.php', {
    method: 'POST',
    body: formData
  });
  const data = await resp.json();
  alert(data.message);
  if (data.success) location.reload();
});

window.addEventListener('click', e=>{
  if(e.target===modalAdd) cerrarModalAgregar();
  if(e.target===modalPerm) cerrarModalPermisos();
  if(e.target===modalEditar) cerrarModalEditarRol();
});
</script>
</body>
</html>
