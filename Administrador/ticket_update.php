<?php
session_start();

if (!isset($_SESSION["correo_usuario"]) || $_SESSION["rol"] !== "admin") {
    exit("no-session");
}

require_once("../config/conexion.php");

$conectar = new Conectar();
$conexion = $conectar->conexion();

$ticket_id = $_POST['ticket_id'] ?? null;
$estado = $_POST['estado'] ?? null;
$asignado = $_POST['asignado'] ?? null;
$comentario = $_POST['comentario_admin'] ?? '';

if (!$ticket_id || !$estado) {
    exit("datos-incompletos");
}

$sql = "UPDATE tm_ticket 
        SET estado = ?, 
            comentario_admin = ?, 
            usu_asignado  = ?
        WHERE ticket_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->execute([
    $estado,
    $comentario,
    $asignado,
    $ticket_id
]);

echo "ok";
