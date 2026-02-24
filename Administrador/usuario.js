console.log("usuario.js cargado correctamente");

/* ==========================
   CARGAR USUARIOS (AJAX)
========================== */
function cargarUsuarios() {
    $("#tablaUsuarios").load("usuarios_tabla.php");
}

/* ==========================
   GUARDAR USUARIO
========================== */
$(document).on("submit", "#formUsuario", function (e) {

    e.preventDefault();

    let pass = $("#usu_pass").val();
    let confirm = $("#usu_pass_confirm").val();

    /* VALIDACIONES */

    if (pass !== "" || confirm !== "") {

        if (pass !== confirm) {

            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Las contraseñas no coinciden"
            });

            return;
        }

        if (pass.length < 6) {

            Swal.fire({
                icon: "warning",
                title: "Contraseña débil",
                text: "Debe tener al menos 6 caracteres"
            });

            return;
        }
    }


    /* GUARDAR */

    $.post(
        "creaedit.php",
        $(this).serialize() + "&op=guardar",

        function () {

            $("#modalUsuario").modal("hide");

            $("#modalUsuario").one("hidden.bs.modal", function () {

                Swal.fire({
                    icon: "success",
                    title: "Usuario guardado",
                    text: "El usuario fue registrado correctamente",
                    timer: 1500,
                    showConfirmButton: false
                });

                cargarUsuarios();

                $("#modalUsuarios").modal("show");

            });

        }

    );

});


