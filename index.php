<?php include "includes/header.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTPN</title>

    <!-- MANIFEST -->
    <link rel="manifest" href="http://172.16.144.246/integradora-UTPN/manifest.json">
    <meta name="theme-color" content="#ffffff">

    <!-- MODO APP PARA ANDROID E IOS -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="apple-touch-icon" href="http://172.16.144.246/integradora-UTPN/assets/img/Logo.png">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, "Helvetica Neue", Arial, sans-serif;
  background-color: #EDE5D6;
}

/* ------------------------------- */
/*   CONTENEDOR PRINCIPAL (MÓVIL)  */
/* ------------------------------- */

.logos-container {
    width: 100%;
    max-width: 400px;
    margin: 30px auto 0 auto;
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
    place-items: center;
}

/* Evita subrayados */
.logos-container a {
    text-decoration: none;
}

/* ------------------------------- */
/*       ESTILO DE CADA LOGO       */
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
/*       MODO ESCRITORIO           */
/* ------------------------------- */

@media (min-width: 768px) {
    .logos-container {
        max-width: 900px;
        grid-template-columns: repeat(3, 1fr);
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

/* 🔥 BOTÓN INSTALAR PWA */
#btnInstalar {
    display: none;
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 12px 18px;
    background: #333;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    z-index: 999;
}
</style>

</head>

<body>

<main>
    <div class="logos-container">

        <a href="pages/public/becas.php">
            <div class="logo">
                <svg fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23 18.9999H22V8.99991H18V6.58569L12 0.585693L6 6.58569V8.99991H2V18.9999H1V20.9999H23V18.9999ZM6 19H4V11H6V19ZM18 11H20V19H18V11ZM11 12H13V19H11V12Z"></path>
                </svg>
                <p>Becas</p>
            </div>
        </a>

        <a href="pages/public/mapa_calendario.php">
            <div class="logo">
                <svg fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17 7C13.5705 7 10.6449 9.15804 9.50734 12.1903L11.3805 12.8927C12.2337 10.6185 14.4278 9 17 9C17.6983 9 18.3687 9.11928 18.992 9.33857C21.3265 10.16 23 12.3846 23 15C23 18.3137 20.3137 21 17 21H7C3.68629 21 1 18.3137 1 15C1 12.3846 2.67346 10.16 5.00804 9.33857C5.0027 9.22639 5 9.11351 5 9C5 5.13401 8.13401 2 12 2C15.242 2 17.9693 4.20399 18.7652 7.19539C18.1973 7.0675 17.6065 7 17 7Z"></path>
                </svg>
                <p>Mapa</p>
            </div>
        </a>

        <a href="pages/public/ChatBot.php">
            <div class="logo">
                <svg fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.00004C6.48577 2.00004 2 6.48581 2 12.0001C2 14.5989 3.03816 16.9204 4.70817 18.6757L4.08836 21.3918C4.01353 21.7138 4.30138 21.9961 4.62243 21.9213L7.38883 21.2982C9.0716 22.457 11.0089 22.9996 13 22.9996C18.5143 22.9996 23 18.5139 23 12.9996C23 7.48541 18.5143 2.00004 12 2.00004ZM12.0001 20.9996C11.6667 20.9996 11.3333 20.9996 11 20.9996C6.58172 20.9996 3 17.4179 3 12.9996C3 8.58137 6.58172 5.00004 11 5.00004C15.4183 5.00004 19 8.58137 19 12.9996C19 17.4179 15.4183 20.9996 11 20.9996Z"></path>
                </svg>
                <p>ChatBot</p>
            </div>
        </a>

    </div>
</main>

<button id="btnInstalar" onclick="instalarApp()">Instalar App</button>

<script>
/* REGISTRO DEL SW — RUTA FIJA CON IP */
if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("http://172.16.144.246/integradora-UTPN/sw.js")
        .then(() => console.log("SW registrado"))
        .catch(err => console.log("Error SW:", err));
}

let deferredPrompt;

window.addEventListener("beforeinstallprompt", (e) => {
    e.preventDefault();
    deferredPrompt = e;
    document.getElementById("btnInstalar").style.display = "block";
});

async function instalarApp() {
    const btn = document.getElementById("btnInstalar");
    btn.style.display = "none";

    deferredPrompt.prompt();
    await deferredPrompt.userChoice;
    deferredPrompt = null;
}

window.addEventListener("appinstalled", () => {
    document.getElementById("btnInstalar").style.display = "none";
});
</script>

<?php include "includes/footer.php"; ?>
</body>
</html>
