<?php
session_start();
require_once("../../config/conexion.php");

if (!isset($_SESSION["correo_usuario"])) {
    exit("no-session");
}

if (!isset($_POST["noti_id"])) {
    exit("sin-id");
}

$conexion = Conectar::conexion();

$noti_id = $_POST["noti_id"];

$stmt = $conexion->prepare("
    UPDATE tm_notificacion 
    SET leido = 1 
    WHERE noti_id = ? 
    AND correo_usuario = ?
");

$stmt->execute([
    $noti_id,
    $_SESSION["correo_usuario"]
]);

echo "ok";
