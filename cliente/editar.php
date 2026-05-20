<?php
// 1. Incluir la conexión
require_once '../config/conexion.php';

$error = null;
$cliente = null;

// 2. Procesar la actualización (POST) con tus campos reales
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cedula = $conexion->real_escape_string($_POST['cedula']);
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $apellido = $conexion->real_escape_string($_POST['apellido']);
    $telefono = $conexion->real_escape_string($_POST['telefono']);
    $correo = $conexion->real_escape_string($_POST['correo']);

    $query_update = "UPDATE Cliente 
                     SET nombre = '$nombre', 
                         apellido = '$apellido', 
                         telefono = '$telefono', 
                         correo = '$correo' 
                     WHERE cedula = '$cedula'";

    if ($conexion->query($query_update)) {
        header("Location: selecionar.php");
        exit();
    } else {
        $error = "Error en la base de datos al actualizar: " . $conexion->error;
    }
}

// 3. DETECTOR INTELIGENTE: Acepta tanto 'cedula' como 'id' desde la URL
$cedula_url = "";
if (isset($_GET['cedula'])) {
    $cedula_url = $conexion->real_escape_string($_GET['cedula']);
} elseif (isset($_GET['id'])) {
    $cedula_url = $conexion->real_escape_string($_GET['id']);
}

// 4. Buscar los datos del cliente si encontramos una identificación
if (!empty($cedula_url)) {
    $query_select = "SELECT * FROM Cliente WHERE cedula = '$cedula_url'";
    $resultado = $conexion->query($query_select);

    if ($resultado && $resultado->num_rows > 0) {
        $cliente = $resultado->fetch_assoc();
    } else {
        $error = "No se encontró ningún cliente en la base de datos con la cédula o ID: '" . $cedula_url . "'. Revisa si el nombre de la columna en MySQL es correcto.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // Si no viene ningún ID ni es un envío de datos, volvemos
    header("Location: selecionar.php");
    exit();
}

// 5. Incluir componentes visuales
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark fw-bold">Editar Cliente</h2>
    <a href="selecionar.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i> Volver a la lista
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger fw-bold"><i class="fas fa-exclamation-circle me-2"></i><?= $error ?></div>
                <?php endif; ?>

                <?php if ($cliente): ?>
                    <form action="editar.php" method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Documento de Cédula</label>
                            <input type="text" class="form-control bg-light" name="cedula" value="<?= $cliente['cedula'] ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre" value="<?= $cliente['nombre'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="apellido" value="<?= $cliente['apellido'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Teléfono / Celular <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="telefono" value="<?= $cliente['telefono'] ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="correo" value="<?= $cliente['correo'] ?>" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning text-dark fw-bold">
                                <i class="fas fa-sync-alt me-2"></i> Actualizar Cliente
                            </button>
                        </div>

                    </form>
                <?php else: ?>
                    <div class="text-center py-3 text-muted">
                        <p>No se pueden cargar los campos del formulario porque no se pudo validar la identidad del cliente.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>