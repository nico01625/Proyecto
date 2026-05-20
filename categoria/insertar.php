<?php
// 1. Incluir la conexión
require_once '../config/conexion.php';

// 2. Lógica para guardar los datos cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir los datos del formulario de manera segura
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $impuesto = $conexion->real_escape_string($_POST['porcentaje_impuesto']);
    $stock = $conexion->real_escape_string($_POST['stock_minimo']);

    // Crear la consulta INSERT
    $query_insert = "INSERT INTO Categoria (nombre, porcentaje_impuesto, stock_minimo) 
                     VALUES ('$nombre', '$impuesto', '$stock')";

    // Ejecutar la consulta y redirigir
    if ($conexion->query($query_insert)) {
        // Si se guardó correctamente, volvemos a la tabla
        header("Location: selecionar.php");
        exit();
    } else {
        $error = "Error al guardar la categoría: " . $conexion->error;
    }
}

// 3. Incluir la cabecera y el panel lateral
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Nueva Categoría</h2>
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
                        <label for="nombre" class="form-label fw-bold">Nombre de la Categoría <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej. Lácteos, Ferretería..." required>
                    </div>

                    <div class="mb-3">
                        <label for="porcentaje_impuesto" class="form-label fw-bold">Porcentaje de Impuesto (%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control" id="porcentaje_impuesto" name="porcentaje_impuesto" placeholder="Ej. 5.00" required>
                        <div class="form-text">Ingresa el valor numérico (ej. 19 para 19%). Si no tiene impuesto, ingresa 0.</div>
                    </div>

                    <div class="mb-4">
                        <label for="stock_minimo" class="form-label fw-bold">Stock Mínimo <span class="text-danger">*</span></label>
                        <input type="number" min="1" class="form-control" id="stock_minimo" name="stock_minimo" value="5" required>
                        <div class="form-text">Cantidad mínima sugerida para que el sistema emita una alerta de escasez.</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn text-white" style="background-color: #6f42c1;">
                            <i class="fas fa-save me-2"></i> Registrar Categoría
                        </button>
                    </div>
                </form>
                </div>
        </div>
    </div>
</div>

<?php
// 4. Incluir el pie de página
include '../includes/footer.php';
?>