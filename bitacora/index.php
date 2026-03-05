<?php
require_once("../config/conexion.php");
$con = (new Conectar())->conexion();
?>
<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">

<title>Dismar</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="bitacora.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

</head>

<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar">
        <div class="logo">SIE</div>

        <nav>
            <a href="empleados.php">Empleados</a>
            <a href="telefonos.php">Teléfonos</a>
            <a href="asignaciones.php">Asignaciones</a>
            <a href="/Dismar/Administrador/administrador.php" class="logout">
                Volver
            </a>
        </nav>
    </aside>

    <!-- CONTENIDO -->
    <main class="main">

        <!-- HAMBURGUESA -->
        <div id="btnMenu" class="hamburger">☰</div>
        <div id="overlay" class="overlay"></div>

        <div class="topbar">
            Inventario de Teléfonos
        </div>

        <!-- aquí dejas tu card tal cual -->


<div class="card">

    <div class="card-header">
        <button class="btn btn-primary" id="btnNuevo">
            Nuevo Teléfono
        </button>
    </div>

    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle" id="tablaTelefonos">

                <thead>
                    <tr>
                        
                        <th>Marca</th>
                        <th>Imei</th>
                        <th>Modelo</th>
                        <th>Serie</th>
                        <th>Teléfono</th>
                        <th>Puesto</th>
                        <th>Área</th>
                        <th>Usuario</th>
                        <th>Cedis</th>
                        <th>Front</th>
                        <th>Back</th>
                        <th>Folio</th>
                        <th>Comentarios</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody></tbody>

            </table>
        </div>

    </div>

</div>
<?php include("modal.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="bitacora.js"></script>
</body>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const btnMenu = document.getElementById("btnMenu");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    btnMenu.onclick = () => {
        sidebar.classList.toggle("active");
        overlay.classList.toggle("active");
    };

    overlay.onclick = () => {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
    };

});
</script>

</html>
