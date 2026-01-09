document.addEventListener("DOMContentLoaded", () => {

    const btnMenu  = document.getElementById("btnMenu");
    const sidebar  = document.getElementById("sidebar");
    const overlay  = document.getElementById("overlay");

    const nuevo = document.getElementById("seccionNuevo");
    const mis   = document.getElementById("seccionMis");

    const btnNuevo = document.getElementById("btnNuevo");
    const btnMis   = document.getElementById("btnMis");

    /* ===== MENU ===== */
    btnMenu.addEventListener("click", () => {
        sidebar.classList.add("activo");
        overlay.classList.add("activo");
        document.body.classList.add("menu-abierto");
    });

    overlay.addEventListener("click", cerrarMenu);

    function cerrarMenu() {
        sidebar.classList.remove("activo");
        overlay.classList.remove("activo");
        document.body.classList.remove("menu-abierto");
    }

    /* ===== MOSTRAR SECCIONES ===== */
    function mostrar(seccion) {
        if (nuevo) nuevo.style.display = "none";
        if (mis)   mis.style.display   = "none";

        seccion.style.display = "block";
        cerrarMenu();
    }

    /* NUEVO TICKET */
    btnNuevo.addEventListener("click", e => {
        e.preventDefault();
        mostrar(nuevo);
    });

    /* MIS TICKETS */
    btnMis.addEventListener("click", e => {
        e.preventDefault();
        mostrar(mis);
    });

});
