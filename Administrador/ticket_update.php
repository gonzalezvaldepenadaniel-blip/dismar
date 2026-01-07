<?php
session_start();

if (!isset($_SESSION["correo_usuario"]) || $_SESSION["rol"] !== "admin") {
    exit("no-session");
}

require_once("../config/conexion.php");

$conectar = new Conectar();
$conexion = $conectar->conexion();

$ticket_id = $_POST['ticket_id'] ?? null;
$estado    = $_POST['estado'] ?? null;
$comentario = $_POST['comentario_admin'] ?? '';

if (!$ticket_id || !$estado) {
    exit("datos-incompletos");
}

$sql = "UPDATE tm_ticket 
        SET estado = ?, comentario_admin = ?
        WHERE ticket_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->execute([$estado, $comentario, $ticket_id]);

echo "ok";
