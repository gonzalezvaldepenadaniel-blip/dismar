<?php
session_start();

if (
    !isset($_SESSION["correo_usuario"]) ||
    !in_array($_SESSION["rol"], ["admin","superadmin"])
) {
    header("Location: ../admin-login.php");
    exit();
}

$esSuperAdmin = ($_SESSION['rol'] === 'superadmin');

require_once("../config/conexion.php");
require_once("usuarios_modal.php");
require_once("crud.php");

$con = Conectar::conexion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dismar</title>

<link rel="stylesheet" href="../public/css/lib/bootstrap/bootstrap.min.css">
<link rel="stylesheet" href="/Dismar/Administrador/administrador.css">
<link rel="stylesheet" href="../public/css/lib/font-awesome/font-awesome.min.css">
</head>

<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar">
        <div class="logo">ADMINISTRADOR</div>

        <div class="user">
            <strong><?= htmlspecialchars($_SESSION["usu_nombre"]) ?></strong>
            <span><?= $_SESSION["rol"] ?></span>
        </div>

        <nav>
            <a href="#" onclick="mostrarInicio()">Inicio</a>
            <a href="#" onclick="mostrarReportes()">Reportes</a>

            <?php if ($esSuperAdmin): ?>
                <a href="#" data-toggle="modal" data-target="#modalUsuarios">Usuarios</a>
            <?php endif; ?>

            <a href="../bitacora/index.php">SIE</a>
            <a href="../config/logout.php" class="logout">Cerrar sesión</a>
        </nav>
    </aside>

    <!-- CONTENIDO -->
    <main class="main">

        <!-- HAMBURGUESA -->
        <div id="btnMenu" class="hamburger">☰</div>
        <div id="overlay" class="overlay"></div>

        <!-- INICIO -->
        <section id="seccionInicio">
            <div class="cards">

                <div class="card">
                    <h4>Usuarios</h4>
                    <p>Total: <strong id="totalUsuarios">0</strong></p>
                </div>

                <div class="card">
                    <h4>Tickets</h4>
                    <p>Total: <strong id="totalTickets">0</strong></p>
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
                    <p>Mis tickets: <strong id="ticketsAsignados">0</strong></p>
                </div>

            </div>
        </section>

        <!-- REPORTES -->
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
                        <td colspan="11" class="text-center">Selecciona filtros</td>
                    </tr>
                </tbody>
            </table>

        </section>

    </main>
</div>

<!-- MODAL ATENDER -->
<div class="modal fade" id="modalAtenderTicket">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Atender Ticket</h5>
                <button class="close" data-dismiss="modal">&times;</button>
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

                <?php if ($esSuperAdmin): ?>
                <div class="form-group">
                    <label>Asignar a administrador</label>
                    <select id="usuarioAsignado" class="form-control">
                        <option value="">-- Seleccionar --</option>
                        <?php
                        $stmt = $con->prepare("
                            SELECT usu_id, usu_nombre 
                            FROM tm_usuario 
                            WHERE rol='admin' AND estado=1
                        ");
                        $stmt->execute();
                        while ($u = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$u['usu_id']}'>{$u['usu_nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Comentario</label>
                    <textarea id="comentarioTicket" class="form-control"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" id="btnGuardarTicket">Guardar</button>
            </div>

        </div>
    </div>
</div>

<script src="../public/js/lib/jquery/jquery.min.js"></script>
<script src="../public/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="usuario.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const btnMenu = document.getElementById("btnMenu");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    btnMenu.onclick = () => {
        sidebar.classList.toggle("active");
        overlay.classList.toggle("active");
    };

    overlay.onclick = () => {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
    };
});
</script>

</body>
</html>
