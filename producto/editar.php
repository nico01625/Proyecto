<?php
require_once '../config/conexion.php';

// Procesar la actualización (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigo = $conexion->real_escape_string($_POST['codigo']);
    $id_categoria = $conexion->real_escape_string($_POST['id_categoria']);
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $peso = $conexion->real_escape_string($_POST['peso']);
    $cantidad = $conexion->real_escape_string($_POST['cantidad_almacenada']);
    $empaque = $conexion->real_escape_string($_POST['tipo_empaque']);
    $precio = $conexion->real_escape_string($_POST['precio_unitario']);

    $query_update = "UPDATE Producto 
                     SET id_categoria = '$id_categoria', 
                         nombre = '$nombre', 
                         peso = '$peso', 
                         cantidad_almacenada = '$cantidad', 
                         tipo_empaque = '$empaque', 
                         precio_unitario = '$precio' 
                     WHERE codigo = '$codigo'";

    if ($conexion->query($query_update)) {
        header("Location: selecionar.php");
        exit();
    } else {
        $error = "Error al actualizar el producto: " . $conexion->error;
    }
}

// Obtener datos del producto para el formulario (GET)
if (isset($_GET['id'])) {
    $id_get = $conexion->real_escape_string($_GET['id']);
    $query_select = "SELECT * FROM Producto WHERE codigo = '$id_get'";
    $resultado = $conexion->query($query_select);

    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
    } else {
        header("Location: selecionar.php");
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: selecionar.php");
    exit();
}

// Obtener categorías para el select
$query_categorias = "SELECT id_categoria, nombre FROM Categoria";
$resultado_categorias = $conexion->query($query_categorias);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Editar Producto</h2>
    <a href="selecionar.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i> Volver al inventario
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form action="editar.php" method="POST">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label fw-bold">Código del Producto</label>
                            <input type="text" class="form-control bg-light" id="codigo" name="codigo" value="<?= $producto['codigo'] ?>" readonly>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="id_categoria" class="form-label fw-bold">Categoría <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_categoria" name="id_categoria" required>
                                <?php while ($cat = $resultado_categorias->fetch_assoc()): ?>
                                    <option value="<?= $cat['id_categoria'] ?>" <?= ($cat['id_categoria'] == $producto['id_categoria']) ? 'selected' : '' ?>>
                                        <?= $cat['nombre'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre del Producto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $producto['nombre'] ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="precio_unitario" class="form-label fw-bold">Precio Unitario ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" id="precio_unitario" name="precio_unitario" value="<?= $producto['precio_unitario'] ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cantidad_almacenada" class="form-label fw-bold">Cantidad Actual (Stock) <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control" id="cantidad_almacenada" name="cantidad_almacenada" value="<?= $producto['cantidad_almacenada'] ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="peso" class="form-label fw-bold">Peso (Kg) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" id="peso" name="peso" value="<?= $producto['peso'] ?>" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="tipo_empaque" class="form-label fw-bold">Tipo de Empaque <span class="text-danger">*</span></label>
                            <select class="form-select" id="tipo_empaque" name="tipo_empaque" required>
                                <option value="Carton" <?= ($producto['tipo_empaque'] == 'Carton') ? 'selected' : '' ?>>Cartón</option>
                                <option value="Plastico" <?= ($producto['tipo_empaque'] == 'Plastico') ? 'selected' : '' ?>>Plástico</option>
                                <option value="Otro" <?= ($producto['tipo_empaque'] == 'Otro') ? 'selected' : '' ?>>Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning text-dark fw-bold">
                            <i class="fas fa-sync-alt me-2"></i> Actualizar Producto
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