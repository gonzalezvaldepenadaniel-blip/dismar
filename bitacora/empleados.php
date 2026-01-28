<?php
require_once("../config/conexion.php");
$con = Conectar::conexion();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Empleados</title>
<link rel="stylesheet" href="../public/css/lib/bootstrap/bootstrap.min.css">
</head>
<body class="p-4">

<h3>Empleados</h3>

<button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalEmpleado">
Agregar empleado
</button>

<table class="table table-bordered table-hover">
<thead class="thead-dark">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Área</th>
    <th>Puesto</th>
    <th>Cedis</th>
    <th>Estado</th>
    <th>Acciones</th>
</tr>
</thead>
<tbody>
<?php
$sql = "SELECT * FROM empleados ORDER BY usu_id DESC";
$stmt = $con->prepare($sql);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
?>
<tr>
    <td><?= $row['usu_id'] ?></td>
    <td><?= $row['nombre'] . " " . $row['apellidop'] . " " . $row['apellidom'] ?></td>
    <td><?= $row['area'] ?></td>
    <td><?= $row['puesto'] ?></td>
    <td><?= $row['cedis'] ?></td>
    <td><?= $row['estado'] ?></td>
    <td>
        <button class="btn btn-warning btn-sm btnEditar"
            data-id="<?= $row['usu_id'] ?>"
            data-nombre="<?= $row['nombre'] ?>"
            data-apellidop="<?= $row['apellidop'] ?>"
            data-apellidom="<?= $row['apellidom'] ?>"
            data-area="<?= $row['area'] ?>"
            data-puesto="<?= $row['puesto'] ?>"
            data-cedis="<?= $row['cedis'] ?>"
            data-estado="<?= $row['estado'] ?>"
        >
            ✏️ Editar
        </button>
    </td>
</tr>
<?php
}
?>

</tbody>
</table>

<!-- MODAL EMPLEADO -->
<div class="modal fade" id="modalEmpleado">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Agregar empleado</h5>
<button class="close" data-dismiss="modal">&times;</button>
</div>

<form action="empleados_guardar.php" method="POST">
<div class="modal-body">

<input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" required>
<input type="text" name="apellidop" class="form-control mb-2" placeholder="Apellido paterno" required>
<input type="text" name="apellidom" class="form-control mb-2" placeholder="Apellido materno">

<input type="text" name="area" class="form-control mb-2" placeholder="Área">
<input type="text" name="puesto" class="form-control mb-2" placeholder="Puesto">
<select name="cedis" class="form-control mb-2" required>
    <option value="">Selecciona CEDIS</option>
    <option value="Ecatepec">ECATEPEPEC</option>
    <option value="Iztapalapa">IZTAPALAPA</option>
    <option value="Chicoloapan">CHICOLOAPAN</option>
    <option value="Tultitlán">TULTITLAN</option>
    <option value="Querétaro">QUERETARO</option>
</select>

<select name="estado" class="form-control">
    <option value="ACTIVO">Activo</option>
    <option value="INACTIVO">Inactivo</option>
</select>

</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button class="btn btn-primary">Guardar</button>
</div>

</form>

</div>
</div>
</div>


<!-- MODAL EDITAR EMPLEADO -->
<div class="modal fade" id="modalEditarEmpleado">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Editar empleado</h5>
<button class="close" data-dismiss="modal">&times;</button>
</div>

<form id="formEditarEmpleado">
<div class="modal-body">

<input type="hidden" name="usu_id" id="edit_id">

<input type="text" name="nombre" id="edit_nombre" class="form-control mb-2" required>
<input type="text" name="apellidop" id="edit_apellidop" class="form-control mb-2" required>
<input type="text" name="apellidom" id="edit_apellidom" class="form-control mb-2">

<input type="text" name="area" id="edit_area" class="form-control mb-2">
<input type="text" name="puesto" id="edit_puesto" class="form-control mb-2">

<select name="cedis" id="edit_cedis" class="form-control mb-2">
    <option value="Ecatepec">ECATEPEC</option>
    <option value="Iztapalapa">IZTAPALAPA</option>
    <option value="Chicoloapan">CHICOLOAPAN</option>
    <option value="Tultitlán">TULTITLAN</option>
    <option value="Querétaro">QUERETARO</option>
</select>

<select name="estado" id="edit_estado" class="form-control mb-2">
    <option value="ACTIVO">Activo</option>
    <option value="INACTIVO">Inactivo</option>
</select>

</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<button type="submit" class="btn btn-primary">Actualizar</button>
</div>

</form>

</div>
</div>
</div>


<script src="../public/js/lib/jquery/jquery.min.js"></script>
<script src="../public/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="bitacora.js"></script>
</body>
</html>
<script>
$(document).on("click", ".btnEditar", function(){

    $("#edit_id").val($(this).data("id"));
    $("#edit_nombre").val($(this).data("nombre"));
    $("#edit_apellidop").val($(this).data("apellidop"));
    $("#edit_apellidom").val($(this).data("apellidom"));
    $("#edit_area").val($(this).data("area"));
    $("#edit_puesto").val($(this).data("puesto"));
    $("#edit_cedis").val($(this).data("cedis"));
    $("#edit_estado").val($(this).data("estado"));

    $("#modalEditarEmpleado").modal("show");
});

// GUARDAR EDICIÓN
$("#formEditarEmpleado").submit(function(e){
    e.preventDefault();

    $.ajax({
        url: "empleados_ajax.php",
        type: "POST",
        data: $(this).serialize() + "&accion=editar",
        success: function(res){
            if(res.trim() == "OK"){
                alert("Empleado actualizado");
                location.reload();
            } else {
                alert("Error");
                console.log(res);
            }
        }
    });
});
</script>
