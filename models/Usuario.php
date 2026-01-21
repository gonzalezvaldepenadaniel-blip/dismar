<?php
require_once("../Dismar/config/conexion.php");

class Usuario {

    public function login(){

        session_start();
        $conectar = Conectar::conexion();

        if(isset($_POST["enviar"])){

            $correo = trim($_POST["usu_correo"]);
            $pass   = trim($_POST["usu_pass"]);

            if(empty($correo) || empty($pass)){
                header("Location:".Conectar::ruta()."admin-login.php?m=2");
                exit();
            }

            /* =========================
               NO FILTRAR POR ROL
            ========================= */
            $sql = "SELECT 
                        usu_id,
                        usu_nombre,
                        usu_apellido,
                        usu_correo,
                        usu_pass,
                        rol,
                        estado
                    FROM tm_usuario
                    WHERE usu_correo = ?
                    AND estado = 1
                    LIMIT 1";

            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $correo);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$resultado) {
                header("Location:".Conectar::ruta()."admin-login.php?m=1");
                exit();
            }

            if (!password_verify($pass, $resultado["usu_pass"])) {
                header("Location:".Conectar::ruta()."admin-login.php?m=1");
                exit();
            }

            /* =========================
               SESIONES (CLAVE)
            ========================= */
            $_SESSION["usu_id"]         = $resultado["usu_id"];
            $_SESSION["usu_nombre"]     = $resultado["usu_nombre"];
            $_SESSION["usu_apellido"]   = $resultado["usu_apellido"];
            $_SESSION["correo_usuario"] = $resultado["usu_correo"];
            $_SESSION["rol"]            = $resultado["rol"];

            /* =========================
               REDIRECCIÓN SEGÚN ROL
            ========================= */
            if (in_array($resultado["rol"], ["admin", "superadmin"])) {
                header("Location:".Conectar::ruta()."Administrador/administrador.php");
            } else {
                header("Location:".Conectar::ruta()."view/Home/");
            }
            exit();
        }
    }
}
