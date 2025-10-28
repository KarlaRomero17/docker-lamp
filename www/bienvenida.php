<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
$nombre = $_SESSION['usuario_nombre'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Bienvenido</title>
    <style>
        body {
            font-family: Arial;
            background: #F0F0F0FF;
            padding: 40px
        }

        .box {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08)
        }

        a {
            display: inline-block;
            margin-top: 12px;
            padding: 8px 12px;
            background: #dc3545;
            color: #fff;
            text-decoration: none;
            border-radius: 6px
        }
    </style>
</head>

<body>
    <div class="box">
        <h1>Bienvenido, <?php echo htmlspecialchars($nombre); ?>!</h1>
        <p>Has iniciado sesión correctamente en el entorno <strong>LAMP-DOCKER</strong>.</p>
        <a href="logout.php">Cerrar sesión</a>
    </div>
</body>

</html>