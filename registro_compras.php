<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require_once 'conexion.php';

// Obtener totales por producto
$totales_query = "SELECT producto, SUM(cantidad) as total FROM compras GROUP BY producto";
$totales_result = mysqli_query($conexion, $totales_query);
$totales = array();
while ($row = mysqli_fetch_assoc($totales_result)) {
    $totales[$row['producto']] = $row['total'];
}

if ($_POST) {
    $producto = $_POST['producto'];
    $año = date('Y');
    $stock = $_POST['stock'];
    $nombre_socio = $_POST['nombre_socio'];
    $cobase = $_POST['cobase'];
    $rendimiento = $_POST['rendimiento'];
    $humedad = $_POST['humedad'];
    $guia_ingreso = $_POST['guia_ingreso'];
    $estado_socio = $_POST['estado_socio'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    
    $query = "INSERT INTO compras (producto, año, stock, nombre_socio, cobase, rendimiento, humedad, guia_ingreso, estado_socio, precio, cantidad) 
              VALUES ('$producto', '$año', '$stock', '$nombre_socio', '$cobase', '$rendimiento', '$humedad', '$guia_ingreso', '$estado_socio', '$precio', '$cantidad')";
    
    if (mysqli_query($conexion, $query)) {
        $mensaje = "Registro guardado exitosamente";
        // Actualizar totales
        $totales_result = mysqli_query($conexion, $totales_query);
        $totales = array();
        while ($row = mysqli_fetch_assoc($totales_result)) {
            $totales[$row['producto']] = $row['total'];
        }
    } else {
        $error = "Error al guardar: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Compras - Cooperativa de Café</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 800px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        .btn:hover { background-color: #218838; }
        .btn-secondary { background-color: #6c757d; }
        .btn-secondary:hover { background-color: #5a6268; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #c82333; }
        .totales { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .success { color: green; margin-bottom: 15px; }
        .error { color: red; margin-bottom: 15px; }
        .button-group { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>COOPERATIVA CAFETALERA BAGUA GRANDE</h2>
            <h3>REGISTRO DE COMPRA DE CAFÉ</h3>
        </div>
        
        <?php if (isset($mensaje)): ?>
            <div class="success"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="totales">
            <h4>Totales por Producto</h4>
            <p><strong>FTO:</strong> <?php echo isset($totales['FTO']) ? $totales['FTO'] : 0; ?> quintales</p>
            <p><strong>FT:</strong> <?php echo isset($totales['FT']) ? $totales['FT'] : 0; ?> quintales</p>
            <p><strong>C:</strong> <?php echo isset($totales['C']) ? $totales['C'] : 0; ?> quintales</p>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label>Producto:</label>
                <select name="producto" required>
                    <option value="">Seleccione</option>
                    <option value="FTO">FTO</option>
                    <option value="FT">FT</option>
                    <option value="C">C</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Año:</label>
                <input type="text" name="año" value="<?php echo date('Y'); ?>" readonly>
            </div>
            
            <div class="form-group">
                <label>Stock:</label>
                <input type="number" name="stock" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label>Unidad:</label>
                <input type="text" value="Quintales" readonly>
            </div>
            
            <div class="form-group">
                <label>Nombre de Socio:</label>
                <input type="text" name="nombre_socio" placeholder="Ingresar nombre" required>
            </div>
            
            <div class="form-group">
                <label>COBASE:</label>
                <input type="text" name="cobase">
            </div>
            
            <div class="form-group">
                <label>Rendimiento (80-95):</label>
                <input type="number" name="rendimiento" min="80" max="95" step="0.01" placeholder="Ingresar rendimiento (80-95)" required>
            </div>
            
            <div class="form-group">
                <label>Humedad (12-15):</label>
                <input type="number" name="humedad" min="12" max="15" step="0.01" placeholder="Ingresar humedad (12-15)" required>
            </div>
            
            <div class="form-group">
                <label>Guía de Ingreso:</label>
                <input type="text" name="guia_ingreso" placeholder="N° Guía">
            </div>
            
            <div class="form-group">
                <label>Estado de Socio:</label>
                <select name="estado_socio" required>
                    <option value="">Seleccione</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Precio (S/):</label>
                <input type="number" name="precio" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label>Cantidad:</label>
                <input type="number" name="cantidad" step="0.01" required>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn">Registrar</button>
                <a href="lista_compras.php" class="btn btn-secondary">Lista de Registro</a>
                <a href="dashboard.php" class="btn btn-danger">Salir</a>
            </div>
        </form>
    </div>
</body>
</html>