<?php
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cedula = $conexion->real_escape_string($_POST['cedula']);
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $apellido = $conexion->real_escape_string($_POST['apellido']);
    $telefono = $conexion->real_escape_string($_POST['telefono']);
    $correo = $conexion->real_escape_string($_POST['correo']);

    $query_insert = "INSERT INTO Cliente (cedula, nombre, apellido, telefono, correo) 
                     VALUES ('$cedula', '$nombre', '$apellido', '$telefono', '$correo')";

    if ($conexion->query($query_insert)) {
        header("Location: selecionar.php");
        exit();
    } else {
        $error = "Error al registrar el cliente. Es posible que la identificación ya exista: " . $conexion->error;
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Nuevo Cliente</h2>
    <a href="selecionar.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i> Volver a la lista
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form action="insertar.php" method="POST">
                    
                    <div class="mb-3">
                        <label for="cedula" class="form-label fw-bold">Documento de Identidad / NIT <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Ej. 1049654..." required>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej. Juan, Andres" required>
                    </div>
                     <div class="mb-3">
                        <label for="apellido" class="form-label fw-bold">Apellido <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ej. Pérez, Hernandez" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label fw-bold">Teléfono / Celular <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ej. 3112345678" required>
                    </div>

                   <div class="mb-4">
    <label for="correo" class="form-label fw-bold">
        Correo del cliente <span class="text-danger">*</span>
    </label>
        <input type="email"class="form-control"id="correo"name="correo"placeholder="Ej. usuario@ejemplo.com"pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"required>
        </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn text-white" style="background-color: #6f42c1;">
                            <i class="fas fa-save me-2"></i> Registrar Cliente
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>