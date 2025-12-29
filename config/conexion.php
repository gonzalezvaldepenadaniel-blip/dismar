<?php
class Conectar{
    protected $dbh;

    public function conexion(){
        try {
            $this->dbh = new PDO(
                "mysql:host=localhost;dbname=dismar;charset=utf8",
                "root",
                ""
            );
            return $this->dbh;
        } catch (PDOException $e) {
            die("Error al conectar: " . $e->getMessage());
        }
    }

    public function set_names(){
        return $this->dbh->query("SET NAMES 'utf8'");
    }

    public function ruta(){
        return "http://localhost/dismar/";
    }
}
?>
