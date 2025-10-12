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
    *{
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }
    body{
        background: #EDE5D6;
    }
    a {
        text-decoration: none;
    }
    main {
        padding: 100px;
    }
    .logos-container {
        display: grid;
        grid-template-columns: repeat(3, 220px);
        grid-auto-rows: 220px;
        justify-content: center;
        gap: 10px;
        width: max-content;
        margin: 60px auto;
        transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
    }
    .logo {
        width: 220px;
        height: 220px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background-color: #ffffff;
        transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
        cursor: pointer;
        text-align: center;
    }
    .logo svg {
        width: 32px;
        height: 32px;
        fill: #AE874C;
        transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
    }
    .logo:hover {
        background-color: #00837f;
        box-shadow:
            0rem 6px 13px rgba(10, 60, 255, 0.1),
            0rem 24px 24px rgba(10, 60, 255, 0.09),
            0rem 55px 33px rgba(10, 60, 255, 0.05),
            0rem 97px 39px rgba(10, 60, 255, 0.01),
            0rem 152px 43px rgba(10, 60, 255, 0);
    }
    .logo:hover svg {
        fill: #ffffff;
    }
    .logo p {
        margin-top: 10px;
        font-size: 16px;
        color: #333333;
    }
    /* DESACTIVADO */
    .logo.disabled {
        opacity: 0.5;
        pointer-events: none;
        filter: grayscale(80%);
        cursor: not-allowed;
    }
    @media screen and (max-width: 700px) {
        .logos-container {
            grid-template-columns: repeat(2, 200px);
            grid-auto-rows: 200px;
            gap: 10px;
            width: max-content;
        }
        .logo {
            width: 200px;
            height: 200px;
        }
        .logo svg {
            width: 28px;
            height: 28px;
        }
    }
    </style>
</head>
<body>
<main>
    <div class="logos-container">

        <!-- 📌 Becas -->
        <a href="<?= tienePermiso('becas') ? 'pages/public/becas.php' : '#' ?>">
            <div class="logo <?= !tienePermiso('becas') ? 'disabled' : '' ?>">
                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M23 18.9999H22V8.99991H18V6.58569L12 0.585693L6 6.58569V8.99991H2V18.9999H1V20.9999H23V18.9999ZM6 19H4V11H6V19ZM18 11H20V19H18V11ZM11 12H13V19H11V12Z"></path></svg>
                <p>Becas</p>
            </div>
        </a>

        <!-- 📌 Mapa -->
        <a href="<?= tienePermiso('mapa') ? 'pages/alumno/recursos-aprendizaje/index.php' : '#' ?>">
            <div class="logo <?= !tienePermiso('mapa') ? 'disabled' : '' ?>">
                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M17 7C13.5705 7 10.6449 9.15804 9.50734 12.1903L11.3805 12.8927C12.2337 10.6185 14.4278 9 17 9C17.6983 9 18.3687 9.11928 18.992 9.33857C21.3265 10.16 23 12.3846 23 15C23 18.3137 20.3137 21 17 21H7C3.68629 21 1 18.3137 1 15C1 12.3846 2.67346 10.16 5.00804 9.33857C5.0027 9.22639 5 9.11351 5 9C5 5.13401 8.13401 2 12 2C15.242 2 17.9693 4.20399 18.7652 7.19539C18.1973 7.0675 17.6065 7 17 7Z"></path></svg>
                <p>Mapa</p>
            </div>
        </a>

        <!-- 📌 Comunidad Bienestar -->
        <a href="<?= tienePermiso('comunidad') ? 'pages/alumno/recursos-aprendizaje/index.php' : '#' ?>">
            <div class="logo <?= !tienePermiso('comunidad') ? 'disabled' : '' ?>">
                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M17 7C13.5705 7 10.6449 9.15804 9.50734 12.1903L11.3805 12.8927C12.2337 10.6185 14.4278 9 17 9C17.6983 9 18.3687 9.11928 18.992 9.33857C21.3265 10.16 23 12.3846 23 15C23 18.3137 20.3137 21 17 21H7C3.68629 21 1 18.3137 1 15C1 12.3846 2.67346 10.16 5.00804 9.33857C5.0027 9.22639 5 9.11351 5 9C5 5.13401 8.13401 2 12 2C15.242 2 17.9693 4.20399 18.7652 7.19539C18.1973 7.0675 17.6065 7 17 7Z"></path></svg>
                <p>Comunidad Bienestar</p>
            </div>
        </a>

        <!-- 📌 Recursos de aprendizaje -->
        <a href="<?= tienePermiso('recursos') ? 'pages/alumno/recursos-aprendizaje/index.php' : '#' ?>">
            <div class="logo <?= !tienePermiso('recursos') ? 'disabled' : '' ?>">
                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M17 7C13.5705 7 10.6449 9.15804 9.50734 12.1903L11.3805 12.8927C12.2337 10.6185 14.4278 9 17 9C17.6983 9 18.3687 9.11928 18.992 9.33857C21.3265 10.16 23 12.3846 23 15C23 18.3137 20.3137 21 17 21H7C3.68629 21 1 18.3137 1 15C1 12.3846 2.67346 10.16 5.00804 9.33857C5.0027 9.22639 5 9.11351 5 9C5 5.13401 8.13401 2 12 2C15.242 2 17.9693 4.20399 18.7652 7.19539C18.1973 7.0675 17.6065 7 17 7Z"></path></svg>
                <p>Recursos de aprendizaje</p>
            </div>
        </a>

        <!-- 📌 Ciberseguridad -->
        <a href="<?= tienePermiso('ciberseguridad') ? 'pages/alumno/recursos-aprendizaje/index.php' : '#' ?>">
            <div class="logo <?= !tienePermiso('ciberseguridad') ? 'disabled' : '' ?>">
                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M17 7C13.5705 7 10.6449 9.15804 9.50734 12.1903L11.3805 12.8927C12.2337 10.6185 14.4278 9 17 9C17.6983 9 18.3687 9.11928 18.992 9.33857C21.3265 10.16 23 12.3846 23 15C23 18.3137 20.3137 21 17 21H7C3.68629 21 1 18.3137 1 15C1 12.3846 2.67346 10.16 5.00804 9.33857C5.0027 9.22639 5 9.11351 5 9C5 5.13401 8.13401 2 12 2C15.242 2 17.9693 4.20399 18.7652 7.19539C18.1973 7.0675 17.6065 7 17 7Z"></path></svg>
                <p>Ciberseguridad</p>
            </div>
        </a>
    </div>
</main>
<?php include "../../includes/footer.php"; ?>
</body>
</html>
