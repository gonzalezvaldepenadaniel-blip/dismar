<?php
require_once("../config/conexion.php");
$con = (new Conectar())->conexion();

if (!isset($_POST["op"])) {
    exit;
}

/* =======================
   GUARDAR / EDITAR
======================= */
if ($_POST["op"] === "guardar") {

    /* ===== CREAR USUARIO ===== */
    if (empty($_POST["usu_id"])) {

        $sql = "INSERT INTO tm_usuario
        (usu_nombre, usu_apellido, usu_correo, usu_pass, rol, estado)
        VALUES (?,?,?,?,?,1)";

        $stmt = $con->prepare($sql);
        $stmt->execute([
            $_POST["usu_nombre"],
            $_POST["usu_apellido"],
            $_POST["usu_correo"],
            password_hash($_POST["usu_pass"], PASSWORD_DEFAULT),
            $_POST["rol"]
        ]);

    } 
    /* ===== EDITAR USUARIO ===== */
    else {

        // Si escribieron contraseña, se actualiza
        if (!empty($_POST["usu_pass"])) {

            $sql = "UPDATE tm_usuario SET
            usu_nombre=?,
            usu_apellido=?,
            usu_correo=?,
            usu_pass=?,
            rol=?
            WHERE usu_id=?";

            $stmt = $con->prepare($sql);
            $stmt->execute([
                $_POST["usu_nombre"],
                $_POST["usu_apellido"],
                $_POST["usu_correo"],
                password_hash($_POST["usu_pass"], PASSWORD_DEFAULT),
                $_POST["rol"],
                $_POST["usu_id"]
            ]);

        } 
        // Si NO escribieron contraseña, no se toca
        else {

            $sql = "UPDATE tm_usuario SET
            usu_nombre=?,
            usu_apellido=?,
            usu_correo=?,
            rol=?
            WHERE usu_id=?";

            $stmt = $con->prepare($sql);
            $stmt->execute([
                $_POST["usu_nombre"],
                $_POST["usu_apellido"],
                $_POST["usu_correo"],
                $_POST["rol"],
                $_POST["usu_id"]
            ]);
        }
    }
}

/* =======================
   ELIMINAR USUARIO
======================= */
if ($_POST["op"] === "eliminar") {

    $stmt = $con->prepare("DELETE FROM tm_usuario WHERE usu_id=?");
    $stmt->execute([$_POST["usu_id"]]);
}
