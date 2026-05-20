<?php
// 1. Incluir la conexión
require_once '../config/conexion.php';

// 2. Procesar el formulario al hacer POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigo = $conexion->real_escape_string($_POST['codigo']);
    $id_categoria = $conexion->real_escape_string($_POST['id_categoria']);
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $peso = $conexion->real_escape_string($_POST['peso']);
    $cantidad = $conexion->real_escape_string($_POST['cantidad_almacenada']);
    $empaque = $conexion->real_escape_string($_POST['tipo_empaque']);
    $precio = $conexion->real_escape_string($_POST['precio_unitario']);

    // Insertar en la base de datos
    $query_insert = "INSERT INTO Producto (codigo, id_categoria, nombre, peso, cantidad_almacenada, tipo_empaque, precio_unitario) 
                     VALUES ('$codigo', '$id_categoria', '$nombre', '$peso', '$cantidad', '$empaque', '$precio')";

    if ($conexion->query($query_insert)) {
        header("Location: selecionar.php");
        exit();
    } else {
        // En caso de que se intente guardar un código que ya existe
        $error = "Error al guardar el producto. Verifica que el código no esté repetido. Detalle: " . $conexion->error;
    }
}

// 3. Consultar las categorías activas para llenar el menú desplegable (Select)
$query_categorias = "SELECT id_categoria, nombre FROM Categoria";
$resultado_categorias = $conexion->query($query_categorias);

// 4. Incluir cabecera y sidebar
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Nuevo Producto</h2>
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

                <form action="insertar.php" method="POST">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label fw-bold">Código del Producto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Ej. PAP005" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="id_categoria" class="form-label fw-bold">Categoría <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_categoria" name="id_categoria" required>
                                <option value="" disabled selected>Selecciona una categoría...</option>
                                <?php while ($cat = $resultado_categorias->fetch_assoc()): ?>
                                    <option value="<?= $cat['id_categoria'] ?>"><?= $cat['nombre'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre del Producto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej. Cuaderno cuadriculado 100 hojas" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="precio_unitario" class="form-label fw-bold">Precio Unitario ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" id="precio_unitario" name="precio_unitario" placeholder="Ej. 2500" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cantidad_almacenada" class="form-label fw-bold">Cantidad Inicial (Stock) <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control" id="cantidad_almacenada" name="cantidad_almacenada" value="0" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="peso" class="form-label fw-bold">Peso (Kg) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" id="peso" name="peso" placeholder="Ej. 0.5" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="tipo_empaque" class="form-label fw-bold">Tipo de Empaque <span class="text-danger">*</span></label>
                            <select class="form-select" id="tipo_empaque" name="tipo_empaque" required>
                                <option value="Carton">Cartón</option>
                                <option value="Plastico">Plástico</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn text-white" style="background-color: #6f42c1;">
                            <i class="fas fa-save me-2"></i> Registrar Producto
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?php
// 5. Incluir el footer
include '../includes/footer.php';
?>