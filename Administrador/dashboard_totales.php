<?php
session_start();

if (!isset($_SESSION["rol"], $_SESSION["usu_id"])) {
    echo json_encode(["error" => "No autorizado"]);
    exit;
}

require_once("../config/conexion.php");
$con = (new Conectar())->conexion();

$rol    = $_SESSION["rol"];
$usu_id = $_SESSION["usu_id"];

function total($con, $sql, $params = []) {
    $stmt = $con->prepare($sql);
    $stmt->execute($params);
    return (int)$stmt->fetchColumn();
}

/* ============================
   ADMIN → SOLO SUS TICKETS
============================ */
if ($rol === "admin") {

    echo json_encode([
        "abiertos" => total(
            $con,
            "SELECT COUNT(*) 
             FROM tm_ticket 
             WHERE estado = 1 AND usu_asignado = ?",
            [$usu_id]
        ),

        "proceso" => total(
            $con,
            "SELECT COUNT(*) 
             FROM tm_ticket 
             WHERE estado = 2 AND usu_asignado = ?",
            [$usu_id]
        ),

        //ESTE ES EL CONTADOR QUE QUIERES
        "asignados" => total(
            $con,
            "SELECT COUNT(*) 
             FROM tm_ticket 
             WHERE usu_asignado = ?",
            [$usu_id]
        )
    ]);
    exit;
}


/* ============================
   SUPERADMIN 
============================ */
echo json_encode([
    "usuarios" => total(
        $con,
        "SELECT COUNT(*) FROM tm_usuario WHERE estado = 1"
    ),
    "tickets" => total(
        $con,
        "SELECT COUNT(*) FROM tm_ticket"
    ),
    "abiertos" => total(
        $con,
        "SELECT COUNT(*) FROM tm_ticket WHERE estado = 1"
    ),
    "proceso" => total(
        $con,
        "SELECT COUNT(*) FROM tm_ticket WHERE estado = 2"
    ),
    "asignados" => total(
        $con,
        "SELECT COUNT(*) 
         FROM tm_ticket 
         WHERE usu_asignador = ?",
        [$usu_id]
    )
]);
