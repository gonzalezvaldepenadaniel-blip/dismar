<?php
session_start();
date_default_timezone_set('America/Mexico_City');

/* 🔐 VALIDAR LOGIN */
if (!isset($_SESSION['correo_usuario'])) {
    header("Location: ../../index.php");
    exit;
}

require_once("../../config/conexion.php");
$conexion = Conectar::conexion();

/* DATOS SESIÓN */
$correo_usuario = $_SESSION['correo_usuario'];

/* OBTENER NOMBRE DESDE BD */
$stmt = $conexion->prepare("
    SELECT usu_nombre, usu_apellido
    FROM tm_usuario
    WHERE usu_correo = :correo
    LIMIT 1
");
$stmt->execute([":correo" => $correo_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

/* NOMBRE FINAL */
$nombre_usuario = $usuario
    ? $usuario['usu_nombre'] . ' ' . $usuario['usu_apellido']
    : 'Usuario';
?>



<?php
// OBTENER TICKETS DEL USUARIO
$stmtTickets = $conexion->prepare("
    SELECT *
    FROM tm_ticket
    WHERE correo = :correo
    ORDER BY fecha_solicitud DESC
");
$stmtTickets->execute([":correo" => $correo_usuario]);
$tickets = $stmtTickets->fetchAll(PDO::FETCH_ASSOC);
?>









<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dismar | Servicios Corporativos</title>

<link rel="stylesheet" href="home.css">
</head>

<body>

<!-- ☰ BOTÓN HAMBURGUESA -->
<div id="btnMenu" class="hamburger">☰</div>

<!-- OVERLAY -->
<div id="overlay" class="overlay"></div>

<!-- ================= SIDEBAR ================= -->
<aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <img src="../../public/img/avatar-2-128.png" class="sidebar-logo">
        <div class="user">
            <strong><?= htmlspecialchars($nombre_usuario) ?></strong><br>
            <span><?= htmlspecialchars($correo_usuario) ?></span>
        </div>
    </div>

    <ul class="sidebar-menu">
        <li><a href="#" id="btnInicio">Inicio</a></li>
        <li><a href="#" id="btnMis">Mis Tickets</a></li>
        <li><a href="../../index.php">Cerrar sesión</a></li>
    </ul>
</aside>

<!-- ================= HOME ================= -->
<section id="seccionHome" class="home-servicios">
    <h1>SERVICIOS CORPORATIVOS</h1>

    <img src="../../public/img/dismar.png" class="logo-home" alt="Dismar">

    <br><br>

    <button id="btnCrearTicket" class="btn-home">
        ➕ Crear nuevo ticket
    </button>
</section>

<!-- ================= NUEVO TICKET ================= -->
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

<!-- ================= MIS TICKETS ================= -->


<section id="seccionMis" style="display:none;">
    <div class="tickets-card">
        <h3 class="section-title">Mis Tickets</h3>

        <?php if(!empty($tickets)): ?>
        <div class="table-responsive">
            <table class="tickets-table">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Cedis</th>
                        <th>Tipo Solicitud</th>
                        <th>Descripción</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Comentarios</th>
                        <th>Asignado a</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tickets as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['folio']) ?></td>
                        <td><?= htmlspecialchars($t['fecha_solicitud']) ?></td>
                        <td><?= htmlspecialchars($t['cedis']) ?></td>
                        <td><?= htmlspecialchars($t['tipo_solicitud']) ?></td>
                        <td><?= htmlspecialchars($t['descripcion']) ?></td>
                        <td><?= htmlspecialchars($t['prioridad']) ?></td>
                        <td>
                            <?php
                                switch($t['estado']) {
                                    case 1: echo "<span class='estado abierto'>Abierto</span>"; break;
                                    case 2: echo "<span class='estado proceso'>En Proceso</span>"; break;
                                    case 3: echo "<span class='estado cerrado'>Cerrado</span>"; break;
                                    default: echo "<span>-</span>";
                                }
                            ?>
                        </td>
                        <td><?= htmlspecialchars($t['comentario_admin'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($t['asignado'] ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p style="text-align:center;color:#666;">
                No tienes tickets registrados.
            </p>
        <?php endif; ?>
    </div>
</section>









<!-- ================= JS ================= -->
<script src="home.js"></script>

</body>
</html>
