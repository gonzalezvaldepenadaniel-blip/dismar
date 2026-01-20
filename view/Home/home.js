document.addEventListener("DOMContentLoaded", () => {

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

    /* ===== CAMPANA ===== */
    if (btnCampana && listaNoti) {
        btnCampana.addEventListener("click", e => {
            e.stopPropagation();
            listaNoti.style.display =
                listaNoti.style.display === "block" ? "none" : "block";

            // marcar notificaciones como leídas
            fetch("noti_leidas.php");
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
