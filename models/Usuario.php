<?php


class Usuario extends Conectar {

    public function login($tipo){

        session_start();
        $conectar = parent::conexion();
        parent::set_names();

        if(isset($_POST["enviar"])){

            $correo = $_POST["usu_correo"];
            $pass   = $_POST["usu_pass"];

            if(empty($correo) || empty($pass)){
                header("Location:".Conectar::ruta()."index.php?m=2");
                exit();
            }

            $sql = "SELECT * FROM tm_usuario
                    WHERE usu_correo = ?
                    AND usu_pass = ?
                    AND rol = ?
                    AND estado = 1";

            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $correo);
            $stmt->bindValue(2, $pass);
            $stmt->bindValue(3, $tipo);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if($resultado){

                $_SESSION["usu_id"]         = $resultado["usu_id"];
                $_SESSION["usu_nombre"]     = $resultado["usu_nombre"];
                $_SESSION["usu_apellido"]   = $resultado["usu_apellido"];
                $_SESSION["correo_usuario"] = $resultado["usu_correo"];
                $_SESSION["rol"]            = $resultado["rol"];

                if ($tipo === "admin") {
                    header("Location:".Conectar::ruta()."Administrador/administrador.php");
                } else {
                    header("Location:".Conectar::ruta()."view/Home/");
                }
                exit();

            } else {
                $url = ($tipo === "admin") ? "admin-login.php?m=1" : "index.php?m=1";
                header("Location:".Conectar::ruta().$url);
                exit();
            }
        }
    }
}
