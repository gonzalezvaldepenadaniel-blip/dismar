document.addEventListener("DOMContentLoaded", () => {

    /* ===== MENU ===== */
    const btnMenu  = document.getElementById("btnMenu");
    const sidebar  = document.getElementById("sidebar");
    const overlay  = document.getElementById("overlay");

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

    /* ===== CAMPANA ===== */
    const btnCampana = document.getElementById("btnCampana");
    const listaNoti  = document.querySelector(".lista-noti");

    if (btnCampana && listaNoti) {
        btnCampana.addEventListener("click", e => {
            e.stopPropagation();
            listaNoti.style.display =
                listaNoti.style.display === "block" ? "none" : "block";
        });
    }

    /* ===== USUARIO ===== */
    const userBtn  = document.getElementById("topUserBtn");
    const userDrop = document.getElementById("topUserDropdown");

    if (userBtn && userDrop) {
        userBtn.addEventListener("click", e => {
            e.stopPropagation();
            userDrop.style.display =
                userDrop.style.display === "block" ? "none" : "block";
        });
    }

    document.addEventListener("click", () => {
        if (listaNoti) listaNoti.style.display = "none";
        if (userDrop)  userDrop.style.display = "none";
    });

});
