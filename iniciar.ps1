# Inicia o servidor web PHP para o Sistema Restaurante
# Execute: .\iniciar.ps1
# Acesse: http://localhost:8000

$phpExe = "C:\php\php.exe"
$rootDir = $PSScriptRoot
$port = 8000

if (-not (Test-Path $phpExe)) {
    Write-Error "PHP não encontrado em $phpExe. Ajuste o caminho em iniciar.ps1."
}

Write-Host "Iniciando servidor em http://localhost:$port" -ForegroundColor Green
Write-Host "Pasta: $rootDir" -ForegroundColor Cyan
Write-Host "Pressione Ctrl+C para parar." -ForegroundColor Yellow
Write-Host ""

Set-Location $rootDir
& $phpExe -S "localhost:$port"
