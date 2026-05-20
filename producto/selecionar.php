<?php
// 1. Incluir la conexión
require_once '../config/conexion.php';

// 2. Consulta SQL usando INNER JOIN para traer el nombre de la categoría y su stock mínimo
$query = "SELECT p.*, c.nombre AS nombre_categoria, c.stock_minimo 
          FROM Producto p 
          INNER JOIN Categoria c ON p.id_categoria = c.id_categoria";
$resultado = $conexion->query($query);

// 3. Incluir cabecera y sidebar
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Inventario de Productos</h2>
    <a href="insertar.php" class="btn text-white" style="background-color: #6f42c1;">
        <i class="fas fa-plus-circle me-2"></i> Nuevo Producto
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                
                <thead style="background-color: #0d1b2a; color: white;">
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Nombre del Producto</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Precio Unit.</th>
                        <th scope="col" class="text-center">Stock Actual</th>
                        <th scope="col">Empaque</th>
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?= $fila['codigo'] ?></strong></td>
                                <td><?= $fila['nombre'] ?></td>
                                <td><span class="badge bg-secondary"><?= $fila['nombre_categoria'] ?></span></td>
                                <td>$<?= number_format($fila['precio_unitario'], 2, ',', '.') ?></td>
                                
                                <td class="text-center">
                                    <?php if ($fila['cantidad_almacenada'] < $fila['stock_minimo']): ?>
                                        <span class="badge bg-danger fs-6" title="¡Por debajo del mínimo!">
                                            <?= $fila['cantidad_almacenada'] ?> <i class="fas fa-exclamation-triangle ms-1"></i>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success fs-6">
                                            <?= $fila['cantidad_almacenada'] ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                
                                <td><?= $fila['tipo_empaque'] ?></td>
                                
                                <td class="text-center">
                                    <a href="Registrar.php?id=<?= $fila['codigo'] ?>" class="btn btn-sm btn-warning text-dark me-1" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <a href="eliminar.php?id=<?= $fila['codigo'] ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-box-open fa-2x mb-2"></i><br>
                                No hay productos registrados en el inventario.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php
// 4. Incluir el footer
include '../includes/footer.php';
?>