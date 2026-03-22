function toggleMenu() {
  let menu = document.getElementById("submenuUsuario");
  let flecha = document.querySelector(".flecha-usuario");
  menu.classList.toggle("activo");
  flecha.classList.toggle("rotada");
}
document.addEventListener("click", function (e) {
  let menu = document.getElementById("submenuUsuario");
  let flecha = document.querySelector(".flecha-usuario");
  if (!menu.contains(e.target) && !flecha.contains(e.target)) {
    menu.classList.remove("activo");
    flecha.classList.remove("rotada");
  }
});
