<?php
session_start();

if (!isset($_SESSION["correo_usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../admin-login.php");
    exit();
}

require_once("../config/conexion.php");
require_once("usuarios_modal.php");
require_once("crud.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dismar - Admin</title>

<link rel="stylesheet" href="../public/css/lib/bootstrap/bootstrap.min.css">
<link rel="stylesheet" href="administrador.css">
</head>

<body>

<!-- ===== NAVBAR ===== -->
<div class="navbar-admin">
  <a href="#" data-toggle="modal" data-target="#modalUsuarios">Usuarios</a>
  <a href="#" onclick="mostrarReportes()">Reportes</a>
  <a href="../index.php">Cerrar sesión</a>
</div>

<div class="content">
  <h2>Bienvenido, <?= htmlspecialchars($_SESSION["usu_nombre"]) ?></h2>
</div>

<!-- ===== REPORTES ===== -->
<div class="content" id="seccionReportes" style="display:none">

<h4>Reportes</h4>

<!-- ===== FILTROS ===== -->
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

<!-- ===== TABLA ===== -->
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
<td colspan="11" class="text-center">Selecciona Reportes</td>
</tr>
</tbody>
</table>

</div>

<!-- ===== SCRIPTS ===== -->
<script src="../public/js/lib/jquery/jquery.min.js"></script>
<script src="../public/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="usuario.js"></script>

<script>
function mostrarReportes(){
    $("#seccionReportes").show();
    cargarTickets(); // carga inicial
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
        beforeSend: function(){
            $("#tablaReportes").html(
                `<tr><td colspan="11" class="text-center">Cargando...</td></tr>`
            );
        },
        success: function(resp){
            $("#tablaReportes").html(resp);
        }
    });
}

$("#btnBuscar").on("click", function(){
    cargarTickets();
});

$("#btnLimpiar").on("click", function(){
    $("#formFiltros")[0].reset();
    cargarTickets();
});
</script>

</body>
</html>
