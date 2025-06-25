<?php
session_start();
require_once 'conexion.php';

if ($_POST) {
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $password = md5($_POST['password']);
    
    $query = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND password = '$password'";
    $result = mysqli_query($conexion, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = 'almacenero';
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cooperativa de Café - Login</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .login-container { max-width: 400px; margin: 100px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .btn { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
        .btn:hover { background-color: #218838; }
        .error { color: red; margin-bottom: 15px; }
        .logo { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h2>COOPERATIVA CAFETALERA<br>BAGUA GRANDE</h2>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Usuario:</label>
                <input type="text" name="usuario" required>
            </div>
            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Ingresar</button>
        </form>
    </div>
</body>
</html>