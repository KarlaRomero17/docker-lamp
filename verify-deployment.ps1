# verify-deployment.ps1

Write-Host "=== VERIFICANDO DESPLIEGUE COMPLETO ===" -ForegroundColor Green

# 1. Esperar
Write-Host "1. Esperando a que el despliegue se estabilice..." -ForegroundColor Yellow
Start-Sleep -Seconds 180

# 2. Verificar servicio
Write-Host "2. Verificando servicio ECS..." -ForegroundColor Yellow
aws ecs describe-services --cluster lamp-cluster --services lamp-service --region us-east-1 --query 'services[0].[runningCount,desiredCount,deployments]'

# 3. Verificar tasks
Write-Host "3. Verificando tasks..." -ForegroundColor Yellow
aws ecs list-tasks --cluster lamp-cluster --region us-east-1

# 4. Verificar Target Group
Write-Host "4. Verificando Target Group..." -ForegroundColor Yellow
aws elbv2 describe-target-health `
    --target-group-arn arn:aws:elasticloadbalancing:us-east-1:146009966183:targetgroup/lamp-tg/43e6ebf813eb8bcc `
    --region us-east-1

# 5. Verificar logs
Write-Host "5. Verificando logs..." -ForegroundColor Yellow
$taskArn = aws ecs list-tasks --cluster lamp-cluster --region us-east-1 --query 'taskArns[0]' --output text
if ($taskArn) {
    $logStream = aws ecs describe-tasks --cluster lamp-cluster --tasks $taskArn --region us-east-1 --query 'tasks[0].attachments[0].details[?name==`logStreamName`].value' --output text
    if ($logStream) {
        Write-Host "Últimos logs:" -ForegroundColor Cyan
        aws logs get-log-events --log-group-name "/ecs/lamp-app" --log-stream-name $logStream --region us-east-1 --limit 20
    }
}

# 6. Probar URL final
Write-Host "6. Probando URL final..." -ForegroundColor Yellow
Write-Host "URL: http://lamp-alb-578508375.us-east-1.elb.amazonaws.com" -ForegroundColor Cyan

Write-Host "=== VERIFICACIÓN COMPLETADA ===" -ForegroundColor Green
