<?php
require_once("../config/conexion.php");

$conexion = (new Conectar())->conexion();

$sql = "SELECT usu_id, usu_nombre, usu_apellido, usu_correo, rol, estado
        FROM tm_usuario
        ORDER BY usu_id DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute();

$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
