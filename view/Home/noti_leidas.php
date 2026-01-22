<?php
session_start();
require_once("../../config/conexion.php");

$conexion = Conectar::conexion();

$stmt = $conexion->prepare("
    UPDATE tm_notificacion
    SET leido = 1
    WHERE correo_usuario = :correo
");

$stmt->execute([
    ':correo' => $_SESSION['correo_usuario']
]);
