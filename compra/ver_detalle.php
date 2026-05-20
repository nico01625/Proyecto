<?php
// 1. Incluir la conexión
require_once '../config/conexion.php';

// 2. Verificar que recibimos el ID de la factura
if (isset($_GET['id'])) {
    $id_compra = $conexion->real_escape_string($_GET['id']);

    // A. Consultar los datos generales de la Factura y del Cliente
    $query_cabecera = "SELECT co.*, cl.nombre, cl.apellido, cl.telefono, cl.correo, cl.cedula 
                       FROM Compra co 
                       INNER JOIN Cliente cl ON co.cedula_cliente = cl.cedula 
                       WHERE co.id_compra = '$id_compra'";
    $resultado_cabecera = $conexion->query($query_cabecera);

    if ($resultado_cabecera && $resultado_cabecera->num_rows > 0) {
        $factura = $resultado_cabecera->fetch_assoc();
    } else {
        header("Location: selecionar.php");
        exit();
    }

    // B. Consultar la lista de productos que compró (El detalle)
    $query_detalle = "SELECT dc.*, p.nombre AS nombre_producto 
                      FROM Detalle_Compra dc 
                      INNER JOIN Producto p ON dc.codigo_producto = p.codigo 
                      WHERE dc.id_compra = '$id_compra'";
    $resultado_detalle = $conexion->query($query_detalle);

} else {
    header("Location: selecionar.php");
    exit();
}

// 3. Incluir diseño
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
    <h2 class="text-dark fw-bold">Detalle de la Factura</h2>
    <div>
        <button onclick="window.print()" class="btn btn-primary me-2">
            <i class="fas fa-print me-2"></i> Imprimir Recibo
        </button>
        <a href="selecionar.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al historial
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow border-0" id="areaImpresion">
            <div class="card-body p-5">
                
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h2 class="fw-bold text-uppercase" style="color: #0d1b2a;">TIENDA MULTICATEGORÍA</h2>
                        <p class="text-muted mb-0">Tu mejor opción en Papelería, Droguería y más.</p>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <h4 class="fw-bold text-danger mb-1">FACTURA # <?= str_pad($factura['id_compra'], 5, "0", STR_PAD_LEFT) ?></h4>
                        <div class="text-muted">Fecha: <?= date('d/m/Y', strtotime($factura['fecha'])) ?></div>
                        <div class="text-muted">Hora: <?= date('h:i A', strtotime($factura['fecha'])) ?></div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row mb-4">
                    <div class="col-sm-12">
                        <h6 class="text-muted fw-bold mb-2">FACTURADO A:</h6>
                        <div class="fw-bold fs-5"><?= $factura['nombre'] . ' ' . $factura['apellido'] ?></div>
                        <div><strong>C.C / NIT:</strong> <?= $factura['cedula'] ?></div>
                        <div><strong>Teléfono:</strong> <?= $factura['telefono'] ?></div>
                        <div><strong>Correo:</strong> <?= $factura['correo'] ?></div>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">Cant.</th>
                                <th>Descripción del Producto</th>
                                <th class="text-end">Precio U.</th>
                                <th class="text-end">Impuesto</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($item = $resultado_detalle->fetch_assoc()): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $item['cantidad'] ?></td>
                                    <td>
                                        <?= $item['nombre_producto'] ?> <br>
                                        <small class="text-muted">Cod: <?= $item['codigo_producto'] ?></small>
                                    </td>
                                    <td class="text-end">$<?= number_format($item['precio_unitario'], 2, ',', '.') ?></td>
                                    <td class="text-end">$<?= number_format($item['valor_impuesto'], 2, ',', '.') ?></td>
                                    <td class="text-end fw-bold">$<?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end">
                    <div class="col-sm-5 col-md-4">
                        <table class="table table-borderless">
                            <tr class="border-top border-bottom border-2 border-dark">
                                <td class="fw-bold fs-5 text-end">TOTAL A PAGAR:</td>
                                <td class="fw-bold fs-5 text-end text-success">$<?= number_format($factura['total_compra'], 2, ',', '.') ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="text-center text-muted mt-5 pt-3 border-top">
                    <small>¡Gracias por tu compra! Conserva este recibo para cualquier reclamo o devolución.</small>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body { background-color: white !important; }
        .sidebar, .navbar, .d-print-none { display: none !important; }
        .content { margin-left: 0 !important; width: 100% !important; padding: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
    }
</style>

<?php include '../includes/footer.php'; ?>