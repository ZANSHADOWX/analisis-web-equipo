document.querySelectorAll(".agregar-carrito").forEach((boton) => {
  boton.addEventListener("click", function () {
    let id = this.dataset.id;

    /* animación inmediata */
    animarProducto(this);

    /* enviar producto al carrito */

    fetch("carrito/agregar_ajax.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "id=" + id,
    })
      .then((res) => res.text())
      .then((data) => {
        document.getElementById("contadorCarrito").innerText = data;
      });
  });
});

function animarProducto(boton) {
  let producto = boton.closest(".producto-card").querySelector("img");
  let carrito = document.querySelector(".carrito-float img");
  let iconoCarrito = document.querySelector(".carrito-float");

  let img = producto.cloneNode(true);

  let rectProducto = producto.getBoundingClientRect();
  let rectCarrito = carrito.getBoundingClientRect();

  img.style.position = "fixed";
  img.style.left = rectProducto.left + "px";
  img.style.top = rectProducto.top + "px";
  img.style.width = "80px";
  img.style.zIndex = "9999";
  img.style.transition = "all 0.7s cubic-bezier(.17,.67,.83,.67)";

  document.body.appendChild(img);

  setTimeout(() => {
    img.style.left = rectCarrito.left + "px";
    img.style.top = rectCarrito.top + "px";
    img.style.width = "25px";
    img.style.opacity = "0.3";
  }, 10);

  setTimeout(() => {
    img.remove();
    /* vibrar carrito */

    iconoCarrito.classList.add("vibrar");

    setTimeout(() => {
      iconoCarrito.classList.remove("vibrar");
    }, 300);
  }, 700);
}
