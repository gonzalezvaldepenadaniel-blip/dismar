document.addEventListener("DOMContentLoaded", () => {

    const btnMenu = document.getElementById("btnMenu");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    function abrirMenu() {
        sidebar.classList.add("activo");
        overlay.classList.add("activo");
        document.body.classList.add("menu-abierto");
    }

    function cerrarMenu() {
        sidebar.classList.remove("activo");
        overlay.classList.remove("activo");
        document.body.classList.remove("menu-abierto");
    }

    btnMenu.addEventListener("click", abrirMenu);
    overlay.addEventListener("click", cerrarMenu);

    // Cerrar si presiona ESC
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") cerrarMenu();
    });
});
