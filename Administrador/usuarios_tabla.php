<?php
require_once("../config/conexion.php");

$conexion = (new Conectar())->conexion();

$sql = "SELECT 
            usu_id,
            usu_nombre,
            usu_apellido,
            usu_correo,
            rol,
            cedis,
            estado
        FROM tm_usuario
        ORDER BY usu_id DESC";

$usuarios = $conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);

foreach ($usuarios as $u): ?>
<tr>

  <!-- Nombre -->
  <td><?= htmlspecialchars($u["usu_nombre"] . " " . $u["usu_apellido"]) ?></td>

  <!-- Correo -->
  <td><?= htmlspecialchars($u["usu_correo"]) ?></td>

  <!-- Rol -->
  <td><?= htmlspecialchars($u["rol"]) ?></td>

  <!-- Estado -->
  <td><?= $u["estado"] ? "Activo" : "Inactivo" ?></td>

  <!-- Cedis -->
  <td><?= htmlspecialchars($u["cedis"]) ?></td>

  <!-- Acciones -->
  <td class="text-center">
    <button class="btn btn-warning btn-sm editar"
        data-id="<?= $u["usu_id"] ?>"
        data-nombre="<?= htmlspecialchars($u["usu_nombre"]) ?>"
        data-apellido="<?= htmlspecialchars($u["usu_apellido"]) ?>"
        data-correo="<?= htmlspecialchars($u["usu_correo"]) ?>"
        data-rol="<?= htmlspecialchars($u["rol"]) ?>"
        data-cedis="<?= htmlspecialchars($u["cedis"]) ?>">
        ✏️
    </button>

    <button class="btn btn-danger btn-sm eliminar"
        data-id="<?= $u["usu_id"] ?>">
        🗑
    </button>
  </td>

</tr>
<?php endforeach; ?>
