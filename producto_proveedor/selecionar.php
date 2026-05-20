<?php
// 1. Incluir la conexión
require_once '../config/conexion.php';

// 2. Consulta SQL: Unimos 3 tablas para ver el nombre del producto y del proveedor
$query = "SELECT pp.id_registro, pp.fecha_suministro, pp.cantidad_suministrada, pp.valor_total_pagado, 
                 p.nombre AS nombre_producto, pr.nombre AS nombre_proveedor 
          FROM Producto_Proveedor pp
          INNER JOIN Producto p ON pp.codigo_producto = p.codigo
          INNER JOIN Proveedor pr ON pp.id_proveedor = pr.id_proveedor
          ORDER BY pp.fecha_suministro DESC";

$resultado = $conexion->query($query);

// 3. Incluir cabecera y sidebar
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
   <h2 class="text-dark fw-bold">Historial de Abastecimiento (Compras a Proveedores)</h2>
    <a href="insertar.php" class="btn text-white" style="background-color: #198754;">
        <i class="fas fa-truck-loading me-2"></i> Registrar Nuevo Ingreso
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                
                <thead style="background-color: #0d1b2a; color: white;">
                    <tr>
                        <th scope="col">N° Registro</th>
                        <th scope="col">Fecha de Ingreso</th>
                        <th scope="col">Proveedor</th>
                        <th scope="col">Producto Suministrado</th>
                        <th scope="col" class="text-center">Cant. Ingresada</th>
                        <th scope="col" class="text-end">Total Pagado</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><strong># <?= str_pad($fila['id_registro'], 5, "0", STR_PAD_LEFT) ?></strong></td>
                                <td><?= date('d/m/Y h:i A', strtotime($fila['fecha_suministro'])) ?></td>
                                <td><i class="fas fa-building text-muted me-1"></i> <?= $fila['nombre_proveedor'] ?></td>
                                <td><i class="fas fa-box text-muted me-1"></i> <?= $fila['nombre_producto'] ?></td>
                                <td class="text-center fw-bold text-success">+<?= $fila['cantidad_suministrada'] ?></td>
                                <td class="text-end text-danger fw-bold">$<?= number_format($fila['valor_total_pagado'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-boxes fa-2x mb-2"></i><br>
                                Aún no hay registros de mercancía ingresada.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php
include '../includes/footer.php';
?>