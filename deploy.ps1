# deploy.ps1 - Despliegue completo de la aplicación Laravel en ECS

param(
    [switch]$ForceRedeploy = $false
)

Write-Host "=== DESPLIEGUE COMPLETO LARAVEL ECS ===" -ForegroundColor Green
Write-Host "Iniciando: $(Get-Date)" -ForegroundColor Yellow

# Configuración
$ECR_REPO = "146009966183.dkr.ecr.us-east-1.amazonaws.com/laravel-lamp-app"
$CLUSTER = "lamp-cluster"
$SERVICE = "lamp-service"
$TASK_FAMILY = "lamp-task"
$REGION = "us-east-1"

function Check-Command {
    param($Command, $ErrorMessage)
    try {
        & $Command 2>&1 | Out-Null
        return $true
    } catch {
        Write-Host "❌ $ErrorMessage" -ForegroundColor Red
        return $false
    }
}

function Wait-For-Deployment {
    Write-Host "⏳ Esperando a que el servicio se estabilice..." -ForegroundColor Yellow
    $attempts = 0
    $maxAttempts = 30

    while ($attempts -lt $maxAttempts) {
        $service = aws ecs describe-services --cluster $CLUSTER --services $SERVICE --region $REGION --output json | ConvertFrom-Json

        if ($service.services[0].runningCount -eq $service.services[0].desiredCount -and
            $service.services[0].deployments.Count -eq 1 -and
            $service.services[0].deployments[0].status -eq "PRIMARY") {
            Write-Host "✅ Servicio estable - Running: $($service.services[0].runningCount)/$($service.services[0].desiredCount)" -ForegroundColor Green
            return $true
        }

        $attempts++
        Write-Host "⏳ Intentos: $attempts/$maxAttempts - Running: $($service.services[0].runningCount)/$($service.services[0].desiredCount)" -ForegroundColor Yellow
        Start-Sleep -Seconds 10
    }

    Write-Host "❌ Timeout esperando por el servicio" -ForegroundColor Red
    return $false
}

# PASO 1: Verificar que estamos autenticados
Write-Host "`n1. 🔐 Verificando autenticación AWS..." -ForegroundColor Cyan
$caller = aws sts get-caller-identity --query Arn --output text
if (-not $caller) {
    Write-Host "❌ No autenticado en AWS. Ejecuta: aws configure" -ForegroundColor Red
    exit 1
}
Write-Host "✅ Autenticado como: $caller" -ForegroundColor Green

# PASO 2: Login a ECR
Write-Host "`n2. 🔑 Autenticando con ECR..." -ForegroundColor Cyan
try {
    $ecrLogin = aws ecr get-login-password --region $REGION | docker login --username AWS --password-stdin $ECR_REPO
    Write-Host "✅ Login ECR exitoso" -ForegroundColor Green
} catch {
    Write-Host "❌ Error en login ECR: $_" -ForegroundColor Red
    exit 1
}

# PASO 3: Construir imagen Docker
Write-Host "`n3. 🐳 Construyendo imagen Docker..." -ForegroundColor Cyan
try {
    docker build -t laravel-lamp-app -f Dockerfile.production .
    if ($LASTEXITCODE -ne 0) {
        throw "Error en build Docker"
    }
    Write-Host "✅ Imagen Docker construida" -ForegroundColor Green
} catch {
    Write-Host "❌ Error construyendo imagen Docker: $_" -ForegroundColor Red
    exit 1
}

# PASO 4: Taggear y subir imagen
Write-Host "`n4. 📦 Subiendo imagen a ECR..." -ForegroundColor Cyan
try {
    docker tag laravel-lamp-app:latest "${ECR_REPO}:latest"
    docker push "${ECR_REPO}:latest"
    Write-Host "✅ Imagen subida a ECR" -ForegroundColor Green
} catch {
    Write-Host "❌ Error subiendo imagen a ECR: $_" -ForegroundColor Red
    exit 1
}

# PASO 5: Registrar Task Definition
Write-Host "`n5. 📝 Registrando Task Definition..." -ForegroundColor Cyan
try {
    if (Test-Path "task-definition-with-db.json") {
        $taskDef = aws ecs register-task-definition --cli-input-json file://task-definition-with-db.json --region $REGION --output json
        Write-Host "✅ Task Definition registrada" -ForegroundColor Green
    } else {
        Write-Host "❌ Archivo task-definition-with-db.json no encontrado" -ForegroundColor Red
        exit 1
    }
} catch {
    Write-Host "❌ Error registrando Task Definition: $_" -ForegroundColor Red
    exit 1
}

# PASO 6: Verificar si el servicio existe
Write-Host "`n6. 🔍 Verificando servicio existente..." -ForegroundColor Cyan
$serviceExists = aws ecs describe-services --cluster $CLUSTER --services $SERVICE --region $REGION --query 'services[0].status' --output text 2>$null

