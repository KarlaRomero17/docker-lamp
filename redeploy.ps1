# redeploy.ps1 - Redeploy rápido de la aplicación Laravel en ECS

param(
    [switch]$Force = $false
)

Write-Host "=== REDEPLOY RÁPIDO LARAVEL ECS ===" -ForegroundColor Green
Write-Host "Iniciando: $(Get-Date)" -ForegroundColor Yellow

# Configuración
$ECR_REPO = "146009966183.dkr.ecr.us-east-1.amazonaws.com/laravel-lamp-app"
$CLUSTER = "lamp-cluster"
$SERVICE = "lamp-service"
$TASK_FAMILY = "lamp-task"
$REGION = "us-east-1"
$ALB_URL = "http://lamp-alb-578508375.us-east-1.elb.amazonaws.com"

function Show-Status {
    param($Message, $IsError = $false)
    if ($IsError) {
        Write-Host "❌ $Message" -ForegroundColor Red
    } else {
        Write-Host "✅ $Message" -ForegroundColor Green
    }
}

function Wait-For-Service {
    Write-Host "⏳ Esperando que el servicio se estabilice..." -ForegroundColor Yellow
    for ($i = 1; $i -le 30; $i++) {
        $service = aws ecs describe-services --cluster $CLUSTER --services $SERVICE --region $REGION --output json | ConvertFrom-Json
        $running = $service.services[0].runningCount
        $desired = $service.services[0].desiredCount

        if ($running -eq $desired -and $service.services[0].deployments.Count -eq 1) {
            Write-Host "✅ Servicio estable - Tasks: $running/$desired" -ForegroundColor Green
            return $true
        }

        Write-Host "⏳ [$i/30] Tasks: $running/$desired" -ForegroundColor Yellow
        Start-Sleep -Seconds 10
    }
    Write-Host "❌ Timeout esperando por el servicio" -ForegroundColor Red
    return $false
}

# PASO 1: Verificar que el servicio existe
Write-Host "`n1. 🔍 Verificando servicio..." -ForegroundColor Cyan
$serviceExists = aws ecs describe-services --cluster $CLUSTER --services $SERVICE --region $REGION --query 'services[0].status' --output text 2>$null

if (-not $serviceExists -or $serviceExists -ne "ACTIVE") {
    Write-Host "❌ El servicio $SERVICE no existe o no está activo" -ForegroundColor Red
    Write-Host "💡 Ejecuta deploy.ps1 primero para crear el servicio" -ForegroundColor Yellow
    exit 1
}
Show-Status "Servicio $SERVICE está activo"

# PASO 2: Login a ECR (rápido)
Write-Host "`n2. 🔑 Autenticando con ECR..." -ForegroundColor Cyan
try {
    $ecrLogin = aws ecr get-login-password --region $REGION | docker login --username AWS --password-stdin $ECR_REPO 2>&1 | Out-Null
    Show-Status "Login ECR exitoso"
} catch {
    Show-Status "Error en login ECR: $_" $true
    exit 1
}

# PASO 3: Construir imagen Docker
Write-Host "`n3. 🐳 Construyendo imagen Docker..." -ForegroundColor Cyan
try {
    docker build -t laravel-lamp-app -f Dockerfile.production .
    if ($LASTEXITCODE -ne 0) { throw "Build failed" }
    Show-Status "Imagen Docker construida"
} catch {
    Show-Status "Error construyendo imagen Docker: $_" $true
    exit 1
}

# PASO 4: Subir imagen a ECR
Write-Host "`n4. 📦 Subiendo imagen a ECR..." -ForegroundColor Cyan
try {
    docker tag laravel-lamp-app:latest "${ECR_REPO}:latest"
    docker push "${ECR_REPO}:latest"
    Show-Status "Imagen subida a ECR"
} catch {
    Show-Status "Error subiendo imagen a ECR: $_" $true
    exit 1
}

# PASO 5: Registrar nueva Task Definition
Write-Host "`n5. 📝 Actualizando Task Definition..." -ForegroundColor Cyan
try {
    if (Test-Path "task-definition-with-db.json") {
        $taskDef = aws ecs register-task-definition --cli-input-json file://task-definition-with-db.json --region $REGION --output json
        Show-Status "Task Definition actualizada"
    } else {
        Show-Status "Archivo task-definition-with-db.json no encontrado" $true
        exit 1
    }
} catch {
    Show-Status "Error actualizando Task Definition: $_" $true
    exit 1
}

