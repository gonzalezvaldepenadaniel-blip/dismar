<?php
require_once("../config/conexion.php");
$con = (new Conectar())->conexion();

$op = $_GET["op"] ?? "";

if ($op === "listar") {
    $sql = "SELECT * FROM equipos_telefonos ORDER BY tel_id DESC";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($op === "guardar") {
    $sql = "INSERT INTO equipos_telefonos
        (marca, modelo, num_serie, num_telefono, comentarios)
        VALUES (?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    $stmt->execute([
        $_POST["marca"],
        $_POST["modelo"],
        $_POST["num_serie"],
        $_POST["num_telefono"],
        $_POST["comentarios"]
    ]);

    echo "OK";
    exit;
}

if ($op === "baja") {
    $sql = "UPDATE equipos_telefonos SET estatus='BAJA' WHERE tel_id=?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$_POST["tel_id"]]);

    echo "OK";
    exit;
}
