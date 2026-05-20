<?php
// 1. Incluir la conexión a la base de datos
require_once '../config/conexion.php';

// 2. Verificar si se recibió un ID por la URL (método GET)
if (isset($_GET['id'])) {

    // Limpiar el ID por seguridad
    $id_categoria = $conexion->real_escape_string($_GET['id']);

    // 3. Crear la consulta para eliminar
    $query_delete = "DELETE FROM Categoria WHERE id_categoria = '$id_categoria'";

    // 4. Ejecutar la consulta
    if ($conexion->query($query_delete)) {
        // Si se elimina correctamente, redirigir de vuelta a la tabla
        header("Location: selecionar.php");
        exit();
    } else {
        // Si hay un error (por ejemplo, si la categoría ya tiene productos asignados)
        echo "<script>
                alert('No se puede eliminar esta categoría porque ya tiene productos registrados o hubo un error: " . $conexion->error . "');
                window.location.href = 'selecionar.php';
              </script>";
    }
} else {
    // Si alguien intenta entrar a eliminar.php sin un ID, lo devolvemos
    header("Location: selecionar.php");
    exit();
}
?>