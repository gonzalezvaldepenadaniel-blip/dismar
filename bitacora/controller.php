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

    $sql =
     
"SELECT   t.*,
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
   OBTENER TELEFONO
============================ */
if ($op === "obtener") {

$sql = "SELECT 
t.*,
e.cedis
FROM equipos_telefonos t
LEFT JOIN empleados e 
ON e.usu_id = t.usu_id
WHERE t.tel_id=?";

$stmt = $con->prepare($sql);
$stmt->execute([$_GET["tel_id"]]);

echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
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

        // 2️ Manejar imágenes
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

        // Insertar teléfono
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
   EDITAR TELEFONO
============================ */
if ($op === "editar") {

    $front = $_POST["front_actual"];
    $back  = $_POST["back_actual"];

    if(isset($_FILES["front"]) && $_FILES["front"]["error"] == 0){
        $front = time()."_front_".$_FILES["front"]["name"];
        move_uploaded_file($_FILES["front"]["tmp_name"], "../public/telefonos/".$front);
    }

    if(isset($_FILES["back"]) && $_FILES["back"]["error"] == 0){
        $back = time()."_back_".$_FILES["back"]["name"];
        move_uploaded_file($_FILES["back"]["tmp_name"], "../public/telefonos/".$back);
    }

    $sql = "UPDATE equipos_telefonos SET
        marca=?,
        modelo=?,
        num_serie=?,
        num_telefono=?,
        imei=?,
        comentarios=?,
        folio=?,
        front=?,
        back=?,
        usu_id=?
        WHERE tel_id=?";

    $stmt = $con->prepare($sql);

    $stmt->execute([
        $_POST["marca"],
        $_POST["modelo"],
        $_POST["num_serie"],
        $_POST["num_telefono"],
        $_POST["imei"],
        $_POST["comentarios"],
        $_POST["folio"],
        $front,
        $back,
        $_POST["usu_id"],
        $_POST["tel_id"]
    ]);

    echo "OK";
}
/* ============================
   alta y baja
============================ */
// DAR DE BAJA
if ($op === "baja") {

    $sql = "UPDATE equipos_telefonos 
            SET estatus='BAJA'
            WHERE tel_id=?";

    $stmt = $con->prepare($sql);
    $stmt->execute([$_POST["tel_id"]]);

    echo "OK";
    exit;
}

// DAR DE ALTA
if ($op === "alta") {

    $sql = "UPDATE equipos_telefonos SET estatus='ACTIVO' WHERE tel_id=?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$_POST["tel_id"]]);

   echo "OK";
    exit;
}

/* ============================
   EN REPARACION
============================ */
if ($op === "reparacion") {

    $sql = "UPDATE equipos_telefonos SET estatus='REPARACION' WHERE tel_id=?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$_POST["tel_id"]]);

  echo "OK";
    exit;
}

/* ============================
    COMPUTADORAS
============================ */

if($op=="listar"){
    $stmt = $con->prepare("SELECT * FROM computadoras ORDER BY cpu_id DESC");
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

/*======================

GUARDAS COMPUTADORAS
================*/

if($op=="guardar"){

    $sql = "INSERT INTO computadoras
    (puesto,marca,procesador,ram,so,disco_duro,tipo,monitor,teclado,mouse,camara,modelo_cpu,num_serie_cpu,num_serie_monitor,folio,comentarios)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $con->prepare($sql);
    $stmt->execute([
        $_POST["puesto"],
        $_POST["marca"],
        $_POST["procesador"],
        $_POST["ram"],
        $_POST["so"],
        $_POST["disco_duro"],
        $_POST["tipo"],
        $_POST["monitor"],
        $_POST["teclado"],
        $_POST["mouse"],
        $_POST["camara"],
        $_POST["modelo_cpu"],
        $_POST["num_serie_cpu"],
        $_POST["num_serie_monitor"],
        $_POST["folio"],
        $_POST["comentarios"]
    ]);

    echo "OK";
}

/*======================

EDITAR COMPUTADORAS
================*/

if($op=="editar"){

    $sql = "UPDATE computadoras SET
    puesto=?,marca=?,procesador=?,ram=?,so=?,disco_duro=?,tipo=?,monitor=?,teclado=?,mouse=?,camara=?,modelo_cpu=?,num_serie_cpu=?,num_serie_monitor=?,folio=?,comentarios=?
    WHERE cpu_id=?";

    $stmt = $con->prepare($sql);
    $stmt->execute([
        $_POST["puesto"],
        $_POST["marca"],
        $_POST["procesador"],
        $_POST["ram"],
        $_POST["so"],
        $_POST["disco_duro"],
        $_POST["tipo"],
        $_POST["monitor"],
        $_POST["teclado"],
        $_POST["mouse"],
        $_POST["camara"],
        $_POST["modelo_cpu"],
        $_POST["num_serie_cpu"],
        $_POST["num_serie_monitor"],
        $_POST["folio"],
        $_POST["comentarios"],
        $_POST["cpu_id"]
    ]);

    echo "OK";
}

/*======================

OBTENER COMPUTADORAS
================*/

if($op=="obtener"){
    $stmt = $con->prepare("SELECT * FROM computadoras WHERE cpu_id=?");
    $stmt->execute([$_GET["cpu_id"]]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}


/*======================

BAJA/ALTA COMPUTADORAS
================*/

if($op=="baja"){
    $stmt = $con->prepare("UPDATE computadoras SET estatus='BAJA' WHERE cpu_id=?");
    $stmt->execute([$_POST["cpu_id"]]);
    echo "OK";
}

if($op=="alta"){
    $stmt = $con->prepare("UPDATE computadoras SET estatus='ACTIVO' WHERE cpu_id=?");
    $stmt->execute([$_POST["cpu_id"]]);
    echo "OK";
}