<?php
$servername = getenv('DB_HOST') ?: 'mysql';
$username = getenv('DB_USER') ?: 'usuario';
$password = getenv('DB_PASS') ?: 'usuario123';
$dbname = getenv('DB_NAME') ?: 'docker-lamp';

// Muestra página simple
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Mi LAMP con Docker</title>
  <style>
    .btn {
      display: inline-block;
      padding: 10px 16px;
      background: #007bff;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
    }

    .btn:hover {
      background: #484E53FF;
    }
  </style>
</head>

<body>
  <h1>¡Hola desde LAMP en Docker!</h1>
  <p>PHP version: <?php echo phpversion(); ?></p>
  <hr>
  <h3>Conexión a MySQL</h3>
  <?php
  $conn = new mysqli('db', $username, $password, $dbname);
  if ($conn->connect_error) {
    echo "<p style='color:red;'>Error conectando a la BD: " . $conn->connect_error . "</p>";
  } else {
    echo "<p style='color:green;'>Conectado a la BD '{$dbname}' correctamente.</p>";
    $result = $conn->query("SHOW TABLES");
    if ($result && $result->num_rows > 0) {
      echo "<p>Tablas en la BD:</p><ul>";
      while ($row = $result->fetch_array()) {
        echo "<li>{$row[0]}</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No hay tablas (aún).</p>";
    }
    $conn->close();
  }
  ?>
  <a class="btn" href="login.php">INICIAR SESION</a>
</body>

</html>