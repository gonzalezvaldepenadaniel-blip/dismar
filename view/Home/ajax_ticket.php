<?php
require_once("../../config/conexion.php");
$conexion = Conectar::conexion();

if (!isset($_GET['ticket_id'])) {
    echo json_encode(["error" => "Sin ticket"]);
    exit;
}

$ticket_id = $_GET['ticket_id'];

$stmt = $conexion->prepare("
    SELECT 
       
        t.descripcion,
        t.estado,
        t.comentario_admin,
        CONCAT(u.usu_nombre,' ',u.usu_apellido) AS asignado
    FROM tm_ticket t
    LEFT JOIN tm_usuario u ON u.usu_id = t.usu_asignado
    WHERE t.ticket_id = :id
    LIMIT 1
");

$stmt->execute([":id" => $ticket_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    echo json_encode(["error" => "Ticket no encontrado"]);
    exit;
}

switch($ticket['estado']) {
    case 1: $estado = "Abierto"; break;
    case 2: $estado = "En Proceso"; break;
    case 3: $estado = "Cerrado"; break;
    default: $estado = "-";
}

echo json_encode([
    
    "estado"     => $estado,
    "asignado"   => $ticket['asignado'] ?: "Sin asignar",
    "comentario" => $ticket['comentario_admin'] ?: "Sin comentarios",
    "descripcion"=> $ticket['descripcion']
]);
