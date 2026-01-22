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

    // Validaciones
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

    $.post(
        "creaedit.php",
        $(this).serialize() + "&op=guardar",
        function () {

            // cerrar modal de formulario
            $("#modalUsuario").modal("hide");

            // cuando termine de cerrarse
            $("#modalUsuario").one("hidden.bs.modal", function () {

                // recargar tabla y volver a mostrar usuarios
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

    if (!confirm("¿Eliminar usuario?")) return;

    $.post(
        "creaedit.php",
        { op: "eliminar", usu_id: id },
        function () {
            cargarUsuarios();
        }
    );
});



/* ==========================
   AL ABRIR USUARIOS
========================== */
$('#modalUsuarios').on('show.bs.modal', function () {
    $("#seccionReportes").hide();
    cargarUsuarios(); // siempre refresca
});


/* ==========================
   ABRIR MODAL ATENDER
========================== */
$(document).on("click", ".atender", function () {

    let data = $(this).data();

    $("#ticket_id").val(data.id);
    $("#estadoTicket").val(data.estado);
    $("#comentarioTicket").val(data.comentario || "");

    // ESTA LÍNEA ES LA CLAVE DE TODO
    $("#usuarioAsignado").val(data.asignado || "");

    $("#modalAtenderTicket").modal("show");
});

/* ==========================
   GUARDAR CAMBIOS TICKET
========================== */
$("#btnGuardarTicket").on("click", function () {

    $.ajax({
        url: "ticket_update.php",
        type: "POST",
        data: {
            ticket_id: $("#ticket_id").val(),
            estado: $("#estadoTicket").val(),
            comentario_admin: $("#comentarioTicket").val(),
            asignado: $("#usuarioAsignado").val()

            
        },
        success: function (resp) {

            if (resp.trim() === "ok") {
                $("#modalAtenderTicket").modal("hide");
                cargarTickets();
                cargarDashboard();
            } else {
                alert(resp);
            }

        },
        error: function () {
            alert("Error de conexión");
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

            if (data.asignados !== undefined) {
                $("#ticketsAsignados").text(data.asignados);
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