# PASO 6: Forzar nuevo despliegue
Write-Host "`n6. 🚀 Forzando nuevo despliegue..." -ForegroundColor Cyan
try {
    $update = aws ecs update-service `
        --cluster $CLUSTER `
        --service $SERVICE `
        --task-definition $TASK_FAMILY `
        --force-new-deployment `
        --region $REGION
    Show-Status "Nuevo despliegue iniciado"
} catch {
    Show-Status "Error forzando despliegue: $_" $true
    exit 1
}

# PASO 7: Esperar por el despliegue
Write-Host "`n7. ⏳ Monitoreando despliegue..." -ForegroundColor Cyan
if (-not (Wait-For-Service)) {
    Show-Status "El despliegue no se completó correctamente" $true
    exit 1
}

# PASO 8: Verificación rápida
Write-Host "`n8. ✅ Verificación final..." -ForegroundColor Cyan
Start-Sleep -Seconds 10

# Obtener IP pública de la task
$taskArn = aws ecs list-tasks --cluster $CLUSTER --region $REGION --query 'taskArns[0]' --output text
if ($taskArn) {
    $publicIP = aws ecs describe-tasks --cluster $CLUSTER --tasks $taskArn --region $REGION --query 'tasks[0].attachments[0].details[?name==`publicIPv4Address`].value' --output text
    if ($publicIP) {
        Write-Host "🌐 IP Pública: $publicIP" -ForegroundColor Cyan
    }
}

# Verificar Target Group brevemente
Write-Host "`n🔍 Estado del Load Balancer..." -ForegroundColor Cyan
try {
    $targets = aws elbv2 describe-target-health `
        --target-group-arn arn:aws:elasticloadbalancing:us-east-1:146009966183:targetgroup/lamp-tg/43e6ebf813eb8bcc `
        --region $REGION --query 'TargetHealthDescriptions' --output json | ConvertFrom-Json

    if ($targets.Count -gt 0) {
        Write-Host "✅ Targets en Target Group: $($targets.Count)" -ForegroundColor Green
    } else {
        Write-Host "⚠️  No hay targets registrados (puede tomar 2-5 minutos)" -ForegroundColor Yellow
    }
} catch {
    Write-Host "⚠️  No se pudo verificar Target Group" -ForegroundColor Yellow
}

Write-Host "`n🎉 REDEPLOY COMPLETADO!" -ForegroundColor Green
Write-Host "📅 Finalizado: $(Get-Date)" -ForegroundColor Yellow

Write-Host "`n🌐 URLs de tu aplicación:" -ForegroundColor Cyan
Write-Host "   Load Balancer: $ALB_URL" -ForegroundColor White
if ($publicIP) {
    Write-Host "   IP Directa:    http://$publicIP" -ForegroundColor White
}

Write-Host "`n💡 Tips:" -ForegroundColor Cyan
Write-Host "   - El Load Balancer puede tomar 2-5 minutos en responder" -ForegroundColor Gray
Write-Host "   - Para ver logs: aws logs describe-log-groups --log-group-name-prefix '/ecs/lamp-app'" -ForegroundColor Gray
Write-Host "   - Para ver estado: aws ecs describe-services --cluster $CLUSTER --services $SERVICE" -ForegroundColor Gray

# Probar la URL después de 1 minuto (opcional)
Write-Host "`n⏳ Probando Load Balancer en 60 segundos..." -ForegroundColor Yellow
Start-Job -ScriptBlock {
    Start-Sleep -Seconds 60
    try {
        $response = Invoke-WebRequest -Uri $using:ALB_URL -UseBasicParsing -TimeoutSec 10
        Write-Host "`n🎉 Load Balancer RESPONDE: Status $($response.StatusCode)" -ForegroundColor Green
    } catch {
        Write-Host "`n⚠️  Load Balancer aún no responde: $($_.Exception.Message)" -ForegroundColor Yellow
        Write-Host "   Esto es normal, puede tomar 2-5 minutos..." -ForegroundColor Gray
    }
} | Out-Null


## Para ejecutar el script:
## .\redeploy.ps1 -Force
