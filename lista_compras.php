<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require_once 'conexion.php';

$query = "SELECT * FROM compras ORDER BY fecha_registro DESC";
$result = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Compras - Cooperativa de Café</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 1200px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .btn { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin: 5px; }
        .btn:hover { background-color: #0056b3; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #c82333; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>LISTA DE COMPRAS REGISTRADAS</h2>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Año</th>
                    <th>Socio</th>
                    <th>Rendimiento</th>
                    <th>Humedad</th>
                    <th>Estado</th>
                    <th>Precio (S/)</th>
                    <th>Cantidad (qq)</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['producto']; ?></td>
                    <td><?php echo $row['año']; ?></td>
                    <td><?php echo $row['nombre_socio']; ?></td>
                    <td><?php echo $row['rendimiento']; ?>%</td>
                    <td><?php echo $row['humedad']; ?>%</td>
                    <td><?php echo $row['estado_socio']; ?></td>
                    <td><?php echo number_format($row['precio'], 2); ?></td>
                    <td><?php echo $row['cantidad']; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_registro'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <div style="text-align: center;">
            <a href="registro_compras.php" class="btn">Nuevo Registro</a>
            <a href="dashboard.php" class="btn btn-danger">Volver al Menú</a>
        </div>
    </div>
</body>
</html>