/* ==========================
   EDITAR USUARIO
========================== */
$(document).on("click", ".editar", function () {

    let data = $(this).data();

    $("#modalUsuarios").modal("hide");

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
   NUEVO USUARIO
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
   ELIMINAR USUARIO
========================== */
$(document).on("click", ".eliminar", function () {

    let id = $(this).data("id");

    Swal.fire({

        title: "¿Eliminar usuario?",
        text: "Esta acción no se puede deshacer",
        icon: "warning",

        showCancelButton: true,

        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",

        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"

    }).then((result) => {

        if (result.isConfirmed) {

            $.post(
                "creaedit.php",
                { op: "eliminar", usu_id: id },

                function () {

                    Swal.fire({
                        icon: "success",
                        title: "Eliminado",
                        text: "Usuario eliminado correctamente",
                        timer: 1500,
                        showConfirmButton: false
                    });

                    cargarUsuarios();

                }

            );

        }

    });

});


/* ==========================
   AL ABRIR USUARIOS
========================== */
$('#modalUsuarios').on('show.bs.modal', function () {

    $("#seccionReportes").hide();

    cargarUsuarios();

});


/* ==========================
   ABRIR MODAL ATENDER
========================== */
$(document).on("click", ".atender", function () {

    let data = $(this).data();

    $("#ticket_id").val(data.id);
    $("#estadoTicket").val(data.estado);
    $("#comentarioTicket").val(data.comentario || "");
    $("#usuarioAsignado").val(data.asignado || "");

    $("#modalAtenderTicket").modal("show");

});


/* ==========================
   GUARDAR CAMBIOS TICKET
========================== */
$("#btnGuardarTicket").click(function () {

    let data = {

        ticket_id: $("#ticket_id").val(),
        estado: $("#estadoTicket").val(),
        comentario: $("#comentarioTicket").val(),
        asignado: $("#usuarioAsignado").val()

    };

    if ($("#prioridadTicket").length) {

        data.prioridad = $("#prioridadTicket").val();

    }

    $.ajax({

        url: "ticket_update.php",

        type: "POST",

        data: data,

        success: function () {

            Swal.fire({
                icon: "success",
                title: "Ticket actualizado",
                timer: 1500,
                showConfirmButton: false
            });

            $("#modalAtenderTicket").modal("hide");

            cargarTickets();

        }

    });

});
/* ==========================
   CARGA TOTALES
========================== */
function cargarDashboard() {
    $.ajax({
        url: "dashboard_totales.php",
        dataType: "json",
        success: function (data) {

            if (data.error) {
                console.error(data.error);
                return;
            }

            // SOLO SI EXISTEN
            if (data.usuarios !== undefined) {
                $("#totalUsuarios").text(data.usuarios);
            }

            if (data.tickets !== undefined) {
                $("#totalTickets").text(data.tickets);
            }

            if (data.abiertos !== undefined) {
                $("#ticketsAbiertos").text(data.abiertos);
            }

            if (data.proceso !== undefined) {
                $("#ticketsProceso").text(data.proceso);
            
            }





            if (data.cerrados !== undefined) {
    $("#ticketsCerrados").text(data.cerrados);
}


            if (data.asignados !== undefined) {
                $("#ticketsAsignados").text(data.asignados);
            }
            /* ==========================
   TICKETS POR CEDIS
========================== */

if (data.cedis !== undefined) {

    $("#cedisIztapalapa").text(data.cedis.Iztapalapa || 0);
    $("#cedisEcatepec").text(data.cedis.Ecatepec || 0);
    $("#cedisTultitlan").text(data.cedis.Tultitlán || 0);
    $("#cedisCorporativo").text(data.cedis.Corporativo || 0);
    $("#cedisQueretaro").text(data.cedis.Querétaro || 0);

}
        },
        error: function () {
            console.error("Error al cargar dashboard");
        }
    });
}

/* ==========================
   AL CARGAR LA PÁGINA
========================== */
$(document).ready(function () {
    cargarDashboard();
});



/* ==========================
   MOSTRAR SECCIONES
========================== */
function mostrarInicio() {
    $("#seccionReportes").hide();
    $("#seccionInicio").show();
}

function mostrarReportes() {
    $("#seccionInicio").hide();
    $("#seccionReportes").show();
    cargarTickets();
}

/* ==========================
   CARGAR TICKETS 
========================== */
function cargarTickets() {

    $.ajax({
        url: "ticket_filtro.php",
        type: "POST",
        data: {
            folio: $("#f_folio").val(),
            cedis: $("#f_cedis").val(),
            inicio: $("#f_inicio").val(),
            fin: $("#f_fin").val(),
            estado: $("#f_estado").val(),
            prioridad: $("#f_prioridad").val()
        },
        beforeSend: function () {
            $("#tablaReportes").html(`
                <tr>
                    <td colspan="11" class="text-center">Cargando...</td>
                </tr>
            `);
        },
        success: function (html) {
            $("#tablaReportes").html(html);
        },
        error: function () {
            $("#tablaReportes").html(`
                <tr>
                    <td colspan="11" class="text-center text-danger">
                        Error al cargar tickets
                    </td>
                </tr>
            `);
        }
    });
}

/* ==========================
   BOTÓN BUSCAR
========================== */
$("#btnBuscar").on("click", function () {

    if (!validarFechas()) return;

    cargarTickets();
});

/* ==========================
   BOTÓN LIMPIAR
========================== */
$("#btnLimpiar").on("click", function () {

    $("#f_folio").val("");
    $("#f_cedis").val("");
    $("#f_inicio").val("");
    $("#f_fin").val("");
    $("#f_estado").val("");
    $("#f_prioridad").val("");

    cargarTickets(); // vuelve a cargar todos
});


/* ==========================
   VALIDAR FECHAS
========================== */
function validarFechas() {

    let inicio = $("#f_inicio").val();
    let fin = $("#f_fin").val();

    if (inicio && fin && fin < inicio) {

        Swal.fire({
            icon: 'warning',
            title: 'Rango de fechas inválido',
            text: 'La fecha final debe ser mayor a la fecha inicial.',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#343a40',
            background: '#ffffff',
            color: '#343a40',
            width: '380px'
        });

        return false;
    }

    return true;
}
/* ==========================
   VER / OCULTAR PASSWORD
========================== */
function verPassword(id, icono) {

    let input = document.getElementById(id);

    if (input.type === "password") {

        input.type = "text";

        icono.innerHTML = '<i class="fa fa-eye-slash"></i>';

    } else {

        input.type = "password";

        icono.innerHTML = '<i class="fa fa-eye"></i>';

    }

}