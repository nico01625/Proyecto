<?php
require_once '../config/conexion.php';

if (isset($_GET['id'])) {
    $codigo = $conexion->real_escape_string($_GET['id']);

    $query_delete = "DELETE FROM Producto WHERE codigo = '$codigo'";

    if ($conexion->query($query_delete)) {
        header("Location: selecionar.php");
        exit();
    } else {
        echo "<script>
                alert('No se puede eliminar este producto porque ya tiene compras o ventas asociadas. Error: " . $conexion->error . "');
                window.location.href = 'selecionar.php';
              </script>";
    }
} else {
    header("Location: selecionar.php");
    exit();
}
?>