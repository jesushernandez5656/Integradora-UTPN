<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Superadmin Dashboard</title>
  <link rel="stylesheet" href="/UTPN/assets/css/superadmin.css">
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

section table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}

table th, table td {
  padding: 12px;
  border-bottom: 1px solid #eee;
  text-align: left;
}

table th {
  background: #4facfe;
  color: white;
}


/* 📱 Responsive Dashboard */
@media (max-width: 1024px) {
  .sidebar {
    width: 180px;
    padding: 15px;
  }

  .main-content {
    padding: 15px;
  }

  header h1 {
    font-size: 22px;
  }
}

@media (max-width: 768px) {
  body {
    flex-direction: column;
  }

  .sidebar {
    width: 100%;
    min-height: auto;
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 10px;
  }

  .sidebar h2 {
    display: none;
  }

  .sidebar ul {
    display: flex;
    gap: 15px;
  }

  .sidebar ul li {
    margin: 0;
  }

  .main-content {
    width: 100%;
    padding: 15px;
  }

  table th, table td {
    font-size: 14px;
    padding: 8px;
  }
}

@media (max-width: 480px) {
  .sidebar ul {
    flex-direction: column;
    gap: 10px;
    align-items: center;
  }

  header h1 {
    font-size: 18px;
  }

  section h2 {
    font-size: 16px;
  }

  table {
    font-size: 12px;
  }

  table th, table td {
    padding: 6px;
  }
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
            <th>Correo</th>
            <th>Rol</th>
          </tr>
        </thead>
        <tbody>
          <!-- Aquí se llenará desde PHP con los alumnos -->
          <tr>
            <td>1</td>
            <td>alumno@correo.com</td>
            <td>User</td>
          </tr>
        </tbody>
      </table>
    </section>
  </div>
</body>
</html>
