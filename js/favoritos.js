const track = document.getElementById("track");

let speed = 0.15; // velocidad de desplazamiento (px por frame)
let position = 0;
let isPaused = false;

/* CLONAR CONTENIDO PARA LOOP INFINITO */
track.innerHTML += track.innerHTML;

function animate() {
  if (!isPaused) {
    position -= speed;

    if (Math.abs(position) >= track.scrollWidth / 2) {
      position = 0; // reinicio invisible
    }

    track.style.transform = `translateX(${position}px)`;
  }

  requestAnimationFrame(animate);
}

animate();

/* PAUSA AL PASAR MOUSE */
track.addEventListener("mouseenter", () => isPaused = true);
track.addEventListener("mouseleave", () => isPaused = false);

/* BOTONES SOLO EN MÓVIL */
function scrollCarrusel(dir) {
  const card = track.querySelector(".producto-card");
  position -= dir * (card.offsetWidth + 20);
}