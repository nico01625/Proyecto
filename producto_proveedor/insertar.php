<?php
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_proveedor = $conexion->real_escape_string($_POST['id_proveedor']);
    $codigo_producto = $conexion->real_escape_string($_POST['codigo_producto']);
    $cantidad_suministrada = (int) $conexion->real_escape_string($_POST['cantidad_suministrada']);
    $valor_total_pagado = (float) $conexion->real_escape_string($_POST['valor_total_pagado']);

    // 1. Iniciar Transacción (Seguridad para que no haya descuadres)
    $conexion->begin_transaction();

    try {
        // A. Insertar el registro de la compra al proveedor
        $query_ingreso = "INSERT INTO Producto_Proveedor (codigo_producto, id_proveedor, cantidad_suministrada, valor_total_pagado) 
                          VALUES ('$codigo_producto', '$id_proveedor', $cantidad_suministrada, $valor_total_pagado)";
        $conexion->query($query_ingreso);

        // B. SUMAR la mercancía al inventario actual en la tabla Producto
        $query_stock = "UPDATE Producto 
                        SET cantidad_almacenada = cantidad_almacenada + $cantidad_suministrada 
                        WHERE codigo = '$codigo_producto'";
        $conexion->query($query_stock);

        // 2. Si las dos consultas salieron bien, confirmamos los cambios
        $conexion->commit();
        header("Location: selecionar.php");
        exit();

    } catch (Exception $e) {
        // Si ocurre un error, deshacemos todo para proteger el inventario
        $conexion->rollback();
        $error = "Error al registrar el ingreso de mercancía: " . $e->getMessage();
    }
}

// Consultas para llenar los menús desplegables del formulario
$proveedores_db = $conexion->query("SELECT id_proveedor, nombre FROM Proveedor");
$productos_db = $conexion->query("SELECT codigo, nombre, cantidad_almacenada FROM Producto");

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Registrar Abastecimiento</h2>
    <a href="selecionar.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i> Volver al historial
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger fw-bold"><i class="fas fa-exclamation-triangle me-2"></i><?= $error ?></div>
                <?php endif; ?>

                <form action="insertar.php" method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Proveedor que suministra <span class="text-danger">*</span></label>
                        <select class="form-select" name="id_proveedor" required>
                            <option value="">Seleccione el proveedor...</option>
                            <?php while ($prov = $proveedores_db->fetch_assoc()): ?>
                                <option value="<?= $prov['id_proveedor'] ?>"><?= $prov['nombre'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Producto recibido <span class="text-danger">*</span></label>
                        <select class="form-select" name="codigo_producto" required>
                            <option value="">Seleccione el producto...</option>
                            <?php while ($prod = $productos_db->fetch_assoc()): ?>
                                <option value="<?= $prod['codigo'] ?>">
                                    <?= $prod['nombre'] ?> (Cod: <?= $prod['codigo'] ?> | Stock actual: <?= $prod['cantidad_almacenada'] ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-success">Cantidad Recibida <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="cantidad_suministrada" min="1" required placeholder="Ej. 50">
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-danger">Total a Pagar ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="valor_total_pagado" min="0" required placeholder="Ej. 150000">
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success text-white fw-bold">
                            <i class="fas fa-check-circle me-2"></i> Confirmar Ingreso y Sumar Stock
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>