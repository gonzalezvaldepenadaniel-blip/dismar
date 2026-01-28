<?php
require_once("../config/conexion.php");
$con = (new Conectar())->conexion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dismar</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="/Dismar/bitacora/bitacora.css">
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h4 class="logo">SIE</h4>
        <ul>
           <a href="empleados.php">Empleados</a>
<a href="telefonos.php">Teléfonos</a>
<a href="asignaciones.php">Asignaciones</a>

            <li><a href="/Dismar/Administrador/administrador.php" class="logout">Volver</a></li>
        </ul>
    </aside>

    <!-- CONTENIDO -->
    <div class="content">

        <div class="topbar">
            <span>Inventario de Teléfonos</span>
        </div>

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Teléfonos</h5>
                <button class="btn btn-primary" id="btnNuevo">➕ Nuevo Teléfono</button>
            </div>

            <div class="card-body table-responsive">

                <table class="table table-bordered table-hover table-sm align-middle" id="tablaTelefonos">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Serie</th>
                            <th>Teléfono</th>
                            <th>Puesto</th>
                            <th>Área</th>
                            <th>Usuario</th>
                            <th>Front</th>
                            <th>Back</th>
                            <th>Folio</th>
                            <th>Comentarios</th>
                            <th>Estatus</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM equipos_telefonos ORDER BY tel_id DESC";
                        $stmt = $con->prepare($sql);
                        $stmt->execute();

                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <tr>
                            <td><?= $row["tel_id"] ?></td>
                            <td><?= $row["marca"] ?></td>
                            <td><?= $row["modelo"] ?></td>
                            <td><?= $row["num_serie"] ?></td>
                            <td><?= $row["num_telefono"] ?></td>
                            <td><?= $row["puesto"] ?></td>
                            <td><?= $row["area"] ?></td>
                            <td><?= $row["nombre_usuario"] ?></td>
                            <td><?= $row["front"] ?></td>
                            <td><?= $row["back"] ?></td>
                            <td><?= $row["folio"] ?></td>
                            <td><?= $row["comentarios"] ?></td>
                            <td>
                                <span class="badge <?= $row["estatus"]=="ACTIVO" ? "bg-success" : "bg-danger" ?>">
                                    <?= $row["estatus"] ?>
                                </span>
                            </td>
                            <td><?= $row["fecha_alta"] ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>

<script>
$(document).ready(function() {
    $('#tablaTelefonos').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
});
</script>

</body>
</html>
