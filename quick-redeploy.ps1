# fast-redeploy.ps1 - La forma más rápida de redeploy
Write-Host "⚡ REDEPLOY SUPER RÁPIDO" -ForegroundColor Green

# 1. Build y push de la imagen
Write-Host "`n🐳 Construyendo y subiendo imagen Docker..." -ForegroundColor Cyan
docker build -t laravel-lamp-app -f Dockerfile.production .
docker tag laravel-lamp-app:latest 146009966183.dkr.ecr.us-east-1.amazonaws.com/laravel-lamp-app:latest
docker push 146009966183.dkr.ecr.us-east-1.amazonaws.com/laravel-lamp-app:latest

# 2. Forzar nuevo despliegue
Write-Host "`n🚀 Forzando nuevo despliegue en ECS..." -ForegroundColor Cyan
aws ecs update-service --cluster lamp-cluster --service lamp-service --force-new-deployment --region us-east-1

Write-Host "`n✅ ¡Redeploy iniciado!" -ForegroundColor Green
Write-Host "`n📊 Para monitorear el estado:" -ForegroundColor Yellow
Write-Host "   aws ecs describe-services --cluster lamp-cluster --services lamp-service --region us-east-1 --query 'services[0].{Running:runningCount, Desired:desiredCount, Status:status}' --output table" -ForegroundColor Gray

Write-Host "`n🌐 Tu aplicación estará disponible en:" -ForegroundColor Cyan
Write-Host "   http://lamp-alb-578508375.us-east-1.elb.amazonaws.com" -ForegroundColor White

Write-Host "`n⏱️  El redeploy tomará 2-3 minutos en completarse." -ForegroundColor Yellow

## Para ejecutar el script powershelll:
## .\quick-redeploy.ps1
