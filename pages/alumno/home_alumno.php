<?php include "../../includes/header.php"; ?>
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTPN</title>
</head>
<body>
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
    <main>
    <!-- From Uiverse.io by gharsh11032000 --> 
<div class="logos-container">
    <a href="../../pages/alumno/becas.php">
  <div class="logo">
    <svg
      fill="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M23 18.9999H22V8.99991H18V6.58569L12 0.585693L6 6.58569V8.99991H2V18.9999H1V20.9999H23V18.9999ZM6 19H4V11H6V19ZM18 11H20V19H18V11ZM11 12H13V19H11V12Z"
      ></path>
    </svg>
    
    <p>Becas</p>
  </div>
  </a>
<a href="../../pages/alumno/mapa_calendario.php">
  <div class="logo">
    <svg
      fill="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M17 7C13.5705 7 10.6449 9.15804 9.50734 12.1903L11.3805 12.8927C12.2337 10.6185 14.4278 9 17 9C17.6983 9 18.3687 9.11928 18.992 9.33857C21.3265 10.16 23 12.3846 23 15C23 18.3137 20.3137 21 17 21H7C3.68629 21 1 18.3137 1 15C1 12.3846 2.67346 10.16 5.00804 9.33857C5.0027 9.22639 5 9.11351 5 9C5 5.13401 8.13401 2 12 2C15.242 2 17.9693 4.20399 18.7652 7.19539C18.1973 7.0675 17.6065 7 17 7Z"
      ></path>
    </svg>
    <p>Mapa</p>
  </div>
  </a>
  <a href="../../pages/alumno/Foro.php">
  <div class="logo">
    <svg
      fill="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M17 7C13.5705 7 10.6449 9.15804 9.50734 12.1903L11.3805 12.8927C12.2337 10.6185 14.4278 9 17 9C17.6983 9 18.3687 9.11928 18.992 9.33857C21.3265 10.16 23 12.3846 23 15C23 18.3137 20.3137 21 17 21H7C3.68629 21 1 18.3137 1 15C1 12.3846 2.67346 10.16 5.00804 9.33857C5.0027 9.22639 5 9.11351 5 9C5 5.13401 8.13401 2 12 2C15.242 2 17.9693 4.20399 18.7652 7.19539C18.1973 7.0675 17.6065 7 17 7Z"
      ></path>
    </svg>
    <p>Comunidad Bienestar</p>
  </div>
  </a>
    <a href="../../pages/alumno/recursos-aprendizaje/index.php">
  <div class="logo">
    <svg
      fill="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M17 7C13.5705 7 10.6449 9.15804 9.50734 12.1903L11.3805 12.8927C12.2337 10.6185 14.4278 9 17 9C17.6983 9 18.3687 9.11928 18.992 9.33857C21.3265 10.16 23 12.3846 23 15C23 18.3137 20.3137 21 17 21H7C3.68629 21 1 18.3137 1 15C1 12.3846 2.67346 10.16 5.00804 9.33857C5.0027 9.22639 5 9.11351 5 9C5 5.13401 8.13401 2 12 2C15.242 2 17.9693 4.20399 18.7652 7.19539C18.1973 7.0675 17.6065 7 17 7Z"
      ></path>
    </svg>
    <p>Recursos de aprendizaje</p>
  </div>
  </a>
    <a href="../alumno/cibers_consejos.php">
  <div class="logo">
    <svg
      fill="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M17 7C13.5705 7 10.6449 9.15804 9.50734 12.1903L11.3805 12.8927C12.2337 10.6185 14.4278 9 17 9C17.6983 9 18.3687 9.11928 18.992 9.33857C21.3265 10.16 23 12.3846 23 15C23 18.3137 20.3137 21 17 21H7C3.68629 21 1 18.3137 1 15C1 12.3846 2.67346 10.16 5.00804 9.33857C5.0027 9.22639 5 9.11351 5 9C5 5.13401 8.13401 2 12 2C15.242 2 17.9693 4.20399 18.7652 7.19539C18.1973 7.0675 17.6065 7 17 7Z"
      ></path>
    </svg>
    <p>Ciberseguridad</p>
  </div>
  </a>
</div>
</main>

</body>
<?php include "../../includes/footer.php"; ?>
</html>