console.log("usuario.js OK");

/* ==========================
   MOSTRAR / OCULTAR CONTRASEÑA
========================== */
$(document).on("change", "#verPass", function () {
    let tipo = this.checked ? "text" : "password";
    $("#usu_pass, #usu_pass_confirm").attr("type", tipo);
});

/* ==========================
   GUARDAR USUARIO
========================== */
$(document).on("submit", "#formUsuario", function (e) {
    e.preventDefault();

    let pass = $("#usu_pass").val();
    let confirm = $("#usu_pass_confirm").val();

    if (pass !== "" || confirm !== "") {
        if (pass !== confirm) {
            alert("Las contraseñas no coinciden");
            return;
        }
        if (pass.length < 6) {
            alert("La contraseña debe tener al menos 6 caracteres");
            return;
        }
    }

    $.post("creaedit.php", $(this).serialize() + "&op=guardar", function () {
        $("#modalUsuario").modal("hide");
        location.reload();
    });
});

/* ==========================
   EDITAR USUARIO (CORRECTO)
========================== */
$(document).on("click", ".editar", function () {

    let data = $(this).data();

    // 1️⃣ cerrar modal lista
    $("#modalUsuarios").modal("hide");

    // 2️⃣ cuando termine de cerrarse, abrir el otro
    $("#modalUsuarios").one("hidden.bs.modal", function () {

        $("#formUsuario")[0].reset();

        $("#usu_id").val(data.id);
        $("#usu_nombre").val(data.nombre);
        $("#usu_apellido").val(data.apellido);
        $("#usu_correo").val(data.correo);
         $("#cedis").val(data.cedis);

        $("#rol").val(data.rol);

        $("#modalUsuario").modal("show");
    });
});

/* ==========================
   NUEVO USUARIO (CORRECTO)
========================== */
function nuevoUsuario() {

    $("#modalUsuarios").modal("hide");

    $("#modalUsuarios").one("hidden.bs.modal", function () {
        $("#formUsuario")[0].reset();
        $("#usu_id").val("");
        $("#modalUsuario").modal("show");
    });
}

/* ==========================
   ELIMINAR
========================== */
$(document).on("click", ".eliminar", function () {

    let id = $(this).data("id");

    if (!confirm("¿Eliminar usuario?")) return;

    $.post("creaedit.php", {
        op: "eliminar",
        usu_id: id
    }, function () {
        location.reload();
    });
});


/* ==========================
   ATENDER TICKET
========================== */
$(document).on("click", ".atender", function () {

    let id = $(this).data("id");
    let estado = $(this).data("estado");
    let comentario = $(this).data("comentario");

    $("#ticket_id").val(id);
    $("#estado").val(estado);
    $("textarea[name='comentario_admin']").val(comentario);

    $("#modalTicket").modal("show");
});


/* ==========================
   GUARDAR TICKET
========================== */
$(document).on("submit", "#formTicket", function (e) {
    e.preventDefault();

    $.post("ticket_update.php", $(this).serialize(), function () {
        $("#modalTicket").modal("hide");
        location.reload();
    });
});
