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
    <p>Texto</p>
  </div>
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
    <p>Texto</p>
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
    <p>Texto</p>
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
    <p>Texto</p>
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
    <p>Texto</p>
  </div>
  <div class="logo">
    <svg
      fill="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M3 3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3ZM12 15V17H18V15H12ZM8.41421 12L5.58579 14.8284L7 16.2426L11.2426 12L7 7.75736L5.58579 9.17157L8.41421 12Z"
      ></path>
    </svg>
    <p>Texto</p>
  </div>
  <div class="logo">
    <svg
      fill="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M8 4C8 2.34315 9.34315 1 11 1C12.6569 1 14 2.34315 14 4C14 4.35064 13.9398 4.68722 13.8293 5H18C18.5523 5 19 5.44772 19 6V10.1707C19.3128 10.0602 19.6494 10 20 10C21.6569 10 23 11.3431 23 13C23 14.6569 21.6569 16 20 16C19.6494 16 19.3128 15.9398 19 15.8293V20C19 20.5523 18.5523 21 18 21H4C3.44772 21 3 20.5523 3 20V6C3 5.44772 3.44772 5 4 5H8.17071C8.06015 4.68722 8 4.35064 8 4Z"
      ></path>
    </svg>
    <p>Texto</p>
  </div>
  <div class="logo">
    <svg
      fill="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M3 6C3 4.34315 4.34315 3 6 3C7.65685 3 9 4.34315 9 6C9 7.30622 8.16519 8.41746 7 8.82929V15.1707C8.16519 15.5825 9 16.6938 9 18C9 19.6569 7.65685 21 6 21C4.34315 21 3 19.6569 3 18C3 16.6938 3.83481 15.5825 5 15.1707V8.82929C3.83481 8.41746 3 7.30622 3 6ZM15.2929 3.29289C15.6834 2.90237 16.3166 2.90237 16.7071 3.29289L18 4.58579L19.2929 3.29289C19.6834 2.90237 20.3166 2.90237 20.7071 3.29289C21.0976 3.68342 21.0976 4.31658 20.7071 4.70711L19.4142 6L20.7071 7.29289C21.0976 7.68342 21.0976 8.31658 20.7071 8.70711C20.3166 9.09763 19.6834 9.09763 19.2929 8.70711L18 7.41421L16.7071 8.70711C16.3166 9.09763 15.6834 9.09763 15.2929 8.70711C14.9024 8.31658 14.9024 7.68342 15.2929 7.29289L16.5858 6L15.2929 4.70711C14.9024 4.31658 14.9024 3.68342 15.2929 3.29289ZM18 10C18.5523 10 19 10.4477 19 11V15.1707C20.1652 15.5825 21 16.6938 21 18C21 19.6569 19.6569 21 18 21C16.3431 21 15 19.6569 15 18C15 16.6938 15.8348 15.5825 17 15.1707V11C17 10.4477 17.4477 10 18 10Z"
      ></path>
    </svg>
    <p>Texto</p>
  </div>
  <div class="logo">
    <svg
      fill="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M5.07089 16C5.02417 15.6734 5 15.3395 5 15V14H3V12H5V11C5 10.6605 5.02417 10.3266 5.07089 10H3V8.00001H5.67363C6.28647 6.70996 7.28227 5.6371 8.51412 4.92834L7.46447 3.87869L8.87868 2.46448L10.5621 4.14785C11.0262 4.05095 11.5071 4.00001 12 4.00001C12.4929 4.00001 12.9738 4.05095 13.4379 4.14785L15.1213 2.46448L16.5355 3.87869L15.4859 4.92834C16.7177 5.6371 17.7135 6.70996 18.3264 8.00001H21V10H18.9291C18.9758 10.3266 19 10.6605 19 11V12H21V14H19V15C19 15.3395 18.9758 15.6734 18.9291 16H21V18H18.3264C17.2029 20.365 14.7924 22 12 22C9.2076 22 6.7971 20.365 5.67363 18H3V16H5.07089ZM9 10V12H15V10H9ZM9 14V16H15V14H9Z"
      ></path>
    </svg>
    <p>Texto</p>
  </div>
</div>
</main>

</body>
<?php include "includes/footer.php"; ?>
</html>