<?php
session_start();

if (!isset($_SESSION["correo_usuario"]) || $_SESSION["rol"] !== "admin") {
    exit("no-session");
}

require_once("../config/conexion.php");
$conexion = Conectar::conexion();

$ticket_id  = $_POST['ticket_id'] ?? null;
$estado     = $_POST['estado'] ?? null;
$comentario = $_POST['comentario_admin'] ?? '';
$asignado   = $_POST['asignado'] ?? null;

if (!$ticket_id || !$estado) {
    exit("datos-incompletos");
}

/* ACTUALIZAR TICKET */
$sql = "UPDATE tm_ticket
        SET estado = ?, comentario_admin = ?, usu_asignado = ?
        WHERE ticket_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([
    $estado,
    $comentario,
    $asignado,
    $ticket_id
]);

/* OBTENER DATOS DEL TICKET */
$stmt = $conexion->prepare("
    SELECT correo, folio
    FROM tm_ticket
    WHERE ticket_id = ?
");
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

/* INSERTAR NOTIFICACIÓN */
if ($ticket) {

    $mensaje = "Tu ticket {$ticket['folio']} fue actualizado";

    $stmt = $conexion->prepare("
        INSERT INTO tm_notificacion
        (correo_usuario, ticket_id, mensaje)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([
        $ticket['correo'],
        $ticket_id,
        $mensaje
    ]);
}

echo "ok";
?>