<?php
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cedula_cliente = $conexion->real_escape_string($_POST['cedula_cliente']);
    $total_compra = $conexion->real_escape_string($_POST['total_compra']);

    // Arrays que vienen del carrito con JavaScript
    $productos = $_POST['codigo_producto'] ?? [];
    $cantidades = $_POST['cantidad'] ?? [];
    $precios = $_POST['precio_unitario'] ?? [];
    $impuestos = $_POST['valor_impuesto'] ?? [];
    $subtotales = $_POST['subtotal'] ?? [];

    if (count($productos) > 0) {
        // 1. Iniciar Transacción (Seguridad para el inventario)
        $conexion->begin_transaction();

        try {
            // 2. Insertar la factura principal en la tabla Compra
            $query_compra = "INSERT INTO Compra (cedula_cliente, total_compra) VALUES ('$cedula_cliente', '$total_compra')";
            $conexion->query($query_compra);

            // Obtener el ID de la compra que se acaba de crear
            $id_compra = $conexion->insert_id;

            // 3. Recorrer cada producto del carrito para guardar el detalle y restar stock
            for ($i = 0; $i < count($productos); $i++) {
                $codigo = $conexion->real_escape_string($productos[$i]);
                $cantidad = $conexion->real_escape_string($cantidades[$i]);
                $precio = $conexion->real_escape_string($precios[$i]);
                $impuesto = $conexion->real_escape_string($impuestos[$i]);
                $subtotal = $conexion->real_escape_string($subtotales[$i]);

                // A. Insertar en Detalle_Compra
                $query_detalle = "INSERT INTO Detalle_Compra (id_compra, codigo_producto, cantidad, precio_unitario, valor_impuesto, subtotal) 
                                  VALUES ('$id_compra', '$codigo', '$cantidad', '$precio', '$impuesto', '$subtotal')";
                $conexion->query($query_detalle);

                // B. Restar la cantidad del inventario en la tabla Producto
                $query_stock = "UPDATE Producto SET cantidad_almacenada = cantidad_almacenada - $cantidad WHERE codigo = '$codigo'";
                $conexion->query($query_stock);
            }

            // 4. Si todo salió bien, confirmar los cambios en la base de datos
            $conexion->commit();
            header("Location: selecionar.php");
            exit();

        } catch (Exception $e) {
            // Si algo falla, revertimos todo para no dañar el stock
            $conexion->rollback();
            $error = "Error al procesar la venta: " . $e->getMessage();
        }
    } else {
        $error = "Debes agregar al menos un producto a la factura.";
    }
}

