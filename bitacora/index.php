<?php
require_once("../config/conexion.php");
$con = (new Conectar())->conexion();
?>
<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">

<title>Dismar</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="/Dismar/bitacora/bitacora.css">

</head>

<body>

<div class="wrapper">

<aside class="sidebar">

<h4 class="logo">SIE</h4>

<ul>

<a href="empleados.php">Empleados</a>

<a href="telefonos.php">Teléfonos</a>

<a href="asignaciones.php">Asignaciones</a>

<li>

<a href="/Dismar/Administrador/administrador.php" class="logout">

Volver

</a>

</li>

</ul>

</aside>



<div class="content">

<div class="topbar">

Inventario de Teléfonos

</div>



<div class="card">

<div class="card-header">

<button class="btn btn-primary" id="btnNuevo">

Nuevo Teléfono

</button>

</div>



<div class="card-body">

<table class="table" id="tablaTelefonos">

<thead>

<tr>

<th>ID</th>

<th>Marca</th>

<th>Imei</th>
<th>Modelo</th>

<th>Serie</th>

<th>Teléfono</th>

<th>Puesto</th>

<th>Área</th>

<th>Usuario</th>

<th>Cedis</th>

<th>Front</th>

<th>Back</th>

<th>Folio</th>

<th>Comentarios</th>

<th>Estatus</th>

</tr>

</thead>



<tbody>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php include("modal.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="bitacora.js"></script>



</body>

</html>
