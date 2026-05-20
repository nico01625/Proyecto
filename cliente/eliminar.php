<?php
require_once '../config/conexion.php';

if (isset($_GET['id'])) {
    $cedula = $conexion->real_escape_string($_GET['id']);

    $query_delete = "DELETE FROM Cliente WHERE cedula = '$cedula'";

    if ($conexion->query($query_delete)) {
        header("Location: selecionar.php");
        exit();
    } else {
        echo "<script>
                alert('No se puede eliminar este cliente porque cuenta con un historial de compras en el sistema. Error: " . $conexion->error . "');
                window.location.href = 'selecionar.php';
              </script>";
    }
} else {
    header("Location: selecionar.php");
    exit();
}
?>