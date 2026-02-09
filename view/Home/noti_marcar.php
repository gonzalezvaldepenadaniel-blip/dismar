<?php
session_start();
require_once("../../config/conexion.php");

if (!isset($_SESSION["correo_usuario"])) {
    exit("no-session");
}

$conexion = Conectar::conexion();

/* =====================
   MARCAR UNA NOTIFICACIÓN
   ===================== */
if (isset($_POST["noti_id"])) {

    $stmt = $conexion->prepare("
        UPDATE tm_notificacion
        SET leido = 1
        WHERE noti_id = ?
        AND correo_usuario = ?
    ");

    $stmt->execute([
        $_POST["noti_id"],
        $_SESSION["correo_usuario"]
    ]);

    echo "ok-uno";
    exit;
}

/* =====================
   MARCAR TODAS
   ===================== */
if (isset($_POST["todas"])) {

    $stmt = $conexion->prepare("
        UPDATE tm_notificacion
        SET leido = 1
        WHERE correo_usuario = ?
        AND leido = 0
    ");

    $stmt->execute([
        $_SESSION["correo_usuario"]
    ]);

    echo "ok-todas";
    exit;
}

echo "sin-accion";
