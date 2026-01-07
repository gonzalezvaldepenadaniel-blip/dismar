<?php
require_once("../config/conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dismar</title>

    <!-- Bootstrap SOLO CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS propio -->
    <link rel="stylesheet" href="/Dismar/bitacora/bitacora.css">
</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <ul>
            <li><a href="#">Teléfonos</a></li>
            <li><a href="#">Computadoras</a></li>
        </ul>
    </aside>

    <!-- CONTENIDO -->
    <div class="content">

        <div class="topbar">
            <span>Inventarios</span>
        </div>

        <div class="content-inner">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista de Teléfonos</h5>
                    <button class="btn btn-primary" id="btnNuevo">
                        Nuevo Teléfono
                    </button>
                </div>

                <div class="card-body">
                    <table id="tablaTelefonos" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>No. Serie</th>
                                <th>Teléfono</th>
                                <th>Estatus</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include("modal.php"); ?>

<script src="/Dismar/bitacora/bitacora.js"></script>
</body>
</html>
