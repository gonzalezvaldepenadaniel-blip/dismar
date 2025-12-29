<?php
session_start();

if (!isset($_SESSION["correo_usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../admin-login.php");
    exit();
}

require_once("usuarios_modal.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dismar - Admin</title>

    <link rel="stylesheet" href="../public/css/lib/bootstrap/bootstrap.min.css">

    <style>
        body {
            background: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .navbar-admin {
            background: #343a40;
            padding: 10px;
        }
        .navbar-admin a {
            color: #fff;
            margin-right: 15px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }
        .navbar-admin a:hover {
            color: #ddd;
        }
        .content {
            padding: 30px;
        }
    </style>
</head>
<body>

<!-- 🔹 NAVBAR -->
<div class="navbar-admin">
    <a href="administrador.php">Inicio</a>

    <!-- BOTÓN QUE ABRE EL MODAL -->
<a href="#" data-toggle="modal" data-target="#modalUsuarios">Usuarios</a>

    <a href="reportes.php">Reportes</a>
    <a href="../logout.php">Cerrar sesión</a>
</div>

<!-- CONTENIDO -->
<div class="content">
    <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION["usu_nombre"]); ?></h2>
</div>

<!-- 🔥 MODAL USUARIOS -->
<div class="modal fade" id="modalUsuarios" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Usuarios registrados</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= $u["usu_nombre"] . " " . $u["usu_apellido"] ?></td>
                            <td><?= $u["usu_correo"] ?></td>
                            <td><?= strtoupper($u["rol"]) ?></td>
                            <td><?= $u["estado"] == 1 ? "Activo" : "Inactivo" ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>

<script src="../public/js/lib/jquery/jquery.min.js"></script>
<script src="../public/js/lib/bootstrap/bootstrap.min.js"></script>
</body>
</html>
