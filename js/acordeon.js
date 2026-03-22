const faqItems = document.querySelectorAll(".faq-item");

faqItems.forEach((item) => {
  const question = item.querySelector(".faq-question");
  const answer = item.querySelector(".faq-answer");

  question.addEventListener("click", () => {
    const isActive = answer.classList.contains("activo");

    // Cerrar todos los FAQ abiertos
    document.querySelectorAll(".faq-answer.activo").forEach((a) => {
      a.style.maxHeight = null;
      a.classList.remove("activo");
    });
    document.querySelectorAll(".faq-question.activo").forEach((q) => {
      q.classList.remove("activo");
    });

    // Abrir el seleccionado
    if (!isActive) {
      answer.classList.add("activo");
      question.classList.add("activo");
      answer.style.maxHeight = answer.scrollHeight + "px"; // altura exacta del contenido
    }
  });
});
