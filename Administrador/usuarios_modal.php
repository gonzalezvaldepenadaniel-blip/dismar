<?php
require_once("../config/conexion.php");

$conectar = new Conectar();
$conexion = $conectar->conexion();

$sql = "SELECT usu_nombre, usu_apellido, usu_correo, rol, estado 
        FROM tm_usuario";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