// Obtener listas para los desplegables del formulario
$clientes = $conexion->query("SELECT cedula, nombre, apellido FROM Cliente");
// Traemos los productos uniendo con Categoría para saber cuánto impuesto cobran
$productos_db = $conexion->query("SELECT p.codigo, p.nombre, p.precio_unitario, p.cantidad_almacenada, c.porcentaje_impuesto 
                                  FROM Producto p 
                                  INNER JOIN Categoria c ON p.id_categoria = c.id_categoria 
                                  WHERE p.cantidad_almacenada > 0");

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Nueva Factura de Venta</h2>
    <a href="selecionar.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i> Volver al historial
    </a>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger fw-bold"><i class="fas fa-exclamation-triangle me-2"></i><?= $error ?></div>
<?php endif; ?>

<form action="insertar.php" method="POST" id="formVenta">
    
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white fw-bold">Datos de la Venta</div>
                <div class="card-body">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Cliente</label>
                        <select class="form-select" name="cedula_cliente" required>
                            <option value="">Seleccione un cliente...</option>
                            <?php while ($c = $clientes->fetch_assoc()): ?>
                                <option value="<?= $c['cedula'] ?>"><?= $c['nombre'] . ' ' . $c['apellido'] ?> (<?= $c['cedula'] ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Producto a agregar</label>
                        <select class="form-select" id="selector_producto">
                            <option value="">Seleccione un producto...</option>
                            <?php while ($p = $productos_db->fetch_assoc()): ?>
                                <option value="<?= $p['codigo'] ?>" 
                                        data-nombre="<?= $p['nombre'] ?>"
                                        data-precio="<?= $p['precio_unitario'] ?>"
                                        data-stock="<?= $p['cantidad_almacenada'] ?>"
                                        data-impuesto="<?= $p['porcentaje_impuesto'] ?>">
                                    <?= $p['nombre'] ?> - $<?= number_format($p['precio_unitario'], 2) ?> (Stock: <?= $p['cantidad_almacenada'] ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad_agregar" min="1" value="1">
                    </div>

                    <button type="button" class="btn btn-primary w-100 fw-bold" onclick="agregarAlCarrito()">
                        <i class="fas fa-plus me-2"></i> Registrar a Factura
                    </button>

                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-bold text-center">Detalle de Factura</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0" id="tablaDetalles">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Cant.</th>
                                    <th>Precio U.</th>
                                    <th>Impuesto</th>
                                    <th>Subtotal</th>
                                    <th>X</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpoTabla">
                                </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white text-end p-4">
                    <h4 class="fw-bold">TOTAL A PAGAR: $<span id="textoTotal">0.00</span></h4>
                    <input type="hidden" name="total_compra" id="inputTotalCompra" value="0">
                    
                    <button type="submit" class="btn btn-success btn-lg mt-3 fw-bold" id="btnGuardarVenta" disabled>
                        <i class="fas fa-save me-2"></i> Procesar y Registrar Venta
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    let granTotal = 0;

    function agregarAlCarrito() {
        let selector = document.getElementById('selector_producto');
        let opcionSeleccionada = selector.options[selector.selectedIndex];
        let cantidad = parseInt(document.getElementById('cantidad_agregar').value);

        if (selector.value === "") {
            alert("Por favor, selecciona un producto primero.");
            return;
        }

        // Leer los datos ocultos del producto
        let codigo = selector.value;
        let nombre = opcionSeleccionada.getAttribute('data-nombre');
        let precio = parseFloat(opcionSeleccionada.getAttribute('data-precio'));
        let stock = parseInt(opcionSeleccionada.getAttribute('data-stock'));
        let porcentaje_imp = parseFloat(opcionSeleccionada.getAttribute('data-impuesto'));

        if (cantidad > stock) {
            alert("¡No tienes suficiente inventario! El stock máximo de este producto es: " + stock);
            return;
        }
        if (cantidad <= 0 || isNaN(cantidad)) {
            alert("Ingresa una cantidad válida.");
            return;
        }

        // Matemáticas de la factura
        let base_subtotal = precio * cantidad;
        let valor_impuesto = base_subtotal * (porcentaje_imp / 100);
        let subtotal_fila = base_subtotal + valor_impuesto;

        // Sumar al Gran Total
        granTotal += subtotal_fila;
        actualizarTotal();

        // Crear la fila visual en HTML
        let tbody = document.getElementById('cuerpoTabla');
        let fila = document.createElement('tr');
        
        // Atentos aquí: Usamos <input type='hidden' name='array[]'> para mandar múltiples datos a PHP
        fila.innerHTML = `
            <td>${codigo} <input type='hidden' name='codigo_producto[]' value='${codigo}'></td>
            <td>${nombre}</td>
            <td>${cantidad} <input type='hidden' name='cantidad[]' value='${cantidad}'></td>
            <td>$${precio.toFixed(2)} <input type='hidden' name='precio_unitario[]' value='${precio}'></td>
            <td>$${valor_impuesto.toFixed(2)} <input type='hidden' name='valor_impuesto[]' value='${valor_impuesto}'></td>
            <td class='fw-bold'>$${subtotal_fila.toFixed(2)} <input type='hidden' name='subtotal[]' value='${subtotal_fila}'></td>
            <td>
                <button type='button' class='btn btn-sm btn-danger' onclick='eliminarFila(this, ${subtotal_fila})'>
                    <i class='fas fa-times'></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(fila);
        
        // Resetear campos
        selector.value = "";
        document.getElementById('cantidad_agregar').value = 1;
        document.getElementById('btnGuardarVenta').disabled = false;
    }

    function eliminarFila(boton, subtotal_fila) {
        // Restar del total
        granTotal -= subtotal_fila;
        actualizarTotal();
        
        // Borrar la fila visual
        let fila = boton.parentNode.parentNode;
        fila.remove();
        
        // Si no hay productos, deshabilitar botón guardar
        if (document.getElementById('cuerpoTabla').children.length === 0) {
            document.getElementById('btnGuardarVenta').disabled = true;
        }
    }

    function actualizarTotal() {
        document.getElementById('textoTotal').innerText = granTotal.toFixed(2);
        document.getElementById('inputTotalCompra').value = granTotal.toFixed(2);
    }
</script>

<?php include '../includes/footer.php'; ?>