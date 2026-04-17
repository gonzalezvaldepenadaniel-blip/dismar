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
        $responsiva = "";

if(isset($_FILES["responsiva"]) && $_FILES["responsiva"]["error"] == 0){

    $responsiva = time()."_".$_FILES["responsiva"]["name"];

    move_uploaded_file(
        $_FILES["responsiva"]["tmp_name"],
        "../public/responsivas/".$responsiva
    );

}





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
(marca, modelo, num_serie, num_telefono, imei, puesto, area, nombre_usuario, front, back, responsiva, comentarios, folio, estatus, usu_id)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
    $responsiva,
    $_POST["comentarios"],
    $_POST["folio"],
    'ACTIVO',
    $_POST["usu_id"]
]);

$tel_id = $con->lastInsertId();

$sqlHist = "INSERT INTO historial_telefonos
(tel_id, usu_id, fecha_asignacion, comentario)
VALUES (?, ?, NOW(),?)";

$stmtHist = $con->prepare($sqlHist);
$stmtHist->execute([
    $tel_id,
    $_POST["usu_id"],
      $_POST["comentarios"]
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

     {

        // cerrar historial anterior
        $sqlCerrar = "UPDATE historial_telefonos
        SET fecha_retiro = NOW()
        WHERE tel_id=? AND fecha_retiro IS NULL";

        $stmtCerrar = $con->prepare($sqlCerrar);
        $stmtCerrar->execute([$_POST["tel_id"]]);

        // nuevo historial
        $sqlNuevo = "INSERT INTO historial_telefonos
        (tel_id, usu_id, fecha_asignacion)
        VALUES (?, ?, NOW())";

        $stmtNuevo = $con->prepare($sqlNuevo);
        $stmtNuevo->execute([
            $_POST["tel_id"],
            $_POST["usu_id"]
        ]);

    }
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
   VER HISTORIAL
============================ */
if ($op === "historial") {

    $sql = "SELECT 
            h.hist_id,
            h.fecha_asignacion,
            h.fecha_retiro AS fecha_cambio,
            h.comentario,
            e.nombre,
            e.apellidop,
            e.apellidom
            FROM historial_telefonos h
            LEFT JOIN empleados e ON e.usu_id = h.usu_id
            WHERE h.tel_id = ?
            ORDER BY h.fecha_asignacion DESC";

    $stmt = $con->prepare($sql);
    $stmt->execute([$_GET["tel_id"]]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}
/* ============================
   GUARDAR COMENTARIO HISTORIAL
============================ */
if ($op === "comentario") {

    $sql = "INSERT INTO historial_telefonos
            (tel_id, usu_id, comentario, fecha_asignacion)
            VALUES (?, ?, ?, NOW())";

    $stmt = $con->prepare($sql);
    $stmt->execute([
        $_POST["tel_id"],
        $_POST["usu_id"],
        $_POST["comentario"]
    ]);

    echo "OK";
    exit;
}

/* ============================
   EMPLEADOS DEL HISTORIAL DEL TELÉFONO
============================ */
if ($op === "empleados_historial") {

    $sql = "SELECT DISTINCT 
                e.usu_id,
                e.nombre,
                e.apellidop,
                e.apellidom
            FROM historial_telefonos h
            INNER JOIN empleados e ON e.usu_id = h.usu_id
            WHERE h.tel_id = ?
            ORDER BY e.nombre, e.apellidop, e.apellidom";

    $stmt = $con->prepare($sql);
    $stmt->execute([$_GET["tel_id"]]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($op === "editar_comentario") {

    $sql = "UPDATE historial_telefonos
            SET comentario = ?
            WHERE hist_id = ?";

    $stmt = $con->prepare($sql);
    $stmt->execute([
        $_POST["comentario"],
        $_POST["hist_id"]
    ]);

    echo "OK";
    exit;
}