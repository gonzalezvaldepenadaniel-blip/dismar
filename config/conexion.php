<?php
class Conectar {

    private static $dbh = null;

    public static function conexion() {
        if (self::$dbh === null) {
            try {
                self::$dbh = new PDO(
                    "mysql:host=localhost;dbname=dismar;charset=utf8",
                    "root",
                    "",
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die("Error al conectar a la BD: " . $e->getMessage());
            }
        }
        return self::$dbh;
    }

    public static function ruta() {
        return "http://localhost/Dismar/";
    }
}
?>
