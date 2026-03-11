<?php

require_once("../config/conexion.php");

$con = Conectar::conexion();

$cedis = $_GET["cedis"];
$tel_id = $_GET["tel_id"] ?? 0;

$sql = "SELECT e.usu_id, e.nombre, e.apellidop, e.apellidom
        FROM empleados e
        WHERE e.cedis = ?
        AND e.estado = 'ACTIVO'
        AND (
            NOT EXISTS (
                SELECT 1
                FROM equipos_telefonos t
                WHERE t.usu_id = e.usu_id
                AND t.estatus = 'ACTIVO'
            )
            OR EXISTS (
                SELECT 1
                FROM equipos_telefonos t
                WHERE t.usu_id = e.usu_id
                AND t.tel_id = ?
            )
        )
        ORDER BY e.nombre";

$stmt = $con->prepare($sql);
$stmt->execute([$cedis,$tel_id]);

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

echo "<option value='".$row["usu_id"]."'>"
.$row["nombre"]." "
.$row["apellidop"]." "
.$row["apellidom"].
"</option>";

}