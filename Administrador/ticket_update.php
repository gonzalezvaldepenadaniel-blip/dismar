<?php
require_once("../config/conexion.php");

$conectar = new Conectar();
$conexion = $conectar->conexion();
$conectar->set_names();

$ticket_id = $_POST['ticket_id'];
$estado = $_POST['estado'];
$comentario = $_POST['comentario_admin'];

$sql = "UPDATE tm_ticket
        SET estado = :estado,
            comentario_admin = :comentario
        WHERE ticket_id = :id";

$stmt = $conexion->prepare($sql);
$stmt->execute([
    ":estado" => $estado,
    ":comentario" => $comentario,
    ":id" => $ticket_id
]);

echo "ok";
