<?php
session_start();

if (!isset($_SESSION["correo_usuario"]) || $_SESSION["rol"] !== "admin") {
    echo json_encode(["error" => "No autorizado"]);
    exit;
}

require_once("../config/conexion.php");
$con = (new Conectar())->conexion();

$correoAdmin = $_SESSION["correo_usuario"];

function obtenerTotal($con, $sql, $params = []) {
    $stmt = $con->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['total'] : 0;
}

echo json_encode([
    /* ==========================
       USUARIOS ACTIVOS
    ========================== */
    "usuarios" => obtenerTotal(
        $con,
        "SELECT COUNT(*) AS total FROM tm_usuario WHERE estado = 1"
    ),

    /* ==========================
       TOTAL TICKETS
    ========================== */
    "tickets" => obtenerTotal(
        $con,
        "SELECT COUNT(*) AS total FROM tm_ticket"
    ),

    /* ==========================
       ESTATUS
    ========================== */
    "abiertos" => obtenerTotal(
        $con,
        "SELECT COUNT(*) AS total FROM tm_ticket WHERE estado = 1"
    ),

    "proceso" => obtenerTotal(
        $con,
        "SELECT COUNT(*) AS total FROM tm_ticket WHERE estado = 2"
    ),

    /* ==========================
       TICKETS ASIGNADOS AL ADMIN
    ========================== */
    "asignados" => obtenerTotal(
        $con,
        "SELECT COUNT(*) AS total
         FROM tm_ticket t
         INNER JOIN tm_usuario u ON u.usu_id = t.usu_asignado
         WHERE u.usu_correo = ?",
        [$correoAdmin]
    )
]);
