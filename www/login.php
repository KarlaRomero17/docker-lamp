<?php
session_start();
if (isset($_SESSION['usuario_nombre'])) {
    header('Location: bienvenida.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Login - Docker LAMP</title>
    <style>
        body {
            font-family: Arial;
            background: #F0F0F0FF;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 320px
        }

        input {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            box-sizing: border-box
        }

        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 6px
        }

        .error {
            color: red;
            margin-top: 8px
        }
    </style>
</head>

<body>
    <form method="post" action="autenticar.php" autocomplete="off">
        <h2>LAMP DOCKER</h2>
        <h3>Iniciar sesión</h3>
        <label>Correo</label>
        <input type="email" name="email" required>
        <label>Contraseña</label>
        <input type="password" name="password" required>
        <button type="submit">Ingresar</button>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="error"><?php echo htmlspecialchars($_SESSION['error']);
                                unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <a class="btn" href="index.php">regresar a index</a>
    </form>
</body>

</html>