<?php

require_once("../config/conexion.php");

$con = Conectar::conexion();

$cedis = $_GET["cedis"];

$sql = "SELECT usu_id, nombre, apellidop, apellidom
FROM empleados
WHERE cedis = ?
AND estado='ACTIVO'
ORDER BY nombre";

$stmt = $con->prepare($sql);

$stmt->execute([$cedis]);

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

echo "<option value='".$row["usu_id"]."'>"

.$row["nombre"]." "

.$row["apellidop"]." "

.$row["apellidom"].

"</option>";

}