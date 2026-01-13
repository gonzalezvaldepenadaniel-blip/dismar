<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['correo_usuario'])) {
    header("Location: /Dismar/index.php");
    exit;
}
