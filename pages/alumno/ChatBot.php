<?php include "../../includes/header.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/UTPN/assets/css/becas.css">
  <link rel="stylesheet" href="/UTPN/assets/css/header.css">
  <link rel="stylesheet" href="/UTPN/assets/css/footer.css">
  <title>Chat de Apoyo Psicológico</title>
  <style>
    /* Chat a pantalla completa */
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: Arial, sans-serif;
      background: #f0f4f8;
      display: flex;
      flex-direction: column;
    }

    .chat-container {
      flex: 1;
      display: flex;
      flex-direction: column;
      height: 100%;
      max-height: 100vh;
    }

    .chat-header {
      background: #4cafaa;
      color: #fff;
      text-align: center;
      padding: 15px;
      font-size: 1.3em;
      font-weight: bold;
    }

    .chat-messages {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
      background: #e9f5f5;
      display: flex;
      flex-direction: column;
    }

    .message {
      margin: 8px 0;
      padding: 10px 14px;
      border-radius: 20px;
      max-width: 70%;
      line-height: 1.4;
    }

    .bot {
      background: #ffffff;
      align-self: flex-start;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .user {
      background: #c8e6c9;
      align-self: flex-end;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .chat-input {
      display: flex;
      border-top: 2px solid #ddd;
      padding: 10px;
      background: #fff;
    }

    .chat-input input {
      flex: 1;
      border: none;
      padding: 12px;
      border-radius: 20px;
      outline: none;
      background: #f1f1f1;
      font-size: 1em;
    }

    .chat-input button {
      border: none;
      background: #4cafaa;
      color: white;
      padding: 12px 20px;
      margin-left: 10px;
      border-radius: 20px;
      cursor: pointer;
      font-size: 1em;
    }

    .chat-input button:hover {
      background: #3d918f;
    }
  </style>
</head>
<body>
  <main>
    <div class="chat-container">
      <div class="chat-header">💬 Chat de Apoyo Psicológico</div>

      <div class="chat-messages" id="chat-messages">
        <div class="message bot">Hola 👋 Soy tu asistente de apoyo psicológico. ¿Cómo te sientes hoy?</div>
      </div>

      <div class="chat-input">
        <input type="text" id="user-input" placeholder="Escribe tu mensaje...">
        <button onclick="sendMessage()">Enviar</button>
      </div>
    </div>
  </main>

  <script>
    const messagesContainer = document.getElementById("chat-messages");

    function sendMessage() {
      const input = document.getElementById("user-input");
      const text = input.value.trim();
      if (text === "") return;

      // mensaje del usuario
      addMessage(text, "user");

      input.value = "";

      // respuesta del bot
      setTimeout(() => {
        respondToMessage(text);
      }, 800);
    }

    function addMessage(text, sender) {
      const msg = document.createElement("div");
      msg.classList.add("message", sender);
      msg.innerText = text;
      messagesContainer.appendChild(msg);
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function respondToMessage(userText) {
      const lower = userText.toLowerCase();
      let response = "Entiendo lo que dices, cuéntame más sobre cómo te sientes.";

      if (lower.includes("estres") || lower.includes("ansiedad")) {
        response = "Eso suena difícil 😟. Te recomiendo respirar profundo y hablar de lo que te preocupa. Estoy aquí para escucharte.";
      } 
      else if (lower.includes("triste") || lower.includes("solo")) {
        response = "Lamento que te sientas así 💙. Recuerda que no estás solo, expresar tus emociones es un gran paso.";
      }
      else if (lower.includes("suicidio") || lower.includes("depresion") || lower.includes("muy mal")) {
        response = "Lo que me cuentas es muy importante. 🙏 No estás solo, por favor comunícate con el psicólogo de la universidad: **555-123-4567** 📞 para agendar una cita.";
      }
      else if (lower.includes("gracias")) {
        response = "De nada 💚. Estoy aquí siempre que quieras hablar.";
      }

      addMessage(response, "bot");
    }
  </script>
</body>
<?php include "../../includes/footer.php"; ?>
</html>
