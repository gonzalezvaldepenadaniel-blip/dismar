document.addEventListener("DOMContentLoaded", () => {

  /* ===== VER MÁS / VER MENOS ===== */
  document.addEventListener("click", function (e) {
    const btn = e.target.closest(".ver-mas");
    if (!btn) return;

    e.stopPropagation(); // evita abrir modal

    const texto = btn.previousElementSibling;
    if (!texto) return;

    texto.classList.toggle("expandido");

    btn.textContent = texto.classList.contains("expandido")
      ? "Ver menos"
      : "Ver más";
  });

  /* ===== ELEMENTOS ===== */
  const btnMenu   = document.getElementById("btnMenu");
  const sidebar   = document.getElementById("sidebar");
  const overlay   = document.getElementById("overlay");

  const home  = document.getElementById("seccionHome");
  const nuevo = document.getElementById("seccionNuevo");
  const mis   = document.getElementById("seccionMis");

  const btnInicio      = document.getElementById("btnInicio");
  const btnMis         = document.getElementById("btnMis");
  const btnCrearTicket = document.getElementById("btnCrearTicket");

  const btnCampana = document.getElementById("btnCampana");
  const listaNoti  = document.getElementById("listaNoti");

  const userBtn  = document.getElementById("topUserBtn");
  const userDrop = document.getElementById("topUserDropdown");

  /* ===== FUNCIONES ===== */
  function cerrarMenu() {
    sidebar.classList.remove("activo");
    overlay.classList.remove("activo");
    document.body.classList.remove("menu-abierto");
  }

  function ocultarTodo() {
    home.style.display  = "none";
    nuevo.style.display = "none";
    mis.style.display   = "none";
  }

  function mostrar(seccion) {
    ocultarTodo();
    seccion.style.display = "block";
    cerrarMenu();
  }

  /* ===== MENU ===== */
  if (btnMenu) {
    btnMenu.addEventListener("click", () => {
      sidebar.classList.add("activo");
      overlay.classList.add("activo");
      document.body.classList.add("menu-abierto");
    });
  }

  if (overlay) {
    overlay.addEventListener("click", cerrarMenu);
  }

  /* ===== NAVEGACIÓN ===== */
  if (btnInicio) {
    btnInicio.addEventListener("click", e => {
      e.preventDefault();
      mostrar(home);
    });
  }

  if (btnMis) {
    btnMis.addEventListener("click", e => {
      e.preventDefault();
      mostrar(mis);
    });
  }

  if (btnCrearTicket) {
    btnCrearTicket.addEventListener("click", () => {
      mostrar(nuevo);
    });
  }

  /* ===== CAMPANA 🔔 ===== */
  if (btnCampana && listaNoti) {
    btnCampana.addEventListener("click", e => {
      e.stopPropagation();

      listaNoti.style.display =
        listaNoti.style.display === "block" ? "none" : "block";

      fetch("noti_leidas.php").then(() => {
        const badge = document.querySelector("#btnCampana .badge");
        if (badge) {
          badge.remove(); // quitar contador
        }
      });
    });
  }

  /* ===== USUARIO ===== */
  if (userBtn && userDrop) {
    userBtn.addEventListener("click", e => {
      e.stopPropagation();
      userDrop.style.display =
        userDrop.style.display === "block" ? "none" : "block";
    });
  }

  /* ===== CERRAR TODO AL HACER CLICK FUERA ===== */
  document.addEventListener("click", () => {
    if (listaNoti) listaNoti.style.display = "none";
    if (userDrop)  userDrop.style.display = "none";
  });

});


/* ================= MODAL TICKET ================= */

// abrir modal desde NOTIFICACIÓN (delegación de eventos)
document.addEventListener("click", function(e) {

  const noti = e.target.closest(".noti-ticket");
  if (!noti) return;

  e.stopPropagation();

  let ticketId = noti.dataset.ticketId;
  let notiId   = noti.dataset.notiId;

  // marcar notificación como leída
  fetch("noti_marcar.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: "noti_id=" + notiId
  })
  .then(res => res.text())
  .then(resp => {
    if (resp.trim() === "ok") {
      noti.remove(); // eliminar notificación
    }
  });

  // abrir modal del ticket
  abrirModalTicket(ticketId);
});


// función AJAX para traer datos reales del ticket
function abrirModalTicket(ticketId) {
  fetch("ajax_ticket.php?ticket_id=" + ticketId)
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        alert(data.error);
        return;
      }

      document.getElementById("modalEstado").innerText = data.estado;
      document.getElementById("modalAsignado").innerText = data.asignado;
      document.getElementById("modalComentario").innerText = data.comentario;

      document.getElementById("modalTicket").style.display = "flex";
    });
}

// cerrar modal
document.querySelector(".close-modal").addEventListener("click", function () {
  document.getElementById("modalTicket").style.display = "none";
});

window.addEventListener("click", function(e) {
  if (e.target == document.getElementById("modalTicket")) {
    document.getElementById("modalTicket").style.display = "none";
  }
});
