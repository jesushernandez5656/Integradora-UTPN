<?php include "includes/header.php"; ?>
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTPN</title>
</head>
<body>
  <style>
    *{
    padding: 0;
    margin: 0;
    box-sizing: border-box;
}

body{
    background: #EDE5D6;
}
main {
  padding: 100px ; /* 80px arriba y abajo, 20px a los lados */
}
 
/* Contenedor de logos */
.logos-container {
    display: grid;
    grid-template-columns: repeat(3, 220px); /* 3 columnas en PC */
    grid-auto-rows: 220px;
    justify-content: center; /* centra la cuadrícula horizontalmente */
    gap: 10px;
    width: max-content; /* contenedor ajustado al contenido */
    margin: 60px auto; /* margen superior e inferior */
    transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
}

/* Cada logo */
.logo {
    width: 220px;
    height: 220px;
    display: flex;
    flex-direction: column; /* svg arriba, texto debajo */
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background-color: #ffffff;
    transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
    cursor: pointer;
    text-align: center;
}

/* Svg dentro del logo */
.logo svg {
    width: 32px;
    height: 32px;
    fill: #AE874C;
    transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
}

/* Hover original que tenías */
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

/* Texto debajo del svg */
.logo p {
    margin-top: 10px;
    font-size: 16px;
    color: #333333;
}

/* Responsive: Pantallas menores a 700px */
@media screen and (max-width: 700px) {
    .logos-container {
        grid-template-columns: repeat(2, 200px); /* 2 columnas */
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
    <main>
    <!-- From Uiverse.io by gharsh11032000 --> 
<div class="logos-container">
  <div class="logo"><a href="pages/alumno/becas.php">
    <svg
      fill="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M23 18.9999H22V8.99991H18V6.58569L12 0.585693L6 6.58569V8.99991H2V18.9999H1V20.9999H23V18.9999ZM6 19H4V11H6V19ZM18 11H20V19H18V11ZM11 12H13V19H11V12Z"
      ></path>
    </svg>
    </a>
    <p>Becas</p>
  </div>
  <div class="logo"><a href="pages/alumno/recursos-aprendizaje/index.php">
    <svg
      fill="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M17 7C13.5705 7 10.6449 9.15804 9.50734 12.1903L11.3805 12.8927C12.2337 10.6185 14.4278 9 17 9C17.6983 9 18.3687 9.11928 18.992 9.33857C21.3265 10.16 23 12.3846 23 15C23 18.3137 20.3137 21 17 21H7C3.68629 21 1 18.3137 1 15C1 12.3846 2.67346 10.16 5.00804 9.33857C5.0027 9.22639 5 9.11351 5 9C5 5.13401 8.13401 2 12 2C15.242 2 17.9693 4.20399 18.7652 7.19539C18.1973 7.0675 17.6065 7 17 7Z"
      ></path>
    </svg>
    </a>
    <p>Mapa</p>
  </div>
  <div class="logo">
    <svg
      fill="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M20.0833 10.4999L21.2854 11.2212C21.5221 11.3633 21.5989 11.6704 21.4569 11.9072C21.4146 11.9776 21.3557 12.0365 21.2854 12.0787L11.9999 17.6499L2.71451 12.0787C2.47772 11.9366 2.40093 11.6295 2.54301 11.3927C2.58523 11.3223 2.64413 11.2634 2.71451 11.2212L3.9166 10.4999L11.9999 15.3499L20.0833 10.4999ZM20.0833 15.1999L21.2854 15.9212C21.5221 16.0633 21.5989 16.3704 21.4569 16.6072C21.4146 16.6776 21.3557 16.7365 21.2854 16.7787L12.5144 22.0412C12.1977 22.2313 11.8021 22.2313 11.4854 22.0412L2.71451 16.7787C2.47772 16.6366 2.40093 16.3295 2.54301 16.0927C2.58523 16.0223 2.64413 15.9634 2.71451 15.9212L3.9166 15.1999L11.9999 20.0499L20.0833 15.1999ZM12.5144 1.30864L21.2854 6.5712C21.5221 6.71327 21.5989 7.0204 21.4569 7.25719C21.4146 7.32757 21.3557 7.38647 21.2854 7.42869L11.9999 12.9999L2.71451 7.42869C2.47772 7.28662 2.40093 6.97949 2.54301 6.7427C2.58523 6.67232 2.64413 6.61343 2.71451 6.5712L11.4854 1.30864C11.8021 1.11864 12.1977 1.11864 12.5144 1.30864Z"
      ></path>
    </svg>
    <p>Comunidad y Bienestar</p>
  </div>
  <div class="logo">
    <svg
      fill="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22H2L4.92893 19.0711C3.11929 17.2614 2 14.7614 2 12C2 6.47715 6.47715 2 12 2ZM16 13H8C8 15.2091 9.79086 17 12 17C14.2091 17 16 15.2091 16 13Z"
      ></path>
    </svg>
    <p>Recursos de aprendizaje</p>
  </div>
  <div class="logo">
    <svg
      fill="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M22 9.999V20C22 20.5523 21.5523 21 21 21H13V9.999H22ZM11 15.999V21H3C2.44772 21 2 20.5523 2 20V15.999H11ZM11 3V13.999H2V4C2 3.44772 2.44772 3 3 3H11ZM21 3C21.5523 3 22 3.44772 22 4V7.999H13V3H21Z"
      ></path>
    </svg>
    <p>Ciberseguridad</p>
  </div>
</div>
</main>

</body>
<?php include "includes/footer.php"; ?>
</html>