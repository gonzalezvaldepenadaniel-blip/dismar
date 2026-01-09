document.addEventListener("DOMContentLoaded", () => {

    /* ===== ELEMENTOS MENU ===== */
    const btnMenu  = document.getElementById("btnMenu");
    const sidebar  = document.getElementById("sidebar");
    const overlay  = document.getElementById("overlay");

    /* ===== SECCIONES ===== */
    const home  = document.getElementById("seccionHome");
    const nuevo = document.getElementById("seccionNuevo");
    const mis   = document.getElementById("seccionMis");

    /* ===== BOTONES SIDEBAR ===== */
    const btnInicio = document.getElementById("btnInicio");
    const btnNuevo  = document.getElementById("btnNuevo");
    const btnMis    = document.getElementById("btnMis");

    /* ===== BOTÓN HOME ===== */
    const btnCrearTicket = document.getElementById("btnCrearTicket");

    /* =========================
       MENU HAMBURGUESA
    ========================= */
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

    function cerrarMenu() {
        sidebar.classList.remove("activo");
        overlay.classList.remove("activo");
        document.body.classList.remove("menu-abierto");
    }

    /* =========================
       MOSTRAR SECCIONES
    ========================= */
    function ocultarTodo() {
        if (home)  home.style.display  = "none";
        if (nuevo) nuevo.style.display = "none";
        if (mis)   mis.style.display   = "none";
    }

    function mostrar(seccion) {
        ocultarTodo();
        seccion.style.display = "block";
        cerrarMenu();
    }

    /* =========================
       EVENTOS
    ========================= */

    /* INICIO */
    if (btnInicio) {
        btnInicio.addEventListener("click", e => {
            e.preventDefault();
            mostrar(home);
        });
    }

    /* NUEVO TICKET (SIDEBAR) */
    if (btnNuevo) {
        btnNuevo.addEventListener("click", e => {
            e.preventDefault();
            mostrar(nuevo);
        });
    }

    /* MIS TICKETS */
    if (btnMis) {
        btnMis.addEventListener("click", e => {
            e.preventDefault();
            mostrar(mis);
        });
    }

    /* NUEVO TICKET (BOTÓN HOME) */
    if (btnCrearTicket) {
        btnCrearTicket.addEventListener("click", () => {
            mostrar(nuevo);
        });
    }

});
