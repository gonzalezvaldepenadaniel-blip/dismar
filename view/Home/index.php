<?php
session_start();
date_default_timezone_set('America/Mexico_City');

/* 🔐 VALIDAR LOGIN */
if (!isset($_SESSION['correo_usuario'])) {
    header("Location: ../../index.php");
    exit;
}

/* DATOS DEL USUARIO */
$nombre_usuario = $_SESSION['nombre_usuario'] ?? 'Usuario';
$correo_usuario = $_SESSION['correo_usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="home.css">

<meta charset="UTF-8">
<title>Dismar</title>


</head>

<body>

<!-- ================= HOME ================= -->
<section id="seccionHome" class="home-servicios">
    <h1>SERVICIOS CORPORATIVOS</h1>

    <!-- CAMBIA LA RUTA SI ES NECESARIO -->
    <img src="../../public/img/dismar.png" class="logo-home" alt="Dismar">

    <br>
    <button id="btnCrearTicket" class="btn-home">
        ➕ Crear nuevo ticket
    </button>
</section>

<!-- ================= FORMULARIO ================= -->
<section id="seccionNuevo" class="ticket-container" style="display:none;">

<form method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label>Fecha Solicitud</label>
        <input type="text" value="<?= date('Y-m-d H:i') ?>" readonly>
    </div>

    <div class="form-group">
        <label>Quién solicita</label>
        <input type="text" value="<?= htmlspecialchars($nombre_usuario) ?>" readonly>
    </div>

    <div class="form-group">
        <label>Correo</label>
        <input type="email" value="<?= htmlspecialchars($correo_usuario) ?>" readonly>
    </div>

    <div class="form-group">
        <label>Cedis</label>
        <select name="cedis" required>
            <option value="">Seleccione</option>
            <option>Iztapalapa</option>
            <option>Ecatepec</option>
            <option>Chicoloapan</option>
        </select>
    </div>

    <div class="form-group">
        <label>Tipo de Solicitud</label>
        <select name="tipo_solicitud" required>
            <option value="">Seleccione</option>
            <option>Mantenimiento</option>
            <option>Compras</option>
            <option>Sistemas</option>
        </select>
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" required></textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Prioridad</label>
            <select name="prioridad">
                <option>Alta</option>
                <option>Media</option>
                <option>Baja</option>
            </select>
        </div>

        <div class="form-group">
            <label>Matriz</label>
            <select name="matriz">
                <option value="1">Urgente e Importante</option>
                <option value="2">Importante</option>
                <option value="3">Urgente</option>
                <option value="4">No urgente</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Evidencia</label>
        <input type="file" name="evidencia">
    </div>

    <button type="submit" name="guardar" class="btn">
        Guardar Ticket
    </button>

</form>
</section>

<!-- ================= JS ================= -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const home  = document.getElementById("seccionHome");
    const nuevo = document.getElementById("seccionNuevo");
    const btn   = document.getElementById("btnCrearTicket");

    btn.addEventListener("click", function () {
        home.style.display  = "none";
        nuevo.style.display = "block";
    });

});
</script>

</body>
</html>
