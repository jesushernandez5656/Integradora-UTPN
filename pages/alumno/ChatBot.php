<?php include "../../includes/header.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <link rel="stylesheet" href="/INTEGRADORA-UTPN/assets/css/header.css">
    <link rel="stylesheet" href="/INTEGRADORA-UTPN/assets/css/footer.css">
    <title>Document</title>
    <style>
        /* Estilos del chatbot */
        .chatbot-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 320px;
            max-height: 450px;
            background: #f9f9f9;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        .chatbot-header {
            background: #4cafaa;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }

        .chatbot-messages {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            background: #fff;
        }

        .chatbot-message {
            margin: 8px 0;
            padding: 8px 12px;
            border-radius: 12px;
            max-width: 80%;
            line-height: 1.4;
        }

        .bot-message {
            background: #e0f7fa;
            align-self: flex-start;
        }

        .user-message {
            background: #c8e6c9;
            align-self: flex-end;
        }

        .chatbot-input {
            display: flex;
            border-top: 1px solid #ccc;
        }

        .chatbot-input input {
            flex: 1;
            border: none;
            padding: 10px;
            border-radius: 0 0 0 15px;
            outline: none;
        }

        .chatbot-input button {
            border: none;
            background: #4cafaa;
            color: white;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 0 0 15px 0;
        }
    </style>
</head>
<body>
    <main>
        <h1>Prueba de cambios 2</h1>
    </main>

    <!-- Chatbot -->
    <div class="chatbot-container">
        <div class="chatbot-header">💬 Ayuda Psicológica</div>
        <div class="chatbot-messages" id="chatbot-messages">
            <div class="chatbot-message bot-message">Hola 👋 Soy tu asistente de apoyo psicológico. ¿Cómo te sientes hoy?</div>
        </div>
        <div class="chatbot-input">
            <input type="text" id="chatbot-input" placeholder="Escribe tu mensaje...">
            <button onclick="sendMessage()">Enviar</button>
        </div>
    </div>

    <script>
        const messagesContainer = document.getElementById("chatbot-messages");

        function sendMessage() {
            const input = document.getElementById("chatbot-input");
            const text = input.value.trim();
            if (text === "") return;

            // Mostrar mensaje del usuario
            addMessage(text, "user-message");

            // Limpiar input
            input.value = "";

            // Respuesta del bot
            setTimeout(() => {
                respondToMessage(text);
            }, 800);
        }

        function addMessage(text, className) {
            const msg = document.createElement("div");
            msg.classList.add("chatbot-message", className);
            msg.innerText = text;
            messagesContainer.appendChild(msg);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function respondToMessage(userText) {
            const lower = userText.toLowerCase();
            let response = "Entiendo, cuéntame un poco más sobre eso.";

            if (lower.includes("estres") || lower.includes("ansiedad")) {
                response = "Lamento que te sientas así 😟. Respirar profundo y hablar de lo que sientes puede ayudarte. Estoy aquí para escucharte.";
            } 
            else if (lower.includes("triste") || lower.includes("solo")) {
                response = "Siento que te sientas así 💙. No estás solo, hablar es un buen primer paso.";
            }
            else if (lower.includes("suicidio") || lower.includes("muy mal") || lower.includes("depresion")) {
                response = "Lo que me cuentas es muy importante. No estás solo. Por favor comunícate con el psicólogo de la uni: **555-123-4567** 📞 para agendar una cita.";
            }
            else if (lower.includes("gracias")) {
                response = "Con gusto 💚. Recuerda que siempre puedes escribirme.";
            }

            addMessage(response, "bot-message");
        }
    </script>
</body>
<?php include "../../includes/footer.php"; ?>
</html>
