function toggleChat() {
  const chat = document.getElementById("chatContainer");
  chat.classList.toggle("active");
}

function sendMessage() {
  const input = document.getElementById("userInput");
  const message = input.value.trim();
  if (message === "") return;

  addMessage(message, "user-message");
  input.value = "";

  setTimeout(() => {
    const response = generateResponse(message);
    addMessage(response, "bot-message");
  }, 700);
}

function addMessage(text, className) {
  const chatBody = document.getElementById("chatBody");
  const messageDiv = document.createElement("div");
  messageDiv.className = className;
  messageDiv.textContent = text;
  chatBody.appendChild(messageDiv);
  chatBody.scrollTop = chatBody.scrollHeight;
}

function generateResponse(msg) {
  msg = msg.toLowerCase();

  const saludos = ["hola", "buenas", "hey"];
  const despedidas = ["adios", "bye", "nos vemos"];
  const cafe = ["cafe", "latte", "capuchino", "menu", "precio"];
  const ubicacion = ["donde", "ubicacion", "direccion"];

  if (saludos.some((p) => msg.includes(p))) {
    return "¡Hola! ☕ Qué gusto verte por aquí. ¿Quieres ver nuestras promociones?";
  }

  if (despedidas.some((p) => msg.includes(p))) {
    return "¡Gracias por visitarnos! 💛 Te esperamos en RUSH Café.";
  }

  if (cafe.some((p) => msg.includes(p))) {
    return "Tenemos latte, capuchino, frappé y promociones especiales 🔥 ¿Te gustaría una recomendación?";
  }

  if (ubicacion.some((p) => msg.includes(p))) {
    return "Estamos ubicados en el corazón de la ciudad 📍 ¡Ven a visitarnos!";
  }

  const respuestasAleatorias = [
    "Interesante 🤔 cuéntame más.",
    "Suena bien ☕ ¿quieres saber algo sobre nuestro menú?",
    "Estoy aquí para ayudarte 😊",
    "Eso me gusta 😎 ¿te recomiendo algo especial?",
    "En RUSH Café siempre tenemos algo delicioso para ti.",
  ];

  return respuestasAleatorias[
    Math.floor(Math.random() * respuestasAleatorias.length)
  ];
}
