<?php
require_once("../config/conexion.php");
$con = Conectar::conexion();

$sql = "INSERT INTO empleados (nombre, apellidop, apellidom, area, puesto, cedis, estado)
VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql);
$stmt->execute([
    $_POST["nombre"],
    $_POST["apellidop"],
    $_POST["apellidom"],
    $_POST["area"],
    $_POST["puesto"],
    $_POST["cedis"],
    $_POST["estado"]
]);

header("Location: empleados.php");
