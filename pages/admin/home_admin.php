<?php
session_start();
require_once "../../config/db.php";
include "../../includes/header.php";

// Verificar si es admin
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../../login_register.php");
    exit;
}

// Recuperar permisos desde sesión
$permisos = $_SESSION["permisos"] ?? [];

// Función para verificar permiso
function tienePermiso($pagina) {
    global $permisos;
    return isset($permisos[$pagina]) && $permisos[$pagina] == 1;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTPN</title>
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, "Helvetica Neue", Arial, sans-serif;
  background-color: #EDE5D6; /* 🎨 crema claro */
}

/* ------------------------------- */
/*   CONTENEDOR PRINCIPAL (MÓVIL)  */
/* ------------------------------- */

.logos-container {
    width: 100%;
    max-width: 400px;
    margin: 30px auto 0 auto;
    display: grid;
    grid-template-columns: 1fr; /* 1 columna en móvil */
    gap: 20px;
    place-items: center;
}

/* Evita subrayados */
.logos-container a {
    text-decoration: none;
}

/* ------------------------------- */
/*         ESTILO DE CADA LOGO     */
/* ------------------------------- */

.logo {
    width: 140px;
    height: 140px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 10px;
    transition: 0.2s ease-in-out;
}

.logo:hover {
    transform: scale(1.06);
}

.logo svg {
    width: 50px;
    height: 50px;
    color: #b2842a;
    margin-bottom: 8px;
}

/* Elimina líneas debajo del texto */
.logo p {
    color: #333;
    font-size: 15px;
    font-weight: bold;
    text-align: center;
    line-height: 1.1;
    border-bottom: none !important;
    max-width: 100%;
}

/* ------------------------------- */
/*       MODO ESCRITORIO (DESKTOP) */
/* ------------------------------- */

@media (min-width: 768px) {
    .logos-container {
        max-width: 900px;
        grid-template-columns: repeat(3, 1fr); /* 3 columnas */
        gap: 25px;
        place-items: center;
    }

    .logo {
        width: 160px;
        height: 160px;
    }

    .logo svg {
        width: 55px;
        height: 55px;
    }

    .logo p {
        font-size: 16px;
    }
}

    </style>
</head>
<body>
<main>
    <div class="logos-container">

        <!-- 📌 Becas -->
        <a href="<?= tienePermiso('becas') ? '../admin/becas_admin.php' : '#' ?>">
            <div class="logo <?= !tienePermiso('becas') ? 'disabled' : '' ?>">
                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M23 18.9999H22V8.99991H18V6.58569L12 0.585693L6 6.58569V8.99991H2V18.9999H1V20.9999H23V18.9999ZM6 19H4V11H6V19ZM18 11H20V19H18V11ZM11 12H13V19H11V12Z"></path></svg>
                <p>Becas</p>
            </div>
        </a>

        <!-- 📌 Mapa -->
        <a href="<?= tienePermiso('mapa') ? '../../pages/alumno/recursos-aprendizaje/index.php' : '#' ?>">
            <div class="logo <?= !tienePermiso('mapa') ? 'disabled' : '' ?>">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-icon lucide-map"><path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"/><path d="M15 5.764v15"/><path d="M9 3.236v15"/></svg>
                <p>Mapa</p>
            </div>
        </a>

        <!-- 📌 Comunidad Bienestar -->
        <a href="<?= tienePermiso('becas') ? '../admin/foroadmin.php' : '#' ?>">
            <div class="logo <?= !tienePermiso('becas') ? 'disabled' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
                <p>Comunidad Bienestar</p>
            </div>
        </a>

        <!-- 📌 Recursos de aprendizaje -->
        <a href="<?= tienePermiso('recursos') ? '../../pages/admin/recursos-aprendizaje/admin_RA.php' : '#' ?>">
            <div class="logo <?= !tienePermiso('recursos') ? 'disabled' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-library-big-icon lucide-library-big"><rect width="8" height="18" x="3" y="3" rx="1"/><path d="M7 3v18"/><path d="M20.4 18.9c.2.5-.1 1.1-.6 1.3l-1.9.7c-.5.2-1.1-.1-1.3-.6L11.1 5.1c-.2-.5.1-1.1.6-1.3l1.9-.7c.5-.2 1.1.1 1.3.6Z"/></svg>
                <p>Recursos de aprendizaje</p>
            </div>
        </a>

        <!-- 📌 Ciberseguridad -->
        <a href="<?= tienePermiso('ciberseguridad') ? '../admin/cibers_consejos.php' : '#' ?>">
            <div class="logo <?= !tienePermiso('ciberseguridad') ? 'disabled' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-icon lucide-lock"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <p>Ciberseguridad</p>
            </div>
        </a>

    </div>
</main>
<?php include "../../includes/footer.php"; ?>
</body>
</html>
