<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($email) || empty($password)) {
    $_SESSION['error'] = 'Completa todos los campos.';
    header('Location: login.php');
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id, nombre, email, password FROM usuarios WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // autenticado
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nombre'] = $user['nombre'];
        $_SESSION['usuario_email'] = $user['email'];
        header('Location: bienvenida.php');
        exit;
    } else {
        $_SESSION['error'] = 'Correo o contraseña incorrectos.';
        header('Location: login.php');
        exit;
    }
} catch (Exception $e) {
    // para debug en desarrollo:
    $_SESSION['error'] = 'Error al consultar la base de datos: ' . $e->getMessage();
    header('Location: login.php');
    exit;
}
