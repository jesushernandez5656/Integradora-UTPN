<?php
// index.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = $_POST["message"];
    $config = include "../api/config.php";
    $apiKey = $config["apiKey"];

    $ch = curl_init("https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        "model" => "gpt-3.5-turbo",
        "messages" => [
            [
                "role" => "system",
                "content" => "Eres un psicólogo virtual de apoyo emocional. 
Si detectas temas graves como depresión, ansiedad, autolesión o pensamientos suicidas, brinda un mensaje empático y ofrece ayuda inmediata con los siguientes contactos de apoyo:

📞 Psicólogo de la universidad: +52 656 123 4567  
📞 Línea Nacional de la Vida (24h, gratuita): 800 911 2000  
📞 Línea en Crisis del Estado de Chihuahua: 800 012 7274  
📞 Línea de Atención en Crisis Ciudad Chihuahua: 614 194 0200  
📞 Emergencias con atención psicológica: marca 911 y solicita la Línea de Crisis  

Tu labor es escuchar con empatía, acompañar emocionalmente y recordar que siempre hay ayuda disponible."
            ],
            ["role" => "user", "content" => $input]
        ]
    ]));

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    echo $result["choices"][0]["message"]["content"];
    exit;
}
?>
<?php include "../../includes/header.php"; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Chat Psicológico</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background: linear-gradient(to right, #a8edea, #fed6e3);
    }
    
    /* Header Styles */
    .header {
      background-color: #ffffff;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .header-title {
      font-size: 24px;
      font-weight: bold;
      color: #333;
    }
    
    .header-login {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .header-login a {
      text-decoration: none;
      color: #333;
      font-weight: bold;
      padding: 8px 15px;
      border-radius: 4px;
      transition: background-color 0.3s;
    }
    
    .header-login a:hover {
      background-color: #f0f0f0;
    }
    
    /* Main Content Styles */
    .main-content {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      margin-bottom: 50px; /* Espacio adicional para bajar el footer */
    }
    
    .chat-container {
      width: 100%;
      max-width: 800px;
      height: 80vh;
      display: flex;
      flex-direction: column;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    
    .chat-header {
      background: #4CAF50;
      color: white;
      padding: 20px;
      text-align: center;
      font-size: 22px;
      font-weight: bold;
    }
    
    .chat-body {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
      background: #f9f9f9;
      display: flex;
      flex-direction: column;
    }
    
    .message {
      max-width: 70%;
      padding: 12px;
      margin: 8px 0;
      border-radius: 12px;
      font-size: 16px;
      line-height: 1.4em;
    }
    
    .bot {
      background: #e1ffc7;
      align-self: flex-start;
    }
    
    .user {
      background: #cce5ff;
      align-self: flex-end;
    }
    
    .chat-footer {
      display: flex;
      padding: 15px;
      border-top: 1px solid #ccc;
      background: white;
    }
    
    .chat-footer input {
      flex: 1;
      padding: 12px;
      border-radius: 12px;
      border: 1px solid #ccc;
      outline: none;
      font-size: 16px;
    }
    
    .chat-footer button {
      background: #4CAF50;
      color: white;
      border: none;
      padding: 12px 20px;
      margin-left: 10px;
      border-radius: 12px;
      cursor: pointer;
      font-size: 16px;
    }
    
    .chat-footer button:hover {
      background: #45a049;
    }

    /* Footer Styles - matching the previously created footer */
    footer#final {
        background-color: #008080; /* Color teal */
        text-align: center;
        color: white;
        padding: 40px 20px 30px 20px;
        position: relative;
        margin-top: auto;
        flex-shrink: 0;
    }

    footer#final .contacto h1 {
        font-weight: bold;
        font-size: 1.8rem;
        margin-bottom: 5px;
    }

    footer#final .contacto p {
        font-size: 1.2rem;
        margin: 0 0 30px 0;
    }

    ul.social_icon {
        list-style: none;
        padding: 0;
        margin: 0 0 30px 0;
        display: flex;
        justify-content: center;
        gap: 50px;
    }

    ul.social_icon li a svg {
        stroke: #AE874C; /* dorado */
        width: 50px;
        height: 50px;
        transition: stroke 0.3s ease;
        vertical-align: middle;
    }

    ul.social_icon li a:hover svg {
        stroke: #FFD700; /* dorado brillante on hover */
    }

    .logo_footer img {
        width: 120px; /* tamaño del logo */
        margin-bottom: 20px;
    }

    footer#final p.final_text {
        font-size: 1rem;
        color: white;
        margin-top: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .header {
        flex-direction: column;
        gap: 10px;
      }
      
      .chat-container {
        height: 70vh;
      }
      
      .message {
        max-width: 85%;
      }
      
      footer#final {
        padding: 20px;
      }
      
      .main-content {
        margin-bottom: 30px;
      }
    }
  </style>
