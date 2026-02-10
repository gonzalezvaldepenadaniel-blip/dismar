<?php
session_start();
require_once("../../config/conexion.php");

if (!isset($_SESSION["correo_usuario"])) {
    echo json_encode([]);
    exit;
}

$conexion = Conectar::conexion();

$stmt = $conexion->prepare("
    SELECT noti_id, ticket_id, mensaje
    FROM tm_notificacion
    WHERE correo_usuario = ?
    AND leido = 0
    ORDER BY fecha DESC
    LIMIT 5
");

$stmt->execute([$_SESSION["correo_usuario"]]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
