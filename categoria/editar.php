<?php
// 1. Incluir la conexión
require_once '../config/conexion.php';

// 2. Procesar la actualización si se envió el formulario (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $conexion->real_escape_string($_POST['id_categoria']);
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $impuesto = $conexion->real_escape_string($_POST['porcentaje_impuesto']);
    $stock = $conexion->real_escape_string($_POST['stock_minimo']);

    // Consulta UPDATE
    $query_update = "UPDATE Categoria 
                     SET nombre = '$nombre', 
                         porcentaje_impuesto = '$impuesto', 
                         stock_minimo = '$stock' 
                     WHERE id_categoria = '$id'";

    if ($conexion->query($query_update)) {
        header("Location: selecionar.php");
        exit();
    } else {
        $error = "Error al actualizar la categoría: " . $conexion->error;
    }
}

// 3. Obtener los datos actuales de la categoría para rellenar el formulario (GET)
if (isset($_GET['id'])) {
    $id_get = $conexion->real_escape_string($_GET['id']);
    $query_select = "SELECT * FROM Categoria WHERE id_categoria = '$id_get'";
    $resultado = $conexion->query($query_select);

    if ($resultado->num_rows > 0) {
        $categoria = $resultado->fetch_assoc();
    } else {
        // Si el ID no existe, redirigir
        header("Location: selecionar.php");
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: selecionar.php");
    exit();
}

// 4. Incluir la cabecera y el panel lateral
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Editar Categoría</h2>
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
                    
                    <input type="hidden" name="id_categoria" value="<?= $categoria['id_categoria'] ?>">
                    
                    <div class="mb-3">

                        <label for="nombre" class="form-label fw-bold">Nombre de la Categoría <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $categoria['nombre'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="porcentaje_impuesto" class="form-label fw-bold">Porcentaje de Impuesto (%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control" id="porcentaje_impuesto" name="porcentaje_impuesto" value="<?= $categoria['porcentaje_impuesto'] ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="stock_minimo" class="form-label fw-bold">Stock Mínimo <span class="text-danger">*</span></label>
                        <input type="number" min="1" class="form-control" id="stock_minimo" name="stock_minimo" value="<?= $categoria['stock_minimo'] ?>" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning text-dark fw-bold">
                            <i class="fas fa-sync-alt me-2"></i> Actualizar Categoría
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