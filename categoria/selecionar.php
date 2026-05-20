<?php
// 1. Incluir la conexión a la base de datos (usamos ../ porque subimos un nivel de carpeta)
require_once '../config/conexion.php';

// 2. Consulta SQL para traer todas las categorías
$query = "SELECT * FROM Categoria";
$resultado = $conexion->query($query);

// 3. Incluir la cabecera y el panel lateral
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Gestión de Categorías</h2>
    
    <a href="insertar.php" class="btn text-white" style="background-color: #6f42c1;">
        <i class="fas fa-plus-circle me-2"></i> Nueva Categoría
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                
                <thead style="background-color: #0d1b2a; color: white;">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre de la Categoría</th>
                        <th scope="col">Impuesto (%)</th>
                        <th scope="col">Stock Mínimo</th>
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?= $fila['id_categoria'] ?></strong></td>
                                <td><?= $fila['nombre'] ?></td>
                                
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= number_format($fila['porcentaje_impuesto'], 2) ?> %
                                    </span>
                                </td>
                                
                                <td><?= $fila['stock_minimo'] ?> unidades</td>
                                
                                <td class="text-center">
                                    <a href="insertar.php?id=<?= $fila['id_categoria'] ?>" class="btn btn-sm btn-warning text-dark me-1" title="insertar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <a href="eliminar.php?id=<?= $fila['id_categoria'] ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar esta categoría?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                No hay categorías registradas.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php
// 4. Incluir el pie de página y scripts
include '../includes/footer.php';
?>