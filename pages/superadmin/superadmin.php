<?php
include('../../config/db.php'); // Conecta con utpn

// Obtener alumnos (user_type = 'user')
$query = $conn->prepare("SELECT id, name, email, created_at FROM users WHERE user_type = 'user' ORDER BY id ASC");
$query->execute();
$alumnos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Superadmin Dashboard</title>
</head>
<body>
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

    .sidebar ul li a:hover {
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
  </style>

  <div class="sidebar">
    <h2>Superadmin</h2>
    <ul>
      <li><a href="#">Usuarios</a></li>
      <li><a href="#">Administradores</a></li>
      <li><a href="../../logout.php">Cerrar sesión</a></li>
    </ul>
  </div>

  <div class="main-content">
    <header>
      <h1>Panel de Control</h1>
    </header>

    <section>
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
              <tr id="user-<?= $alumno['id'] ?>">
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
  </div>

  <script>
    async function eliminarUsuario(id) {
      if (confirm("¿Seguro que deseas eliminar este usuario?")) {
        const formData = new FormData();
        formData.append("id", id);

        const response = await fetch("eliminar_usuar  io.php", {
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
