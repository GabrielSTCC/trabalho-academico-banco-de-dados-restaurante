# Setup do banco de dados - Sistema Restaurante
# Execute na raiz do projeto: .\setup.ps1
# Usa a senha de config.php (local) ou pede no terminal.

$ErrorActionPreference = "Stop"

$mysqlExe = "C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe"
$rootDir  = $PSScriptRoot
$configPath = Join-Path $rootDir "config.php"
$password = $null

if (Test-Path $configPath) {
    $configContent = Get-Content $configPath -Raw
    if ($configContent -match "'password'\s*=>\s*'([^']*)'") {
        $password = $Matches[1]
    }
}

if (-not $password) {
    Write-Host "config.php nao encontrado ou sem senha." -ForegroundColor Yellow
    $secure = Read-Host "Senha do MySQL (usuario root)" -AsSecureString
    $password = [Runtime.InteropServices.Marshal]::PtrToStringAuto(
        [Runtime.InteropServices.Marshal]::SecureStringToBSTR($secure)
    )
}

if (-not (Test-Path $mysqlExe)) {
    Write-Error "mysql.exe nao encontrado em: $mysqlExe"
}

Write-Host "Conectando ao MySQL..." -ForegroundColor Cyan

$scripts = @(
    "$rootDir\sql\01_ddl.sql",
    "$rootDir\sql\02_dados_exemplo.sql"
)

foreach ($script in $scripts) {
    if (-not (Test-Path $script)) {
        Write-Error "Script nao encontrado: $script"
    }

    Write-Host "Executando $(Split-Path $script -Leaf)..." -ForegroundColor Yellow

    Get-Content $script -Raw -Encoding UTF8 | & $mysqlExe -u root "-p$password" --default-character-set=utf8mb4

    if ($LASTEXITCODE -ne 0) {
        Write-Error "Falha ao executar $script"
    }
}

Write-Host ""
Write-Host "Verificando tabelas criadas:" -ForegroundColor Cyan
& $mysqlExe -u root "-p$password" -e "USE restaurante; SHOW TABLES;"

Write-Host ""
Write-Host "Setup concluido com sucesso!" -ForegroundColor Green
Write-Host "Banco 'restaurante' pronto para uso." -ForegroundColor Green
