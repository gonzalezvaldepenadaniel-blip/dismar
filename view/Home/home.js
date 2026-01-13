document.addEventListener("DOMContentLoaded", () => {

    /* ===== MENU / SIDEBAR ===== */
    const btnMenu  = document.getElementById("btnMenu");
    const sidebar  = document.getElementById("sidebar");
    const overlay  = document.getElementById("overlay");

    const home  = document.getElementById("seccionHome");
    const nuevo = document.getElementById("seccionNuevo");
    const mis   = document.getElementById("seccionMis");

    const btnInicio = document.getElementById("btnInicio");
    const btnMis    = document.getElementById("btnMis");
    const btnCrearTicket = document.getElementById("btnCrearTicket");

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

    if (btnInicio) btnInicio.onclick = e => { e.preventDefault(); mostrar(home); };
    if (btnMis) btnMis.onclick = e => { e.preventDefault(); mostrar(mis); };
    if (btnCrearTicket) btnCrearTicket.onclick = () => mostrar(nuevo);

    /* ===== CAMPANA ===== */
    const btnCampana = document.getElementById("btnCampana");
    

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


/* ===== MENU USUARIO ===== */
const topUserBtn = document.getElementById("topUserBtn");
const topUserDropdown = document.getElementById("topUserDropdown");

if (topUserBtn) {
    topUserBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        topUserDropdown.style.display =
            topUserDropdown.style.display === "block" ? "none" : "block";
    });
}

/* CERRAR AL HACER CLICK FUERA */
document.addEventListener("click", function () {
    if (topUserDropdown) {
        topUserDropdown.style.display = "none";
    }
});




$(document).ready(function(){
    $(".campana").click(function(){
        $(".lista-noti").toggle();
    });

    setInterval(function(){
        $.get("noti_count.php", function(data){
            if(data > 0){
                $(".campana span").text(data).show();
            } else {
                $(".campana span").hide();
            }
        });
    }, 15000);
});













document.addEventListener("DOMContentLoaded", () => {

    const btnCampana = document.getElementById("btnCampana");
    const listaNoti  = document.querySelector(".lista-noti");

    const userBtn = document.getElementById("topUserBtn");
    const userDrop = document.getElementById("topUserDropdown");

    /* CAMPANA */
    if (btnCampana && listaNoti) {
        btnCampana.addEventListener("click", (e) => {
            e.stopPropagation();
            listaNoti.style.display =
                listaNoti.style.display === "block" ? "none" : "block";
        });
    }

    /* USUARIO */
    if (userBtn && userDrop) {
        userBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            userDrop.style.display =
                userDrop.style.display === "block" ? "none" : "block";
        });
    }

    document.addEventListener("click", () => {
        if (listaNoti) listaNoti.style.display = "none";
        if (userDrop) userDrop.style.display = "none";
    });

});
