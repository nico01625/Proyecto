<?php
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_proveedor = $conexion->real_escape_string($_POST['id_proveedor']);
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $telefono = $conexion->real_escape_string($_POST['telefono']);
    $ciudad_procedencia = $conexion->real_escape_string($_POST['ciudad_procedencia']);

    $query_insert = "INSERT INTO Proveedor (id_proveedor, nombre, telefono, ciudad_procedencia) 
                     VALUES ('$id_proveedor', '$nombre', '$telefono', '$ciudad_procedencia')";

    if ($conexion->query($query_insert)) {
        header("Location: selecionar.php");
        exit();
    } else {
        $error = "Error al registrar el proveedor. Es posible que el ID ya exista: " . $conexion->error;
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Nuevo Proveedor</h2>
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
                        <label for="id_proveedor" class="form-label fw-bold">NIT / ID del Proveedor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="id_proveedor" name="id_proveedor" placeholder="Ej. 900123456" required>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre de la Empresa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej. Distribuciones ABC" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label fw-bold">Teléfono / Contacto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ej. 3201234567" required>
                    </div>

                    <div class="mb-4">
                        <label for="ciudad_procedencia" class="form-label fw-bold">Ciudad de Procedencia <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ciudad_procedencia" name="ciudad_procedencia" placeholder="Ej. Bogotá" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn text-white" style="background-color: #6f42c1;">
                            <i class="fas fa-save me-2"></i> Registrar Proveedor
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