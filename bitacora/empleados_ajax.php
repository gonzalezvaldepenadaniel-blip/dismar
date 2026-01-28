<?php
require_once("../config/conexion.php");
$con = Conectar::conexion();

// ✅ Convertir a mayúsculas solo texto (no ID)
if($_SERVER["REQUEST_METHOD"] == "POST"){
    foreach($_POST as $k => $v){
        if($k != "usu_id" && $k != "accion"){
            $_POST[$k] = mb_strtoupper($v);
        }
    }
}

// ===================== EDITAR =====================
if(isset($_POST["accion"]) && $_POST["accion"] == "editar"){

    $sql = "UPDATE empleados 
            SET nombre=?, apellidop=?, apellidom=?, area=?, puesto=?, cedis=?, estado=?
            WHERE usu_id=?";

    $stmt = $con->prepare($sql);
    $ok = $stmt->execute([
        $_POST["nombre"],
        $_POST["apellidop"],
        $_POST["apellidom"],
        $_POST["area"],
        $_POST["puesto"],
        $_POST["cedis"],
        $_POST["estado"],
        $_POST["usu_id"]
    ]);

    if($ok){
        echo "OK";
    } else {
        echo "ERROR UPDATE";
    }
    exit;
}

// ===================== ELIMINAR =====================
if(isset($_POST["accion"]) && $_POST["accion"] == "eliminar"){

    $sql = "DELETE FROM empleados WHERE usu_id=?";
    $stmt = $con->prepare($sql);
    $ok = $stmt->execute([$_POST["usu_id"]]);

    if($ok){
        echo "OK";
    } else {
        echo "ERROR DELETE";
    }
    exit;
}
