<?php
session_start();
require_once("../../config/conexion.php");

$conexion = Conectar::conexion();

$stmt = $conexion->prepare("
    SELECT noti_id, ticket_id, mensaje, leido, fecha 
    FROM tm_notificacion
    WHERE correo_usuario = :correo
    ORDER BY fecha DESC
");

$stmt->execute([
    ':correo' => $_SESSION['correo_usuario']
]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
    <div class="notificacion-item"
         data-ticket="<?= $row['ticket_id']; ?>"
         data-noti="<?= $row['noti_id']; ?>">
        <?= $row['mensaje']; ?>
    </div>
<?php } ?>
