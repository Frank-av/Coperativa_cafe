<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require_once 'conexion.php';

// Obtener compras disponibles para salida
$compras_query = "SELECT id, producto, año, nombre_socio, cantidad, stock FROM compras ORDER BY fecha_registro DESC";
$compras_result = mysqli_query($conexion, $compras_query);

if ($_POST) {
    $compra_id = $_POST['compra_id'];
    $cantidad_salida = $_POST['cantidad_salida'];
    $destino = $_POST['destino'];
    $observaciones = $_POST['observaciones'];

    // Validar cantidad disponible
    $compra = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT cantidad FROM compras WHERE id = $compra_id"));
    if ($compra && $cantidad_salida > 0 && $cantidad_salida <= $compra['cantidad']) {
        // Registrar la salida
        $insert = "INSERT INTO salidas (compra_id, cantidad_salida, destino, observaciones) 
                   VALUES ($compra_id, $cantidad_salida, '$destino', '$observaciones')";
        if (mysqli_query($conexion, $insert)) {
            // Actualizar cantidad disponible en compras
            $update = "UPDATE compras SET cantidad = cantidad - $cantidad_salida WHERE id = $compra_id";
            mysqli_query($conexion, $update);
            $mensaje = "Salida registrada correctamente.";
        } else {
            $error = "Error al registrar la salida: " . mysqli_error($conexion);
        }
    } else {
        $error = "Cantidad de salida inválida o insuficiente.";
    }
}

// Obtener lista de salidas
$salidas_query = "SELECT s.id, s.fecha_salida, s.cantidad_salida, s.destino, s.observaciones, 
                         c.producto, c.año, c.nombre_socio 
                  FROM salidas s 
                  JOIN compras c ON s.compra_id = c.id
                  ORDER BY s.fecha_salida DESC";
$salidas_result = mysqli_query($conexion, $salidas_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Salidas - Cooperativa de Café</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 900px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        .btn:hover { background-color: #0056b3; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #c82333; }
        .success { color: green; margin-bottom: 15px; }
        .error { color: red; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>REGISTRO DE SALIDAS DE CAFÉ</h2>
            <h4>Hacia Planta de Procesamiento</h4>
        </div>
        
        <?php if (isset($mensaje)): ?>
            <div class="success"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Compra de Origen:</label>
                <select name="compra_id" required>
                    <option value="">Seleccione una compra</option>
                    <?php while ($row = mysqli_fetch_assoc($compras_result)): ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo $row['producto'] . " - " . $row['año'] . " - Socio: " . $row['nombre_socio'] . " - Disponible: " . $row['cantidad'] . " qq"; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Cantidad a enviar (quintales):</label>
                <input type="number" name="cantidad_salida" step="0.01" min="0.01" required>
            </div>
            <div class="form-group">
                <label>Destino:</label>
                <input type="text" name="destino" value="Planta de Procesamiento" required>
            </div>
            <div class="form-group">
                <label>Observaciones:</label>
                <textarea name="observaciones" rows="2"></textarea>
            </div>
            <button type="submit" class="btn">Registrar Salida</button>
            <a href="dashboard.php" class="btn btn-danger">Volver al Menú</a>
        </form>
        
        <h3>Salidas Registradas</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Año</th>
                    <th>Socio</th>
                    <th>Cantidad (qq)</th>
                    <th>Destino</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($salidas_result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_salida'])); ?></td>
                    <td><?php echo $row['producto']; ?></td>
                    <td><?php echo $row['año']; ?></td>
                    <td><?php echo $row['nombre_socio']; ?></td>
                    <td><?php echo $row['cantidad_salida']; ?></td>
                    <td><?php echo $row['destino']; ?></td>
                    <td><?php echo $row['observaciones']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>