</head>
<body>
  
  <!-- Main Content -->
  <main class="main-content">
    <div class="chat-container">
      <div class="chat-header">ChatBot Psicológico</div>
      <div class="chat-body" id="chatBody">
        <div class="message bot">
          ¡Hola! Soy tu asistente psicológico virtual 😊<br>
          Cuéntame cómo te sientes hoy.
        </div>
      </div>
      <div class="chat-footer">
        <input type="text" id="userInput" placeholder="Escribe tu mensaje...">
        <button onclick="sendMessage()">Enviar</button>
      </div>
    </div>
  </main>
  
  <!-- Footer -->
  <?php
  // Definir la ruta del logo según el rol
  $footerLogoHref = '/integradora-UTPN/index.php'; // Ruta por defecto

  if (isset($_SESSION['rol'])) {
      switch ($_SESSION['rol']) {
          case 'superadmin':
              $footerLogoHref = '/integradora-UTPN/pages/superadmin/home_superadmin.php';
              break;
          case 'admin':
              $footerLogoHref = '/integradora-UTPN/pages/admin/home_admin.php';
              break;
          case 'user':
              $footerLogoHref = '/integradora-UTPN/pages/alumno/home_alumno.php';
              break;
      }
  }
  ?>

  <footer id="final">
    <div class="contacto">
        <h1>Contáctanos</h1>
        <p>656-221-5597</p>
    </div>

    <ul class="social_icon">
        <li>
            <a href="https://www.facebook.com/doitfitneess" target="_blank" aria-label="Facebook">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#AE874C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                </svg>
            </a>
        </li>
        <li>
            <a href="https://www.instagram.com/doitfitnessjrz/" target="_blank" aria-label="Instagram">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#AE874C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                    <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                </svg>
            </a>
        </li>
        <li>
            <a href="https://goo.gl/maps/wMgmVw5xVX8qPRHG7" target="_blank" aria-label="Ubicación">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#AE874C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"/>
                    <path d="M15 5.764v15"/>
                    <path d="M9 3.236v15"/>
                </svg>
            </a>
        </li>
    </ul>

    <div class="logo_footer">
        <a href="<?= $footerLogoHref ?>">
            <img src="/integradora-UTPN/assets/img/Logo.png" alt="Logo Universidad" />
        </a>
    </div>

    <p class="final_text">@2025 IRCM41 | Reservados todos los derechos.</p>
  </footer>
  

  <script>
    const chatBody = document.getElementById("chatBody");
    const userInput = document.getElementById("userInput");

    function addMessage(text, sender) {
      let msg = document.createElement("div");
      msg.classList.add("message", sender);
      msg.innerHTML = text;
      chatBody.appendChild(msg);
      chatBody.scrollTop = chatBody.scrollHeight;
    }

    async function sendMessage() {
      let text = userInput.value.trim();
      if (text === "") return;

      addMessage(text, "user");
      userInput.value = "";

      let loading = document.createElement("div");
      loading.classList.add("message", "bot");
      loading.textContent = "Escribiendo...";
      chatBody.appendChild(loading);
      chatBody.scrollTop = chatBody.scrollHeight;

      let formData = new FormData();
      formData.append("message", text);

      let response = await fetch("ChatBot.php", {
        method: "POST",
        body: formData
      });

      let botReply = await response.text();
      loading.remove();
      addMessage(botReply, "bot");
    }

    userInput.addEventListener("keypress", function(e) {
      if (e.key === "Enter") sendMessage();
    });
  </script>
</body>
</html>
