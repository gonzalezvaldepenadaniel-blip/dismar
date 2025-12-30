console.log("usuarios.js cargado");

/* ==========================
   FUNCIÓN LIMPIAR MODALES
========================== */
function limpiarModales() {
    $(".modal").modal("hide");
    $(".modal-backdrop").remove();
    $("body").removeClass("modal-open");
}

/* ==========================
   NUEVO USUARIO
========================== */
function nuevoUsuario(){

    limpiarModales();

    setTimeout(function(){
        $("#formUsuario")[0].reset();
        $("#usu_id").val("");
        $("#modalUsuario").modal({
            backdrop: "static",
            keyboard: false
        });
    }, 300);
}

/* ==========================
   EDITAR USUARIO
========================== */
$(document).on("click", ".editar", function(){

    limpiarModales();

    setTimeout(() => {
        $("#usu_id").val($(this).data("id"));
        $("#usu_nombre").val($(this).data("nombre"));
        $("#usu_apellido").val($(this).data("apellido"));
        $("#usu_correo").val($(this).data("correo"));
        $("#rol").val($(this).data("rol"));

        $("#modalUsuario").modal({
            backdrop: "static",
            keyboard: false
        });
    }, 300);
});

/* ==========================
   GUARDAR / EDITAR
========================== */
$("#formUsuario").on("submit", function(e){
    e.preventDefault();

    $.post("creaedit.php", $(this).serialize() + "&op=guardar", function(){
        location.reload();
    });
});

/* ==========================
   ELIMINAR
========================== */
$(document).on("click", ".eliminar", function(){
    if(confirm("¿Eliminar usuario?")){
        $.post("creaedit.php", {
            op: "eliminar",
            usu_id: $(this).data("id")
        }, function(){
            location.reload();
        });
    }
});

/* ==========================
   AL CERRAR FORMULARIO
========================== */
$("#modalUsuario").on("hidden.bs.modal", function(){
    limpiarModales();
    $("#modalUsuarios").modal("show");
});
