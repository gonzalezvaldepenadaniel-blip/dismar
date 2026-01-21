<?php
session_start();

if (
    !isset($_SESSION["correo_usuario"]) ||
    !in_array($_SESSION["rol"], ["admin", "superadmin"])
) {
    exit("no-session");
}


require_once("../config/conexion.php");

$conectar = new Conectar();
$conexion = $conectar->conexion();

$ticket_id = $_POST['ticket_id'] ?? null;
$estado    = $_POST['estado'] ?? null;
$asignado  = $_POST['asignado'] ?? null;
$comentario = $_POST['comentario_admin'] ?? '';

if (!$ticket_id || !$estado) {
    exit("datos-incompletos");
}

/* ===== ACTUALIZAR TICKET ===== */
$sql = "UPDATE tm_ticket 
        SET estado = ?, 
            comentario_admin = ?, 
            usu_asignado = ?
        WHERE ticket_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->execute([
    $estado,
    $comentario,
    $asignado,
    $ticket_id
]);

/* ===== NOTIFICACIÓN ===== */
if ($asignado) {

    $sql = "SELECT usu_correo 
            FROM tm_usuario 
            WHERE usu_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$asignado]);
    $correoUsuario = $stmt->fetchColumn();

    if ($correoUsuario) {
        $mensaje = "Ticket #{$ticket_id} fue actualizado";

        $sql = "INSERT INTO tm_notificacion 
                (correo_usuario, ticket_id, mensaje)
                VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            $correoUsuario,
            $ticket_id,
            $mensaje
        ]);
    }
}

echo "ok";
