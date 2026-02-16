document.addEventListener("DOMContentLoaded", () => {

  /* ===== VER MÁS / VER MENOS ===== */
  document.addEventListener("click", e => {
    const btn = e.target.closest(".ver-mas");
    if (!btn) return;

    e.stopPropagation();
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
  btnMenu?.addEventListener("click", () => {
    sidebar.classList.add("activo");
    overlay.classList.add("activo");
    document.body.classList.add("menu-abierto");
  });

  overlay?.addEventListener("click", cerrarMenu);

  /* ===== NAVEGACIÓN ===== */
  btnInicio?.addEventListener("click", e => {
    e.preventDefault();
    mostrar(home);
  });

  btnMis?.addEventListener("click", e => {
    e.preventDefault();
    mostrar(mis);
  });

  btnCrearTicket?.addEventListener("click", () => {
    mostrar(nuevo);
  });

  /* ===== CAMPANA 🔔 ===== */
  btnCampana?.addEventListener("click", e => {
    e.stopPropagation();
    listaNoti.style.display =
      listaNoti.style.display === "block" ? "none" : "block";
  });

  /* ===== USUARIO ===== */
  userBtn?.addEventListener("click", e => {
    e.stopPropagation();
    userDrop.style.display =
      userDrop.style.display === "block" ? "none" : "block";
  });

  /* ===== CERRAR TODO AL HACER CLICK FUERA ===== */
  document.addEventListener("click", () => {
    listaNoti.style.display = "none";
    userDrop.style.display  = "none";
  });

});


/* ================= NOTIFICACIONES ================= */

// clic en notificación
document.addEventListener("click", e => {

  const noti = e.target.closest(".noti-ticket");
  if (!noti) return;

  e.stopPropagation();

  const ticketId = noti.dataset.ticketId;
  const notiId   = noti.dataset.notiId;

  fetch("noti_marcar.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "noti_id=" + notiId
  }).finally(() => {

    // quitar del DOM
    noti.remove();

    // actualizar badge
    const badge = document.querySelector("#btnCampana .badge");
    if (badge) {
      const n = parseInt(badge.textContent);
      n > 1 ? badge.textContent = n - 1 : badge.remove();
    }

  });

  abrirModalTicket(ticketId);
});


/* ================= MODAL TICKET ================= */

function abrirModalTicket(ticketId) {
  fetch("ajax_ticket.php?ticket_id=" + ticketId)
    .then(res => res.json())
    .then(data => {
      if (data.error) return alert(data.error);

      document.getElementById("modalEstado").innerText     = data.estado;
      document.getElementById("modalAsignado").innerText  = data.asignado;
      document.getElementById("modalComentario").innerText= data.comentario;

      document.getElementById("modalTicket").style.display = "flex";
    });
}

document.querySelector(".close-modal")?.addEventListener("click", () => {
  document.getElementById("modalTicket").style.display = "none";
});

window.addEventListener("click", e => {
  if (e.target === document.getElementById("modalTicket")) {
    document.getElementById("modalTicket").style.display = "none";
  }
});


/* ================= CARGA DE NOTIFICACIONES ================= */

function cargarNotificaciones() {
  fetch("noti_ajax.php")
    .then(res => res.json())
    .then(data => {

      const lista   = document.getElementById("listaNoti");
      const campana = document.getElementById("btnCampana");
      if (!lista || !campana) return;

      /* ===== BADGE ===== */
      let badge = campana.querySelector(".badge");

      if (data.length === 0) {
        badge?.remove();
      } else {
        if (!badge) {
          badge = document.createElement("span");
          badge.className = "badge";
          campana.appendChild(badge);
        }
        badge.textContent = data.length;
      }

      /* ===== LISTA ===== */
      lista.innerHTML = "";

      if (data.length === 0) {
        const vacia = document.createElement("div");
        vacia.className = "noti-vacia";
        vacia.textContent = "No hay notificaciones";
        lista.appendChild(vacia);
        return;
      }

      data.forEach(n => {
        const div = document.createElement("div");
        div.className = "noti-item noti-ticket";
        div.dataset.ticketId = n.ticket_id;
        div.dataset.notiId   = n.noti_id;
        div.textContent = n.mensaje;
        lista.appendChild(div);
      });

    });
}

/* ================= VALIDAR ARCHIVO ANTES DE ENVIAR ================= */

document.addEventListener("DOMContentLoaded", () => {

    const form = document.querySelector("form");
    const inputFile = document.getElementById("evidencia");
    const errorText = document.getElementById("errorEvidencia");

    const tiposPermitidos = [
        "image/jpeg",
        "image/png",
        "image/webp"
    ];

    form.addEventListener("submit", function (e) {

        if (inputFile.files.length === 0) return;

        const archivo = inputFile.files[0];

        if (!tiposPermitidos.includes(archivo.type)) {
            e.preventDefault(); // 
            errorText.style.display = "block";
            inputFile.value = ""; // limpia el archivo
        } else {
            errorText.style.display = "none";
        }

    });

});












setInterval(cargarNotificaciones, 2000);
cargarNotificaciones();