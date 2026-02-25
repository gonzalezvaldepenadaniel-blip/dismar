<?php
require_once("../config/conexion.php");
$con = (new Conectar())->conexion();

$op = $_GET["op"] ?? "";

// ✅ Convertir a mayúsculas SOLO si hay POST
if($_SERVER["REQUEST_METHOD"] == "POST"){
    foreach($_POST as $k => $v){
        $_POST[$k] = mb_strtoupper($v);
    }
}


/* ============================
   LISTAR TELÉFONOS
============================ */
if ($op === "listar") {

    $sql = "SELECT 
t.*,
e.nombre,
e.apellidop,
e.apellidom,
e.area,
e.puesto,
e.cedis

FROM equipos_telefonos t
LEFT JOIN empleados e ON e.usu_id = t.usu_id

ORDER BY t.tel_id DESC";
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
    (
        marca,
        modelo,
        num_serie,
        num_telefono,
        usu_id,
        front,
        back,
        folio,
        comentarios,
        estatus
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACTIVO')";

    $stmt = $con->prepare($sql);

    $stmt->execute([
        $_POST["marca"],
        $_POST["modelo"],
        $_POST["num_serie"],
        $_POST["num_telefono"],
        $_POST["usu_id"],
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
