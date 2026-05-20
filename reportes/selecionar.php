<?php
require_once '../config/conexion.php';

// =======================================================
// 1. CÁLCULO DE INGRESOS Y GASTOS
// =======================================================
$query_ingresos = "SELECT SUM(total_compra) AS total_ingresos FROM Compra";
$resultado_ingresos = $conexion->query($query_ingresos)->fetch_assoc();
$ingresos = $resultado_ingresos['total_ingresos'] ?? 0;

$query_gastos = "SELECT SUM(valor_total_pagado) AS total_gastos FROM Producto_Proveedor";
$resultado_gastos = $conexion->query($query_gastos)->fetch_assoc();
$gastos = $resultado_gastos['total_gastos'] ?? 0;

// =======================================================
// 2. ALERTA DE STOCK MÍNIMO (<= 5 o el definido en categoría)
// =======================================================
$query_alertas = "SELECT p.codigo, p.nombre, p.cantidad_almacenada, c.nombre as categoria 
                  FROM Producto p 
                  INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
                  WHERE p.cantidad_almacenada <= c.stock_minimo";
$alertas_stock = $conexion->query($query_alertas);

// =======================================================
// 3. ESTADÍSTICAS DE CLIENTES
// =======================================================

// A. Mejores clientes (Ordenados por mayor valor total comprado)
$query_mejores = "SELECT cl.nombre, cl.apellido, COUNT(co.id_compra) as total_compras, SUM(co.total_compra) as valor_total 
                  FROM Compra co 
                  INNER JOIN Cliente cl ON co.cedula_cliente = cl.cedula 
                  GROUP BY cl.cedula 
                  ORDER BY valor_total DESC LIMIT 5";
$mejores_clientes = $conexion->query($query_mejores);

// B. Cliente más frecuente (El que tiene más cantidad de facturas)
$query_frecuente = "SELECT cl.nombre, cl.apellido, COUNT(co.id_compra) as total_compras 
                    FROM Compra co 
                    INNER JOIN Cliente cl ON co.cedula_cliente = cl.cedula 
                    GROUP BY cl.cedula 
                    ORDER BY total_compras DESC LIMIT 1";
$cliente_frecuente = $conexion->query($query_frecuente)->fetch_assoc();

// C. Clientes de UNA SOLA compra
$query_una_compra = "SELECT cl.nombre, cl.apellido, COUNT(co.id_compra) as total_compras 
                     FROM Compra co 
                     INNER JOIN Cliente cl ON co.cedula_cliente = cl.cedula 
                     GROUP BY cl.cedula 
                     HAVING total_compras = 1";
$clientes_una_compra = $conexion->query($query_una_compra);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Reportes y Estadísticas</h2>
    <button onclick="window.print()" class="btn btn-outline-primary d-print-none">
        <i class="fas fa-print me-2"></i> Imprimir Reporte
    </button>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-success text-white shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title text-uppercase fw-bold"><i class="fas fa-arrow-up me-2"></i> Total Ingresos (Ventas)</h5>
                <h2 class="display-5 fw-bold">$<?= number_format($ingresos, 2, ',', '.') ?></h2>
                <p class="mb-0">Dinero que ha entrado por clientes.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-danger text-white shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title text-uppercase fw-bold"><i class="fas fa-arrow-down me-2"></i> Total Gastos (Proveedores)</h5>
                <h2 class="display-5 fw-bold">$<?= number_format($gastos, 2, ',', '.') ?></h2>
                <p class="mb-0">Dinero pagado en abastecimiento.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm border-0 border-top border-warning border-4 h-100">
            <div class="card-header bg-white fw-bold text-warning fs-5">
                <i class="fas fa-exclamation-triangle me-2"></i> Alertas de Stock (Pedir producto)
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th class="text-center">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($alertas_stock && $alertas_stock->num_rows > 0): ?>
                            <?php while ($alerta = $alertas_stock->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $alerta['nombre'] ?></td>
                                    <td><span class="badge bg-secondary"><?= $alerta['categoria'] ?></span></td>
                                    <td class="text-center fw-bold text-danger"><?= $alerta['cantidad_almacenada'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center py-3 text-success">¡Todo el inventario está por encima del mínimo!</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-7 mb-4">
        
        <div class="card shadow-sm border-0 border-top border-info border-4 mb-4">
            <div class="card-body bg-light">
                <div class="row text-center">
                    <div class="col-sm-6 border-end border-secondary">
                        <h6 class="text-muted text-uppercase fw-bold mb-1">Cliente Más Frecuente</h6>
                        <?php if ($cliente_frecuente): ?>
                            <h4 class="text-info fw-bold mb-0"><?= $cliente_frecuente['nombre'] . ' ' . $cliente_frecuente['apellido'] ?></h4>
                            <small class="text-muted">(<?= $cliente_frecuente['total_compras'] ?> compras realizadas)</small>
                        <?php else: ?>
                            <span class="text-muted">Aún no hay datos</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="text-muted text-uppercase fw-bold mb-1">Clientes de una sola vez</h6>
                        <ul class="list-unstyled mb-0">
                            <?php if ($clientes_una_compra && $clientes_una_compra->num_rows > 0): ?>
                                <?php while ($una_vez = $clientes_una_compra->fetch_assoc()): ?>
                                    <li class="fw-bold"><?= $una_vez['nombre'] . ' ' . $una_vez['apellido'] ?></li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li><span class="text-muted">Aún no hay datos</span></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 border-top border-primary border-4">
            <div class="card-header bg-white fw-bold text-primary fs-5">
                <i class="fas fa-crown me-2"></i> Top Clientes (Por Valor Comprado)
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Cliente</th>
                            <th class="text-center">N° de Compras</th>
                            <th class="text-end">Valor Total Comprado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($mejores_clientes && $mejores_clientes->num_rows > 0): ?>
                            <?php while ($mejor = $mejores_clientes->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold"><?= $mejor['nombre'] . ' ' . $mejor['apellido'] ?></td>
                                    <td class="text-center"><span class="badge bg-primary rounded-pill"><?= $mejor['total_compras'] ?></span></td>
                                    <td class="text-end fw-bold text-success">$<?= number_format($mejor['valor_total'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center py-3 text-muted">Aún no hay datos de clientes</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<style>
    @media print {
        .sidebar, .navbar, .d-print-none { display: none !important; }
        .content { margin-left: 0 !important; width: 100% !important; padding: 0 !important; }
    }
</style>

<?php include '../includes/footer.php'; ?>