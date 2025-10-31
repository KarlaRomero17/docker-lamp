<?php
$servername = getenv('DB_HOST') ?: 'mysql';
$username = getenv('DB_USER') ?: 'usuario';
$password = getenv('DB_PASS') ?: 'usuario123';
$dbname = getenv('DB_NAME') ?: 'docker-lamp';

// Información del sistema
$php_version = phpversion();
$server_software = $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido';
$db_status = 'Desconocido';
$tables = [];

// Probar conexión a BD
$conn = new mysqli('db', $username, $password, $dbname);
if ($conn->connect_error) {
    $db_status = 'error';
} else {
    $db_status = 'success';
    $result = $conn->query("SHOW TABLES");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard LAMP con Docker</title>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --dark: #212529;
            --light: #f8f9fa;
            --gray: #6c757d;
            --border-radius: 12px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--dark);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo h1 {
            font-size: 1.8rem;
            color: var(--primary);
        }

        .logo-icon {
            font-size: 2rem;
            color: var(--primary);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            box-shadow: var(--box-shadow);
        }

        .btn:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 25px;
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .card-icon {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-success {
            background: rgba(76, 201, 240, 0.2);
            color: var(--success);
        }

        .status-error {
            background: rgba(247, 37, 133, 0.2);
            color: var(--danger);
        }

        .status-warning {
            background: rgba(248, 150, 30, 0.2);
            color: var(--warning);
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--gray);
        }

        .info-value {
            font-weight: 600;
        }

        .table-list {
            list-style: none;
        }

        .table-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-item:last-child {
            border-bottom: none;
        }

        .table-icon {
            color: var(--primary);
        }

        .empty-state {
            text-align: center;
            padding: 20px;
            color: var(--gray);
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: var(--gray);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .dashboard {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <div class="logo">
                <div class="logo-icon">🚀</div>
                <h1>Dashboard LAMP con Docker</h1>
            </div>
            <a class="btn" href="login.php">
                <span>👤</span> INICIAR SESIÓN
            </a>
        </header>

        <div class="dashboard">
            <!-- Tarjeta de información del servidor -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">🖥️</div>
                    <h2 class="card-title">Información del Servidor</h2>
                </div>
                <div class="card-content">
                    <div class="info-item">
                        <span class="info-label">PHP Version:</span>
                        <span class="info-value"><?php echo $php_version; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Servidor:</span>
                        <span class="info-value"><?php echo $server_software; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Estado:</span>
                        <span class="status status-success">En línea</span>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de base de datos -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">🗄️</div>
                    <h2 class="card-title">Base de Datos</h2>
                    <span class="status <?php echo $db_status === 'success' ? 'status-success' : 'status-error'; ?>">
                        <?php echo $db_status === 'success' ? 'Conectado' : 'Error'; ?>
                    </span>
                </div>
                <div class="card-content">
                    <div class="info-item">
                        <span class="info-label">Nombre BD:</span>
                        <span class="info-value"><?php echo $dbname; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Host:</span>
                        <span class="info-value"><?php echo $servername; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Usuario:</span>
                        <span class="info-value"><?php echo $username; ?></span>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de tablas -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">📊</div>
                    <h2 class="card-title">Tablas en la Base de Datos</h2>
                    <span class="status <?php echo count($tables) > 0 ? 'status-success' : 'status-warning'; ?>">
                        <?php echo count($tables); ?> tabla(s)
                    </span>
                </div>
                <div class="card-content">
                    <?php if (count($tables) > 0): ?>
                        <ul class="table-list">
                            <?php foreach ($tables as $table): ?>
                                <li class="table-item">
                                    <span class="table-icon">📋</span>
                                    <span><?php echo $table; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>No hay tablas en la base de datos</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Sistema LAMP con Docker &copy; <?php echo date('Y'); ?></p>
        </div>
    </div>
</body>

</html>