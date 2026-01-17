<?php
session_start();

if (!isset($_SESSION["correo_usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../admin-login.php");
    exit();
}

require_once("../config/conexion.php");
require_once("usuarios_modal.php");
require_once("crud.php");

$con = Conectar::conexion();

function obtenerTotal($con, $sql){
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['total'] : 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dismar</title>

<!-- BOOTSTRAP -->
<link rel="stylesheet" href="../public/css/lib/bootstrap/bootstrap.min.css">

<!-- CSS ADMIN -->
<link rel="stylesheet" href="/Dismar/Administrador/administrador.css">
</head>

<body>

<div class="layout">

    <!-- ================= SIDEBAR ================= -->
    <aside id="sidebar" class="sidebar">

        <div class="logo">ADMINISTRADOR</div>

        <div class="user">
            <strong><?= htmlspecialchars($_SESSION["usu_nombre"]) ?></strong>
        </div>

        <nav>
            <a href="#" onclick="mostrarInicio()">Inicio</a>
            <a href="#" onclick="mostrarReportes()">Reportes</a>
            <a href="#" data-toggle="modal" data-target="#modalUsuarios">Usuarios</a>
            <a href="../bitacora/index.php">SIE</a>
            <a href="../config/logout.php" class="logout">Cerrar sesión</a>
        </nav>
    </aside>

    <!-- ================= CONTENIDO ================= -->
    <main class="main">

        <!-- HAMBURGUESA -->
        <div id="btnMenu" class="hamburger">☰</div>
        <div id="overlay" class="overlay"></div>

        <!-- ===== INICIO ===== -->
        <section id="seccionInicio">
            
            <div class="cards">

                <div class="card">
                    <h4>Usuarios</h4>
                    <p>Usuarios registrados: <strong id="totalUsuarios">0</strong></p>
                </div>

                <div class="card">
                    <h4>Tickets</h4>
                    <p>Tickets generados: <strong id="totalTickets">0</strong></p>
                </div>

                <div class="card">
                    <h4>Estatus</h4>
                    <p>
                        Abiertos: <strong id="ticketsAbiertos">0</strong><br>
                        En proceso: <strong id="ticketsProceso">0</strong>
                    </p>
                </div>

                <div class="card">
                    <h4>Asignados</h4>
                    <p>Tickets generados: <strong id="totalTickets">0</strong></p>
                </div>

            </div>
        </section>

        <!-- ===== REPORTES ===== -->
        <section id="seccionReportes" style="display:none">

            <h4>Reportes</h4>

            <form id="formFiltros" class="mb-3">
                <div class="row">

                    <div class="col-md-2">
                        <input type="text" id="f_folio" class="form-control" placeholder="Folio">
                    </div>

                    <div class="col-md-2">
                        <select id="f_cedis" class="form-control">
                            <option value="">Cedis</option>
                            <option>Iztapalapa</option>
                            <option>Ecatepec</option>
                            <option>Tultitlán</option>
                            <option>Corporativo</option>
                            <option>Querétaro</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input type="date" id="f_inicio" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <input type="date" id="f_fin" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <select id="f_estado" class="form-control">
                            <option value="">Estatus</option>
                            <option value="1">Abierto</option>
                            <option value="2">En proceso</option>
                            <option value="3">Cerrado</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select id="f_prioridad" class="form-control">
                            <option value="">Prioridad</option>
                            <option>Alta</option>
                            <option>Media</option>
                            <option>Baja</option>
                        </select>
                    </div>

                </div>

                <div class="mt-2">
                    <button type="button" id="btnBuscar" class="btn btn-primary btn-sm">Buscar</button>
                    <button type="button" id="btnLimpiar" class="btn btn-secondary btn-sm">Limpiar</button>
                </div>
            </form>

            <table class="table table-bordered table-hover table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>Folio</th>
                        <th>Solicita</th>
                        <th>Correo</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Prioridad</th>
                        <th>Cedis</th>
                        <th>Fecha</th>
                        <th>Estatus</th>
                        <th>Evidencia</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaReportes">
                    <tr>
                        <td colspan="11" class="text-center">
                            Selecciona Reportes
                        </td>
                    </tr>
                </tbody>
            </table>

        </section>

    </main>
</div>

<!-- ================= MODAL ATENDER ================= -->
<div class="modal fade" id="modalAtenderTicket" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Atender Ticket</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="ticket_id">

                <div class="form-group">
                    <label>Estatus</label>
                    <select id="estadoTicket" class="form-control">
                        <option value="1">Abierto</option>
                        <option value="2">En proceso</option>
                        <option value="3">Cerrado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Asignar a usuario</label>
                    <select id="usuarioAsignado" class="form-control">
                        <option value="">-- Seleccionar --</option>
                        <?php
                        $stmt = $con->prepare("SELECT usu_id, usu_nombre FROM tm_usuario WHERE rol='admin'");
                        $stmt->execute();
                        while ($u = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$u['usu_id']}'>{$u['usu_nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Comentario</label>
                    <textarea id="comentarioTicket" class="form-control" rows="4"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" id="btnGuardarTicket">Guardar</button>
            </div>

        </div>
    </div>
</div>

<!-- ================= SCRIPTS ================= -->
<script src="../public/js/lib/jquery/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="../public/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="usuario.js"></script>

<!-- HAMBURGUESA -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const btnMenu = document.getElementById("btnMenu");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    btnMenu.addEventListener("click", () => {
        sidebar.classList.toggle("active");
        overlay.classList.toggle("active");
    });

    overlay.addEventListener("click", () => {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
    });
});
</script>

<!-- FUNCIONES -->
<script>
function mostrarInicio(){
    $("#seccionReportes").hide();
    $("#seccionInicio").show();
}

function mostrarReportes(){
    $("#seccionInicio").hide();
    $("#seccionReportes").show();
    cargarTickets();
}

function cargarTickets(){
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
        beforeSend(){
            $("#tablaReportes").html(
                '<tr><td colspan="11" class="text-center">Cargando...</td></tr>'
            );
        },
        success(resp){
            $("#tablaReportes").html(resp);
        }
    });
}

$("#btnBuscar").on("click", cargarTickets);
$("#btnLimpiar").on("click", () => {
    $("#formFiltros")[0].reset();
    cargarTickets();
});

$(document).ready(() => {
    mostrarInicio();
});
</script>

</body>
</html>
