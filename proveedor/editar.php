<?php
// 1. Incluir la conexión
require_once '../config/conexion.php';

// 2. Procesar la actualización al enviar el formulario (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_proveedor = $conexion->real_escape_string($_POST['id_proveedor']);
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $telefono = $conexion->real_escape_string($_POST['telefono']);
    $ciudad_procedencia = $conexion->real_escape_string($_POST['ciudad_procedencia']);

    // Consulta SQL para actualizar los datos
    $query_update = "UPDATE Proveedor 
                     SET nombre = '$nombre', 
                         telefono = '$telefono', 
                         ciudad_procedencia = '$ciudad_procedencia' 
                     WHERE id_proveedor = '$id_proveedor'";

    if ($conexion->query($query_update)) {
        // Redirigir si sale bien
        header("Location: selecionar.php");
        exit();
    } else {
        $error = "Error al actualizar los datos del proveedor: " . $conexion->error;
    }
}

// 3. Cargar los datos actuales del proveedor (GET)
if (isset($_GET['id'])) {
    $id_get = $conexion->real_escape_string($_GET['id']);
    $query_select = "SELECT * FROM Proveedor WHERE id_proveedor = '$id_get'";
    $resultado = $conexion->query($query_select);

    if ($resultado && $resultado->num_rows > 0) {
        $proveedor = $resultado->fetch_assoc();
    } else {
        header("Location: selecionar.php");
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: selecionar.php");
    exit();
}

// 4. Incluir cabecera y sidebar
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Editar Proveedor</h2>
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

                <form action="editar.php" method="POST">
                    
                    <div class="mb-3">
                        <label for="id_proveedor" class="form-label fw-bold">NIT / ID del Proveedor</label>
                        <input type="text" class="form-control bg-light" id="id_proveedor" name="id_proveedor" value="<?= $proveedor['id_proveedor'] ?>" readonly>
                        <div class="form-text">El ID no se puede modificar para mantener la integridad de los registros.</div>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre de la Empresa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $proveedor['nombre'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label fw-bold">Teléfono / Contacto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="telefono" name="telefono" value="<?= $proveedor['telefono'] ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="ciudad_procedencia" class="form-label fw-bold">Ciudad de Procedencia <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ciudad_procedencia" name="ciudad_procedencia" value="<?= $proveedor['ciudad_procedencia'] ?>" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning text-dark fw-bold shadow-sm">
                            <i class="fas fa-sync-alt me-2"></i> Actualizar Datos del Proveedor
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?php
// 5. Incluir el pie de página
include '../includes/footer.php';
?>