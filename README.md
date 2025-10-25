# Proyecto LAMP con Docker

Este proyecto contiene un **entorno LAMP (Linux + Apache + MySQL + PHP)** completamente configurado usando **Docker Compose**, con soporte opcional para **phpMyAdmin**.

Permite levantar rápidamente un servidor web PHP con base de datos MySQL de forma aislada y sin necesidad de instalaciones adicionales en tu sistema operativo.

## 📁 Estructura del proyecto

```plaintext
mi-lamp-docker/
├── docker-compose.yml     # Configuración de servicios Docker
├── Dockerfile            # Imagen PHP + Apache personalizada
├── .env                  # Variables de entorno (credenciales)
├── .gitignore           # Archivos ignorados por Git
├── www/                 # Código fuente PHP
│   ├── index.php
│   └── info.php
├── mysql-data/         # Persistencia de MySQL
└── mysql-init/        # Scripts SQL de inicialización
```

---


## ⚙️ Requisitos previos

- **Docker Desktop** instalado y ejecutándose  
  [Descargar Docker](https://www.docker.com/products/docker-desktop/)  
- **Visual Studio Code** u otro editor de código (opcional)  
- Terminal (PowerShell en Windows o bash en Linux/macOS)  

---

## 📝 Configuración del entorno

1. Copia el archivo `.env.example` (si existe) a `.env` y define tus credenciales:

```env
MYSQL_ROOT_PASSWORD=root
MYSQL_DATABASE=docker-lamp
MYSQL_USER=usuario
MYSQL_PASSWORD=usuario123

root es la contraseña del administrador de MySQL.
usuario es un usuario normal para la base de datos docker-lamp.
```

## 🚀 Levantar el proyecto

1. Abrir terminal en la carpeta raíz del proyecto.

2. Construir y levantar los contenedores:

```bash
docker compose up -d --build
```

3. Verificar que los contenedores están corriendo:
```bash
docker ps
```

Deberias ver algo similar a:

```plaintext
    CONTAINER ID   NAME            IMAGE
xxxxxxx        lamp_web        lamp-docker-web
xxxxxxx        lamp_db         mysql:8.0
xxxxxxx        lamp_phpmyadmin phpmyadmin/phpmyadmin
```

## 🌐 Acceder a la aplicación

Página principal PHP: http://localhost:8080

Verás tu index.php mostrando la versión de PHP y la conexión a la base de datos.

phpMyAdmin (opcional): http://localhost:8081

Servidor: db

Usuario: root

Contraseña: root

## 🛠️ Comandos utiles

- Detener los contenedores:
```bash
docker down
```

- Detener y eliminar contenedores + volumenes:
```bash
docker down -v
```
- Ver logs en tiempo real:
```bash
docker logs -f lamp_web
docker logs -f lamp_db
```