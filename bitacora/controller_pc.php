<?php
require_once("../config/conexion.php");
$con = (new Conectar())->conexion();

switch($_GET["op"]){

/* =========================
   LISTAR
========================= */
case "listar":

$sql = "SELECT c.*, e.nombre, e.apellidop 
        FROM computadoras c
        LEFT JOIN empleados e ON c.usu_id = e.usu_id";

$result = $con->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);
break;


/* =========================
   GUARDAR
========================= */
case "guardar":

$sql = "INSERT INTO computadoras 
(marca, procesador, ram, so, disco_duro, tipo, monitor, teclado, mouse, camara, num_serie_cpu, num_serie_monitor, puesto, usu_id, folio, comentarios, estatus)

VALUES(
'".$_POST["marca"]."',
'".$_POST["procesador"]."',
'".$_POST["ram"]."',
'".$_POST["so"]."',
'".$_POST["disco_duro"]."',
'".$_POST["tipo"]."',
'".$_POST["monitor"]."',
'".$_POST["teclado"]."',
'".$_POST["mouse"]."',
'".$_POST["camara"]."',
'".$_POST["num_serie_cpu"]."',
'".$_POST["num_serie_monitor"]."',
'".$_POST["puesto"]."',
'".$_POST["usu_id"]."',
'".$_POST["folio"]."',
'".$_POST["comentarios"]."',
'ACTIVO'
)";

if($con->query($sql)){
    echo "OK";
}else{
    echo "ERROR";
}

break;


/* =========================
   OBTENER (EDITAR)
========================= */
case "obtener":

$id = $_GET["pc_id"];

$sql = "SELECT * FROM computadoras WHERE pc_id = '$id'";
$result = $con->query($sql);

echo json_encode($result->fetch_assoc());

break;


/* =========================
   EDITAR
========================= */
case "editar":

$sql = "UPDATE computadoras SET
marca='".$_POST["marca"]."',
procesador='".$_POST["procesador"]."',
ram='".$_POST["ram"]."',
so='".$_POST["so"]."',
disco_duro='".$_POST["disco_duro"]."',
tipo='".$_POST["tipo"]."',
monitor='".$_POST["monitor"]."',
teclado='".$_POST["teclado"]."',
mouse='".$_POST["mouse"]."',
camara='".$_POST["camara"]."',
num_serie_cpu='".$_POST["num_serie_cpu"]."',
num_serie_monitor='".$_POST["num_serie_monitor"]."',
puesto='".$_POST["puesto"]."',
usu_id='".$_POST["usu_id"]."',
folio='".$_POST["folio"]."',
comentarios='".$_POST["comentarios"]."'
WHERE pc_id='".$_POST["pc_id"]."'";

if($con->query($sql)){
    echo "OK";
}else{
    echo "ERROR";
}

break;


/* =========================
   BAJA
========================= */
case "baja":

$id = $_POST["pc_id"];

$sql = "UPDATE computadoras SET estatus='BAJA' WHERE pc_id='$id'";

if($con->query($sql)){
    echo "OK";
}

break;


/* =========================
   ALTA
========================= */
case "alta":

$id = $_POST["pc_id"];

$sql = "UPDATE computadoras SET estatus='ACTIVO' WHERE pc_id='$id'";

if($con->query($sql)){
    echo "OK";
}

break;

}