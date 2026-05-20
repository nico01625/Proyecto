<?php
// 1. Incluir la conexión
require_once '../config/conexion.php';

// 2. Consulta SQL para traer todos los clientes
$query = "SELECT * FROM Cliente";
$resultado = $conexion->query($query);

// 3. Incluir cabecera y sidebar
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Gestión de Clientes</h2>
    <a href="insertar.php" class="btn text-white" style="background-color: #6f42c1;">
        <i class="fas fa-user-plus me-2"></i> Nuevo Cliente
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                
                <thead style="background-color: #0d1b2a; color: white;">
                    <tr>
                        <th scope="col">Cédula / ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Correo</th>
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?= $fila['cedula'] ?></strong></td>
                                <td><?= $fila['nombre'] ?></td>
                                <td><?= $fila['apellido'] ?></td>
                                <td><?= $fila['telefono'] ?></td>
                                <td><?= $fila['correo'] ?></td>
                                
                                <td class="text-center">
                                    <a href="editar.php?id=<?= $fila['cedula'] ?>" class="btn btn-sm btn-warning text-dark me-1" title="Editar">
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                    
                                    <a href="eliminar.php?id=<?= $fila['cedula'] ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?');">
                                        <i class="fas fa-user-times"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-users-slash fa-2x mb-2"></i><br>
                                No hay clientes registrados.
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