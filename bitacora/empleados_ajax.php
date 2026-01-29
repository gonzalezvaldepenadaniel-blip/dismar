<?php
require_once("../config/conexion.php");
$con = Conectar::conexion();

//  función segura para POST
function post($key){
    return isset($_POST[$key]) ? mb_strtoupper(trim($_POST[$key])) : "";
}

// ===================== EDITAR =====================
if(isset($_POST["accion"]) && $_POST["accion"] == "editar"){

    if(!isset($_POST["usu_id"]) || $_POST["usu_id"] == ""){
        echo "ERROR: ID NO RECIBIDO";
        exit;
    }

    $sql = "UPDATE empleados 
            SET nombre=?, apellidop=?, apellidom=?, area=?, puesto=?, cedis=?, estado=?
            WHERE usu_id=?";

    $stmt = $con->prepare($sql);
    $ok = $stmt->execute([
        post("nombre"),
        post("apellidop"),
        post("apellidom"),
        post("area"),
        post("puesto"),
        post("cedis"),
        post("estado"),
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

    if(!isset($_POST["usu_id"]) || $_POST["usu_id"] == ""){
        echo "ERROR: ID NO RECIBIDO";
        exit;
    }

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
