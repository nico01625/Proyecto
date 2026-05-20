<?php
// 1. Incluir la conexión
require_once '../config/conexion.php';

// 2. Consulta SQL con el nombre exacto de tu tabla
$query = "SELECT * FROM Proveedor";
$resultado = $conexion->query($query);

// 3. Incluir cabecera y sidebar
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Gestión de Proveedores</h2>
    <a href="insertar.php" class="btn text-white" style="background-color: #6f42c1;">
        <i class="fas fa-truck me-2"></i> Nuevo Proveedor
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                
                <thead style="background-color: #0d1b2a; color: white;">
                    <tr>
                        <th scope="col">ID Proveedor</th>
                        <th scope="col">Nombre de la Empresa</th>
                        <th scope="col">Teléfono / Contacto</th>
                        <th scope="col">Ciudad de Procedencia</th> 
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?= $fila['id_proveedor'] ?></strong></td>
                                <td><?= $fila['nombre'] ?></td>
                                <td><?= $fila['telefono'] ?></td>
                                <td><?= $fila['ciudad_procedencia'] ?></td>
                                
                                <td class="text-center">
                                    <a href="insertar.php?id=<?= $fila['id_proveedor'] ?>" class="btn btn-sm btn-warning text-dark me-1" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <a href="eliminar.php?id=<?= $fila['id_proveedor'] ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este proveedor?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-building fa-2x mb-2"></i><br>
                                No hay proveedores registrados.
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