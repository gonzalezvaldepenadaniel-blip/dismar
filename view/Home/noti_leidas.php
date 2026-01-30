<?php
session_start();
require_once("../../config/conexion.php");

$conexion = Conectar::conexion();

$stmt = $conexion->prepare("
    SELECT noti_id, ticket_id, mensaje, leido, fecha 
    FROM tm_notificacion
    WHERE correo_usuario = :correo AND leido = 0
    ORDER BY fecha DESC
");

$stmt->execute([
    ':correo' => $_SESSION['correo_usuario']
]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
    <div class="noti-ticket"
         data-ticket-id="<?= $row['ticket_id']; ?>"
         data-noti-id="<?= $row['noti_id']; ?>">
        <?= $row['mensaje']; ?>
    </div>
<?php } ?>
