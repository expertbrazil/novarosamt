# Script PowerShell para corrigir permissões via Docker

Write-Host "Corrigindo permissões do storage no container Docker..." -ForegroundColor Yellow

# Ajustar permissões via Docker
docker-compose exec -T app bash -c "chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache"
docker-compose exec -T app bash -c "chown -R novarosamt:www-data /var/www/html/storage /var/www/html/bootstrap/cache"

Write-Host "Permissões corrigidas!" -ForegroundColor Green
Write-Host ""
Write-Host "Reinicie os containers se necessário:" -ForegroundColor Yellow
Write-Host "docker-compose restart app" -ForegroundColor Cyan


