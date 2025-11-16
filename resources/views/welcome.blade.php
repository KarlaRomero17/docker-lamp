<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel LAMP Docker') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #7c3aed;
            --accent: #06b6d4;
            --dark: #1e293b;
            --light: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: var(--light);
            min-height: 100vh;
        }

        .tech-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
        }

        .tech-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            box-shadow: 0 8px 32px rgba(37, 99, 235, 0.3);
        }

        .feature-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            border-color: var(--accent);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .nav-pill {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding: 0.5rem;
            backdrop-filter: blur(10px);
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .tech-stack {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .tech-badge {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            backdrop-filter: blur(10px);
        }

        .deployment-card {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
            border: 1px solid rgba(37, 99, 235, 0.3);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .deployment-card:hover {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.2) 0%, rgba(124, 58, 237, 0.2) 100%);
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="#">
                <i class="fas fa-server me-2"></i>
                LAMP Docker
            </a>

            @if (Route::has('login'))
                <div class="nav-pill">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-user-plus me-2"></i>Register
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="pulse-animation">
                        <h1 class="display-4 fw-bold mb-4">
                            Modern LAMP Stack with
                            <span class="tech-gradient text-gradient" style="-webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                Docker & AWS
                            </span>
                        </h1>
                    </div>
                    <p class="lead mb-4 text-light-emphasis">
                        Despliegue avanzado de aplicaciones PHP/Laravel utilizando contenedores Docker,
                        AWS ECS, y arquitecturas serverless para máxima escalabilidad.
                    </p>

                    <div class="tech-stack mb-4">
                        <span class="tech-badge">
                            <i class="fab fa-docker me-2"></i>Docker
                        </span>
                        <span class="tech-badge">
                            <i class="fab fa-laravel me-2"></i>Laravel
                        </span>
                        <span class="tech-badge">
                            <i class="fab fa-aws me-2"></i>AWS ECS
                        </span>
                        <span class="tech-badge">
                            <i class="fas fa-bolt me-2"></i>Serverless
                        </span>
                        <span class="tech-badge">
                            <i class="fas fa-database me-2"></i>MySQL
                        </span>
                    </div>

                    <div class="d-flex gap-3">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-rocket me-2"></i>Get Started
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-info-circle me-2"></i>Learn More
                        </a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="glass-card p-5 text-center">
                        <div class="tech-icon">
                            <i class="fab fa-docker text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Containerized Architecture</h4>
                        <p class="text-light-emphasis mb-4">
                            Aplicación LAMP completa ejecutándose en contenedores Docker
                            para desarrollo y producción consistentes.
                        </p>
                        <div class="deployment-card p-3 mb-3">
                            <i class="fab fa-aws text-warning me-2"></i>
                            <strong>Amazon ECS Ready</strong>
                        </div>
                        <div class="deployment-card p-3">
                            <i class="fas fa-cloud text-info me-2"></i>
                            <strong>Serverless Functions</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Project Architecture</h2>
                <p class="text-light-emphasis">Infraestructura moderna y escalable</p>
            </div>

            <div class="row g-4">
                <!-- Docker Feature -->
                <div class="col-md-4">
                    <div class="glass-card feature-card p-4 h-100">
                        <div class="tech-icon">
                            <i class="fab fa-docker text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Docker Containers</h5>
                        <p class="text-light-emphasis mb-3">
                            Servicios LAMP completamente containerizados:
                        </p>
                        <ul class="list-unstyled text-light-emphasis">
                            <li><i class="fas fa-check text-success me-2"></i>PHP 8.2 + Apache</li>
                            <li><i class="fas fa-check text-success me-2"></i>MySQL 8.0</li>
                            <li><i class="fas fa-check text-success me-2"></i>phpMyAdmin</li>
                            <li><i class="fas fa-check text-success me-2"></i>Volúmenes persistentes</li>
                        </ul>
                    </div>
                </div>

                <!-- AWS Feature -->
                <div class="col-md-4">
                    <div class="glass-card feature-card p-4 h-100">
                        <div class="tech-icon">
                            <i class="fab fa-aws text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Cloud Deployment</h5>
                        <p class="text-light-emphasis mb-3">
                            Despliegue en AWS Cloud:
                        </p>
                        <ul class="list-unstyled text-light-emphasis">
                            <li><i class="fas fa-check text-success me-2"></i>Elastic Container Service</li>
                            <li><i class="fas fa-check text-success me-2"></i>Application Load Balancer</li>
                            <li><i class="fas fa-check text-success me-2"></i>RDS MySQL</li>
                            <li><i class="fas fa-check text-success me-2"></i>Auto Scaling Groups</li>
                        </ul>
                    </div>
                </div>

                <!-- Serverless Feature -->
                <div class="col-md-4">
                    <div class="glass-card feature-card p-4 h-100">
                        <div class="tech-icon">
                            <i class="fas fa-bolt text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Serverless Functions</h5>
                        <p class="text-light-emphasis mb-3">
                            Arquitectura sin servidor:
                        </p>
                        <ul class="list-unstyled text-light-emphasis">
                            <li><i class="fas fa-check text-success me-2"></i>AWS Lambda</li>
                            <li><i class="fas fa-check text-success me-2"></i>API Gateway</li>
                            <li><i class="fas fa-check text-success me-2"></i>Cloud Functions</li>
                            <li><i class="fas fa-check text-success me-2"></i>Pago por uso</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech Stack Section -->
    <section class="py-5">
        <div class="container">
            <div class="glass-card p-5">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3 class="fw-bold mb-3">Ready for Production</h3>
                        <p class="text-light-emphasis mb-4">
                            Esta aplicación está configurada para despliegue en entornos de producción
                            con las mejores prácticas de DevOps y arquitectura cloud.
                        </p>
                        <div class="row">
                            <div class="col-sm-6">
                                <ul class="list-unstyled text-light-emphasis">
                                    <li><i class="fas fa-check-circle text-success me-2"></i>Autenticación Laravel</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i>Base de datos MySQL</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i>Docker Compose</li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <ul class="list-unstyled text-light-emphasis">
                                    <li><i class="fas fa-check-circle text-success me-2"></i>CI/CD Pipeline</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i>Monitoring</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i>SSL/TLS Ready</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="p-4">
                            <i class="fas fa-cloud-upload-alt fa-4x tech-gradient text-gradient mb-3" style="-webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                            <h5 class="fw-bold">Cloud Native</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 mt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-light-emphasis">
                        &copy; {{ date('Y') }} LAMP Docker Project. CICLO II 2025 - GSW.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="tech-stack justify-content-end">
                        <span class="tech-badge">
                            <i class="fab fa-laravel me-1"></i> v{{ Illuminate\Foundation\Application::VERSION }}
                        </span>
                        <span class="tech-badge">
                            <i class="fab fa-php me-1"></i> v{{ PHP_VERSION }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Simple animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const featureCards = document.querySelectorAll('.feature-card');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            featureCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>
