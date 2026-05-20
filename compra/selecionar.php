<?php
// 1. Incluir la conexión
require_once '../config/conexion.php';

// 2. Consulta SQL para traer el historial de compras uniendo con la tabla Cliente
$query = "SELECT co.id_compra, co.fecha, co.total_compra, co.cedula_cliente, cl.nombre, cl.apellido 
          FROM Compra co 
          INNER JOIN Cliente cl ON co.cedula_cliente = cl.cedula 
          ORDER BY co.fecha DESC";

$resultado = $conexion->query($query);

// 3. Incluir cabecera y sidebar
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Historial de Ventas (Facturas)</h2>
    <a href="insertar.php" class="btn text-white" style="background-color: #6f42c1;">
        <i class="fas fa-cart-plus me-2"></i> Registrar Nueva Venta
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                
                <thead style="background-color: #0d1b2a; color: white;">
                    <tr>
                        <th scope="col">N° Factura</th>
                        <th scope="col">Fecha y Hora</th>
                        <th scope="col">Cédula Cliente</th>
                        <th scope="col">Nombre del Cliente</th>
                        <th scope="col">Total Facturado</th>
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><strong># <?= str_pad($fila['id_compra'], 5, "0", STR_PAD_LEFT) ?></strong></td>
                                <td><?= date('d/m/Y h:i A', strtotime($fila['fecha'])) ?></td>
                                <td><?= $fila['cedula_cliente'] ?></td>
                                <td><?= $fila['nombre'] . ' ' . $fila['apellido'] ?></td>
                                <td><span class="badge bg-success fs-6">$<?= number_format($fila['total_compra'], 2, ',', '.') ?></span></td>
                                
                                <td class="text-center">
                                    <a href="ver_detalle.php?id=<?= $fila['id_compra'] ?>" class="btn btn-sm btn-info text-white" title="Ver Detalles de Factura">
                                        <i class="fas fa-eye me-1"></i> Ver Detalle
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-receipt fa-2x mb-2"></i><br>
                                Aún no hay registros de ventas.
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