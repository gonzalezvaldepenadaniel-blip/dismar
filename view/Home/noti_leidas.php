<?php

session_start();
require_once("../../config/conexion.php");
$conexion = Conectar::conexion();

$sql = "UPDATE tm_notificacion
        SET leido = 1
        WHERE correo_usuario = :correo";
$stmt = $conexion->prepare($sql);
$stmt->execute([
    ":correo" => $_SESSION["correo_usuario"]
]);
?>