if ($serviceExists -eq "ACTIVE" -and -not $ForceRedeploy) {
    Write-Host "✅ Servicio existe, actualizando..." -ForegroundColor Green

    # Actualizar servicio existente
    try {
        $update = aws ecs update-service `
            --cluster $CLUSTER `
            --service $SERVICE `
            --task-definition $TASK_FAMILY `
            --force-new-deployment `
            --region $REGION
        Write-Host "✅ Servicio actualizado, nuevo despliegue forzado" -ForegroundColor Green
    } catch {
        Write-Host "❌ Error actualizando servicio: $_" -ForegroundColor Red
        exit 1
    }
} else {
    Write-Host "🆕 Creando nuevo servicio..." -ForegroundColor Yellow

    # Crear nuevo servicio
    try {
        $create = aws ecs create-service `
            --cluster $CLUSTER `
            --service-name $SERVICE `
            --task-definition $TASK_FAMILY `
            --desired-count 1 `
            --launch-type FARGATE `
            --network-configuration "awsvpcConfiguration={subnets=[subnet-0a5f3cb9270b3297c,subnet-07112c76096681fc6,subnet-075235ca02cad25d2],securityGroups=[sg-0dcd6a61e1e69082a],assignPublicIp=ENABLED}" `
            --region $REGION
        Write-Host "✅ Servicio creado exitosamente" -ForegroundColor Green
    } catch {
        Write-Host "❌ Error creando servicio: $_" -ForegroundColor Red
        exit 1
    }
}

# PASO 7: Esperar por el despliegue
Write-Host "`n7. ⏳ Esperando por el despliegue..." -ForegroundColor Cyan
if (-not (Wait-For-Deployment)) {
    Write-Host "❌ El despliegue no se completó correctamente" -ForegroundColor Red
    exit 1
}

# PASO 8: Configurar Load Balancer
Write-Host "`n8. 🔄 Configurando Load Balancer..." -ForegroundColor Cyan
try {
    $updateLB = aws ecs update-service `
        --cluster $CLUSTER `
        --service $SERVICE `
        --load-balancers "targetGroupArn=arn:aws:elasticloadbalancing:us-east-1:146009966183:targetgroup/lamp-tg/43e6ebf813eb8bcc,containerName=lamp-app,containerPort=80" `
        --region $REGION
    Write-Host "✅ Load Balancer configurado" -ForegroundColor Green
} catch {
    Write-Host "⚠️  No se pudo configurar Load Balancer (puede ser normal): $_" -ForegroundColor Yellow
}

# PASO 9: Verificar estado final
Write-Host "`n9. ✅ Verificando estado final..." -ForegroundColor Cyan
Start-Sleep -Seconds 30

# Obtener información de la task
$taskArn = aws ecs list-tasks --cluster $CLUSTER --region $REGION --query 'taskArns[0]' --output text
if ($taskArn) {
    $publicIP = aws ecs describe-tasks --cluster $CLUSTER --tasks $taskArn --region $REGION --query 'tasks[0].attachments[0].details[?name==`publicIPv4Address`].value' --output text
    if ($publicIP) {
        Write-Host "🌐 IP Pública de la task: $publicIP" -ForegroundColor Cyan
        Write-Host "🔗 URL directa: http://$publicIP" -ForegroundColor Cyan
    }
}

# Verificar Target Group
Write-Host "`n🔍 Verificando Target Group..." -ForegroundColor Cyan
$targetHealth = aws elbv2 describe-target-health `
    --target-group-arn arn:aws:elasticloadbalancing:us-east-1:146009966183:targetgroup/lamp-tg/43e6ebf813eb8bcc `
    --region $REGION

Write-Host "`n🎉 DESPLIEGUE COMPLETADO!" -ForegroundColor Green
Write-Host "📅 Finalizado: $(Get-Date)" -ForegroundColor Yellow
Write-Host "`n🌐 URLs de tu aplicación:" -ForegroundColor Cyan
Write-Host "   Load Balancer: http://lamp-alb-578508375.us-east-1.elb.amazonaws.com" -ForegroundColor White
if ($publicIP) {
    Write-Host "   IP Directa:    http://$publicIP" -ForegroundColor White
}

Write-Host "`n📋 Comandos útiles:" -ForegroundColor Cyan
Write-Host "   Ver logs: aws logs describe-log-groups --log-group-name-prefix '/ecs/lamp-app' --region us-east-1" -ForegroundColor Gray
Write-Host "   Ver servicio: aws ecs describe-services --cluster lamp-cluster --services lamp-service --region us-east-1" -ForegroundColor Gray
Write-Host "   Ver tasks: aws ecs list-tasks --cluster lamp-cluster --region us-east-1" -ForegroundColor Gray

Write-Host "`n⚠️  Nota: El Load Balancer puede tomar 2-5 minutos adicionales para pasar health checks" -ForegroundColor Yellow


## Para ejecutar el script:
## .\deploy.ps1 -ForceRedeploy
