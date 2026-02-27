<?php
require_once("../config/conexion.php");
$con = (new Conectar())->conexion();

$op = $_GET["op"] ?? "";

// Convertir a mayúsculas SOLO si hay POST
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

    try {

        // 1️⃣ Obtener datos del empleado
        $sqlEmp = "SELECT nombre, apellidop, apellidom, area, puesto 
                   FROM empleados 
                   WHERE usu_id = ?";
        $stmtEmp = $con->prepare($sqlEmp);
        $stmtEmp->execute([$_POST["usu_id"]]);
        $emp = $stmtEmp->fetch(PDO::FETCH_ASSOC);

        if(!$emp){
            echo "ERROR: Empleado no encontrado";
            exit;
        }

        $nombreCompleto = $emp["nombre"] . " " . $emp["apellidop"] . " " . $emp["apellidom"];

        // 2️⃣ Manejar imágenes
        $frontNombre = "";
        $backNombre = "";

        if(isset($_FILES["front"]) && $_FILES["front"]["error"] == 0){
            $frontNombre = time()."_front_".$_FILES["front"]["name"];
            move_uploaded_file($_FILES["front"]["tmp_name"], "../public/telefonos/".$frontNombre);
        }

        if(isset($_FILES["back"]) && $_FILES["back"]["error"] == 0){
            $backNombre = time()."_back_".$_FILES["back"]["name"];
            move_uploaded_file($_FILES["back"]["tmp_name"], "../public/telefonos/".$backNombre);
        }

        // 3️⃣ Insertar teléfono
        $sql = "INSERT INTO equipos_telefonos
        (marca, modelo, num_serie, num_telefono, imei, puesto, area, nombre_usuario, front, back, comentarios, folio, estatus, usu_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sql);

        $stmt->execute([
            $_POST["marca"],
            $_POST["modelo"],
            $_POST["num_serie"],
            $_POST["num_telefono"],
            $_POST["imei"],
            $emp["puesto"],
            $emp["area"],
            $nombreCompleto,
            $frontNombre,
            $backNombre,
            $_POST["comentarios"],
            $_POST["folio"],
            'ACTIVO',
            $_POST["usu_id"]
        ]);

        echo "OK";

    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage();
    }

    exit;
}
/* ============================
   alta y baja
============================ */
// DAR DE BAJA
if ($op === "baja") {

    $sql = "UPDATE equipos_telefonos SET estatus='BAJA' WHERE tel_id=?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$_POST["tel_id"]]);

    echo json_encode(["status"=>"success"]);
    exit;
}


// DAR DE ALTA
if ($op === "alta") {

    $sql = "UPDATE equipos_telefonos SET estatus='ACTIVO' WHERE tel_id=?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$_POST["tel_id"]]);

    echo json_encode(["status"=>"success"]);
    exit;
}

/* ============================
   EN REPARACION
============================ */
if ($op === "reparacion") {

    $sql = "UPDATE equipos_telefonos SET estatus='REPARACION' WHERE tel_id=?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$_POST["tel_id"]]);

   echo json_encode(["status"=>"success"]);
    exit;
}