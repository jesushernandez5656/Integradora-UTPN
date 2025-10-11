<?php
include('../../config/db.php');

// Obtener alumnos
$query = $conn->prepare("SELECT id, name, email, created_at FROM users WHERE user_type = 'user' ORDER BY id ASC");
$query->execute();
$alumnos = $query->fetchAll(PDO::FETCH_ASSOC);

// Obtener administradores
$query2 = $conn->prepare("SELECT id, name, email, created_at FROM users WHERE user_type = 'admin' ORDER BY id ASC");
$query2->execute();
$admins = $query2->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Superadmin Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      display: flex;
      background: #f4f6f9;
    }

    .sidebar {
      width: 220px;
      background: #4facfe;
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
    }

    .sidebar ul li a:hover, .sidebar ul li a.active {
      background: rgba(255, 255, 255, 0.2);
    }

    .main-content {
      flex: 1;
      padding: 20px;
    }

    header {
      background: #fff;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
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
      border-bottom: 1px solid #eee;
      text-align: left;
    }

    th {
      background: #4facfe;
      color: white;
    }

    .btn {
      padding: 6px 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      color: white;
    }

    .delete-btn {
      background-color: #d63031;
    }

    .delete-btn:hover {
      background-color: #c0392b;
    }

    .add-btn {
      background-color: #0984e3;
      margin-bottom: 15px;
    }

    .add-btn:hover {
      background-color: #0873c4;
    }

    /* Ocultar secciones */
    .hidden {
      display: none;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: white;
      padding: 20px;
      border-radius: 10px;
      width: 400px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    .modal-content h3 {
      margin-top: 0;
    }

    .modal-content input {
      width: 100%;
      padding: 8px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .close {
      float: right;
      cursor: pointer;
      color: red;
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <h2>Superadmin</h2>
    <ul>
      <li><a href="#" class="active" onclick="mostrarSeccion('alumnos')">Usuarios</a></li>
      <li><a href="#" onclick="mostrarSeccion('admins')">Administradores</a></li>
      <li><a href="home_superadmin.php">Inicio</a></li>
      <li><a href="../../logout.php">Cerrar sesión</a></li>
    </ul>
  </div>

  <div class="main-content">
    <header>
      <h1>Panel de Control</h1>
    </header>

    <!-- Sección de Alumnos -->
    <section id="seccion-alumnos">
      <h2>Lista de Alumnos</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Fecha de creación</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($alumnos): ?>
            <?php foreach ($alumnos as $alumno): ?>
              <tr>
                <td><?= htmlspecialchars($alumno['id']) ?></td>
                <td><?= htmlspecialchars($alumno['name']) ?></td>
                <td><?= htmlspecialchars($alumno['email']) ?></td>
                <td><?= htmlspecialchars($alumno['created_at']) ?></td>
                <td>
                  <button class="btn delete-btn" onclick="eliminarUsuario(<?= $alumno['id'] ?>)">Eliminar</button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5">No hay alumnos registrados.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

    <!-- Sección de Administradores -->
    <section id="seccion-admins" class="hidden">
      <h2>Lista de Administradores</h2>
      <button class="btn add-btn" onclick="abrirModal()">+ Añadir Administrador</button>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Fecha de creación</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($admins): ?>
            <?php foreach ($admins as $admin): ?>
              <tr>
                <td><?= htmlspecialchars($admin['id']) ?></td>
                <td><?= htmlspecialchars($admin['name']) ?></td>
                <td><?= htmlspecialchars($admin['email']) ?></td>
                <td><?= htmlspecialchars($admin['created_at']) ?></td>
                <td><button class="btn delete-btn" onclick="eliminarUsuario(<?= $admin['id'] ?>)">Eliminar</button></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5">No hay administradores registrados.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>
  </div>

  <!-- Modal -->
  <div class="modal" id="modalAgregar">
    <div class="modal-content">
      <span class="close" onclick="cerrarModal()">×</span>
      <h3>Agregar Administrador</h3>
      <form id="formAgregar">
        <input type="text" name="name" placeholder="Nombre completo" required>
        <input type="email" name="email" placeholder="Correo institucional" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button class="btn add-btn" type="submit">Guardar</button>
      </form>
    </div>
  </div>

  <script>
    // Mostrar secciones sin recargar
    function mostrarSeccion(seccion) {
      document.getElementById("seccion-alumnos").classList.add("hidden");
      document.getElementById("seccion-admins").classList.add("hidden");

      if (seccion === "alumnos") {
        document.getElementById("seccion-alumnos").classList.remove("hidden");
      } else {
        document.getElementById("seccion-admins").classList.remove("hidden");
      }

      document.querySelectorAll(".sidebar ul li a").forEach(link => link.classList.remove("active"));
      event.target.classList.add("active");
    }

    // Modal
    function abrirModal() {
      document.getElementById("modalAgregar").style.display = "flex";
    }

    function cerrarModal() {
      document.getElementById("modalAgregar").style.display = "none";
    }

    // Agregar administrador
    document.getElementById("formAgregar").addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);

      const response = await fetch("create_admin.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();
      alert(result.message);

      if (result.success) {
        location.reload();
      }
    });

    // Eliminar usuario (sirve para admin o alumno)
    async function eliminarUsuario(id) {
      if (confirm("¿Seguro que deseas eliminar este usuario?")) {
        const formData = new FormData();
        formData.append("id", id);

        const response = await fetch("eliminar_usuario.php", {
          method: "POST",
          body: formData
        });

        const result = await response.json();

        if (result.success) {
          alert("✅ Usuario eliminado correctamente");
          location.reload();
        } else {
          alert("❌ Error al eliminar: " + result.message);
        }
      }
    }
  </script>
</body>
</html>
