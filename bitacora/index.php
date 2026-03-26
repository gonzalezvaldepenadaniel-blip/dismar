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
            <a href="computadoras.php">Computadoras</a>
            <a href="index.php">Teléfonos</a>
   
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
    
    <div class="topbar-left">
        <h5>Inventario de Teléfonos</h5>
    </div>

    

    <div class="topbar-right">

    <button class="btn btn-secondary btn-sm" id="btnMostrarMas">
        <i class="bi bi-eye"></i> Mostrar más
    </button>

    <button class="btn btn-primary btn-sm" id="btnNuevo">
        <i class="bi bi-plus-lg"></i> Nuevo
    </button>

</div>

</div>

    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-sm table-hover" id="tablaTelefonos">
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
                        <th>Responsiva</th>
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

<div class="modal-custom" id="modalAcciones">

<div class="modal-box modal-acciones">

<h5 class="mb-3">Acciones</h5>

<div class="acciones-grid">

<button class="accion-btn editar">
<i class="bi bi-pencil-square"></i>
<span>Editar</span>
</button>

<button class="accion-btn reparacion">
<i class="bi bi-tools"></i>
<span>Reparación</span>
</button>

<button class="accion-btn alta">
<i class="bi bi-arrow-up-circle"></i>
<span>Dar de alta</span>
</button>

<button class="accion-btn baja">
<i class="bi bi-arrow-down-circle"></i>
<span>Dar de baja</span>
</button>

</div>

<div class="text-end mt-3">
<button class="btn btn-secondary btn-sm" id="cerrarAcciones">
Cancelar
</button>
</div>

</div>
</div>

<div id="visorImagen" class="visor-img">

<span class="cerrar-img">&times;</span>

<img id="imgGrande">

</div>
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
