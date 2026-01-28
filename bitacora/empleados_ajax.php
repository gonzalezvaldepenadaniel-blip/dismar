<?php
require_once("../config/conexion.php");
$con = Conectar::conexion();

if($_POST["accion"] == "editar"){

    $sql = "UPDATE empleados 
    SET nombre=?, apellidop=?, apellidom=?, area=?, puesto=?, cedis=?, estado=?
    WHERE usu_id=?";

    $stmt = $con->prepare($sql);
    $stmt->execute([
        $_POST["nombre"],
        $_POST["apellidop"],
        $_POST["apellidom"],
        $_POST["area"],
        $_POST["puesto"],
        $_POST["cedis"],
        $_POST["estado"],
        $_POST["usu_id"]
    ]);

    echo "OK";
}
