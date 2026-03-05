<?php

require_once("../config/conexion.php");

$con = Conectar::conexion();

$cedis = $_GET["cedis"];

$sql = "SELECT e.usu_id, e.nombre, e.apellidop, e.apellidom
        FROM empleados e
        WHERE e.cedis = ?
        AND e.estado IN ('ACTIVO','REPARACION','BAJA')
        AND NOT EXISTS (
            SELECT 1
            FROM equipos_telefonos t
            WHERE t.usu_id = e.usu_id
            AND t.estatus = 'ACTIVO'
        )
        ORDER BY e.nombre";

$stmt = $con->prepare($sql);
$stmt->execute([$cedis]);

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

    echo "<option value='".$row["usu_id"]."'>"
    .$row["nombre"]." "
    .$row["apellidop"]." "
    .$row["apellidom"].
    "</option>";
}