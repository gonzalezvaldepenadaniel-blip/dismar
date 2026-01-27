<?php
require_once("../config/conexion.php");
$con = (new Conectar())->conexion();

$op = $_GET["op"] ?? "";

/* ============================
   LISTAR TELÉFONOS
============================ */
if ($op === "listar") {

    $sql = "SELECT * FROM equipos_telefonos ORDER BY tel_id DESC";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

/* ============================
   GUARDAR TELÉFONO
============================ */
if ($op === "guardar") {

    $sql = "INSERT INTO equipos_telefonos
    (marca, modelo, num_serie, num_telefono, puesto, area, nombre_usuario, cedis, front, back, folio, comentarios, estatus)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACTIVO')";

    $stmt = $con->prepare($sql);
    $stmt->execute([
        $_POST["marca"],
        $_POST["modelo"],
        $_POST["num_serie"],
        $_POST["num_telefono"],
        $_POST["puesto"],
        $_POST["area"],
        $_POST["nombre_usuario"],
        $_POST["cedis"], // 👈 IMPORTANTE
        $_POST["front"],
        $_POST["back"],
        $_POST["folio"],
        $_POST["comentarios"]
    ]);

    echo "OK";
    exit;
}

/* ============================
   BAJA TELÉFONO
============================ */
if ($op === "baja") {

    $sql = "UPDATE equipos_telefonos SET estatus='BAJA' WHERE tel_id=?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$_POST["tel_id"]]);

    echo "OK";
    exit;
}
