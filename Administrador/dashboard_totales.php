<?php
require_once("../config/conexion.php");
$con = (new Conectar())->conexion();

function obtenerTotal($con, $sql){
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['total'] : 0;
}

echo json_encode([
    "usuarios" => obtenerTotal($con, "SELECT COUNT(*) AS total FROM tm_usuario WHERE estado = 1"),
    "tickets"  => obtenerTotal($con, "SELECT COUNT(*) AS total FROM tm_ticket"),
    "cedis"    => obtenerTotal($con, "SELECT COUNT(DISTINCT cedis) AS total FROM tm_usuario WHERE estado = 1"),
    "abiertos" => obtenerTotal($con, "SELECT COUNT(*) AS total FROM tm_ticket WHERE estado = 1"),
    "proceso"  => obtenerTotal($con, "SELECT COUNT(*) AS total FROM tm_ticket WHERE estado = 2")
]);
