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

$ticket_id  = $_POST['ticket_id'] ?? null;
$estado     = $_POST['estado'] ?? null;
$asignado   = $_POST['asignado'] ?? null;
$comentario = $_POST['comentario_admin'] ?? '';

if (!$ticket_id || !$estado) {
    exit("datos-incompletos");
}

/* aparece ticket que solo fuiste asignado*/
if ($asignado === "" || $asignado === "0") {
    $asignado = null;
}

/* SI ES ADMIN, SOLO PUEDE ASIGNARSE A SÍ MISMO */
if ($_SESSION["rol"] === "admin") {
    $asignado = $_SESSION["usu_id"];
}

/* ===== UPDATE ===== */
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

/* ===== NOTIFICACIÓN AL USUARIO DEL TICKET ===== */
$stmt = $conexion->prepare(
    "SELECT correo, folio FROM tm_ticket WHERE ticket_id = ?"
);
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if ($ticket) {

    $mensaje = "Tu ticket {$ticket['folio']} fue actualizado";

    $stmt = $conexion->prepare(
        "INSERT INTO tm_notificacion 
         (correo_usuario, ticket_id, mensaje)
         VALUES (?, ?, ?)"
    );
    $stmt->execute([
        $ticket['correo'],
        $ticket_id,
        $mensaje
    ]);
}



echo "ok";
