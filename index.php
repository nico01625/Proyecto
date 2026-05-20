<?php
// 1. Incluir la conexión (está en la raíz, así que la ruta es directa)
require_once 'config/conexion.php';

// =======================================================
// QUERIES PARA LAS TARJETAS SUPERIORES
// =======================================================
// Tarjeta 1: Ingresos
$query_ingresos = "SELECT SUM(total_compra) AS total_ingresos FROM Compra";
$resultado_ingresos = $conexion->query($query_ingresos)->fetch_assoc();
$ingresos = $resultado_ingresos['total_ingresos'] ?? 0;

// Tarjeta 2: Gastos
$query_gastos = "SELECT SUM(valor_total_pagado) AS total_gastos FROM Producto_Proveedor";
$resultado_gastos = $conexion->query($query_gastos)->fetch_assoc();
$gastos = $resultado_gastos['total_gastos'] ?? 0;

// Tarjeta 3: Clientes
$query_clientes = "SELECT COUNT(*) AS total_clientes FROM Cliente";
$resultado_clientes = $conexion->query($query_clientes)->fetch_assoc();
$clientes_total = $resultado_clientes['total_clientes'] ?? 0;

// Tarjeta 4: Conteo de Alertas de Stock
$query_count_alertas = "SELECT COUNT(*) AS total_alertas 
                        FROM Producto p 
                        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
                        WHERE p.cantidad_almacenada <= c.stock_minimo";
$resultado_count_alertas = $conexion->query($query_count_alertas)->fetch_assoc();
$total_alertas = $resultado_count_alertas['total_alertas'] ?? 0;


// =======================================================
// QUERIES PARA LA NUEVA PARTE INFERIOR DEL PANEL
// =======================================================
// Tabla A: Últimas 5 ventas realizadas
$query_ultimas_ventas = "SELECT co.id_compra, co.fecha, co.total_compra, cl.nombre, cl.apellido 
                         FROM Compra co 
                         INNER JOIN Cliente cl ON co.cedula_cliente = cl.cedula 
                         ORDER BY co.fecha DESC LIMIT 5";
$ultimas_ventas = $conexion->query($query_ultimas_ventas);

// Tabla B: Lista de productos en alerta (Máximo 5 para el diseño)
$query_lista_alertas = "SELECT p.nombre, p.cantidad_almacenada, c.nombre AS categoria 
                        FROM Producto p 
                        INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
                        WHERE p.cantidad_almacenada <= c.stock_minimo LIMIT 5";
$lista_alertas = $conexion->query($query_lista_alertas);


// 2. Incluir cabecera y tu sidebar actualizado
include 'includes/header.php';
include 'includes/sidebar.php';

?>
<h2 class="text-dark fw-bold mb-4">Resumen General</h2>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 13px;">Ingresos Totales</h6>
                    <h3 class="fw-bold text-success mb-0">$<?= number_format($ingresos, 2, ',', '.') ?></h3>
                </div>
                <div class="bg-success text-white rounded p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 13px;">Gastos (Proveedores)</h6>
                    <h3 class="fw-bold text-danger mb-0">$<?= number_format($gastos, 2, ',', '.') ?></h3>
                </div>
                <div class="bg-danger text-white rounded p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                    <i class="fas fa-arrow-down"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 13px;">Clientes Registrados</h6>
                    <h3 class="fw-bold text-primary mb-0"><?= $clientes_total ?></h3>
                </div>
                <div class="bg-primary text-white rounded p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 13px;">Alertas de Stock</h6>
                    <h3 class="fw-bold text-warning mb-0"><?= $total_alertas ?></h3>
                </div>
                <div class="bg-warning text-dark rounded p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 fw-bold text-dark border-bottom">
                <i class="fas fa-history text-primary me-2"></i> Últimas Ventas Realizadas
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Factura</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($ultimas_ventas && $ultimas_ventas->num_rows > 0): ?>
                                <?php while ($venta = $ultimas_ventas->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong>#<?= str_pad($venta['id_compra'], 5, "0", STR_PAD_LEFT) ?></strong></td>
                                        <td><?= $venta['nombre'] . ' ' . $venta['apellido'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($venta['fecha'])) ?></td>
                                        <td class="text-end fw-bold text-success">$<?= number_format($venta['total_compra'], 2, ',', '.') ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">No hay ventas registradas recientemente.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 fw-bold text-dark border-bottom">
                <i class="fas fa-boxes text-warning me-2"></i> Productos Críticos (Pedir Ya)
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th class="text-center">Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($lista_alertas && $lista_alertas->num_rows > 0): ?>
                                <?php while ($prod_alerta = $lista_alertas->fetch_assoc()): ?>
                                    <tr>
                                        <td class="fw-bold"><?= $prod_alerta['nombre'] ?></td>
                                        <td><span class="badge bg-secondary"><?= $prod_alerta['categoria'] ?></span></td>
                                        <td class="text-center fw-bold text-danger bg-light-danger"><?= $prod_alerta['cantidad_almacenada'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-success fw-bold">
                                        <i class="fas fa-check-circle me-1"></i> ¡Excelente! Todo el stock está al día.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
// Incluir pie de página
include 'includes/footer.php';
?>
