console.log("usuarios.js cargado");

function nuevoUsuario(){
    $("#formUsuario")[0].reset();
    $("#usu_id").val("");
    $("#modalUsuario").modal("show");
}

// GUARDAR / EDITAR
$("#formUsuario").on("submit", function(e){
    e.preventDefault();

    $.post("creaedit.php", $(this).serialize() + "&op=guardar", function(){
        location.reload();
    });
});

// EDITAR
$(document).on("click", ".editar", function(){
    $("#usu_id").val($(this).data("id"));
    $("#usu_nombre").val($(this).data("nombre"));
    $("#usu_apellido").val($(this).data("apellido"));
    $("#usu_correo").val($(this).data("correo"));
    $("#rol").val($(this).data("rol"));

    $("#modalUsuario").modal("show");
});

// ELIMINAR
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
