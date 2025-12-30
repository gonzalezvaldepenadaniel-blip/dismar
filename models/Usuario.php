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

            // 🔹 BUSCAR USUARIO (SIN CONTRASEÑA EN SQL)
            $sql = "SELECT * FROM tm_usuario
                    WHERE usu_correo = ?
                    AND rol = ?
                    AND estado = 1
                    LIMIT 1";

            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $correo);
            $stmt->bindValue(2, $tipo);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            // ❌ Usuario no existe
            if (!$resultado) {
                $url = ($tipo === "admin") ? "admin-login.php?m=1" : "index.php?m=1";
                header("Location:".Conectar::ruta().$url);
                exit();
            }

            // ✅ VERIFICAR CONTRASEÑA ENCRIPTADA
            if (password_verify($pass, $resultado["usu_pass"])) {

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
                // ❌ Contraseña incorrecta
                $url = ($tipo === "admin") ? "admin-login.php?m=1" : "index.php?m=1";
                header("Location:".Conectar::ruta().$url);
                exit();
            }
        }
    }
}
