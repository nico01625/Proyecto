<?php
require_once '../config/conexion.php';

if (isset($_GET['id'])) {
    $id_proveedor = $conexion->real_escape_string($_GET['id']);

    $query_delete = "DELETE FROM Proveedor WHERE id_proveedor = '$id_proveedor'";

    if ($conexion->query($query_delete)) {
        header("Location: selecionar.php");
        exit();
    } else {
        echo "<script>
                alert('No se puede eliminar este proveedor porque ya cuenta con un historial de compras asociado. Error: " . $conexion->error . "');
                window.location.href = 'selecionar.php';
              </script>";
    }
} else {
    header("Location: selecionar.php");
    exit();
}
?>