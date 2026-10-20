# Start Mailpit bound to localhost only (SMTP 1025, UI 8025).
# Real SMTP is never used. MySQL and Filament are out of scope.
$ErrorActionPreference = "Stop"

function Test-LocalPort([int]$Port) {
    return [bool](Get-NetTCPConnection -LocalAddress 127.0.0.1 -LocalPort $Port -State Listen -ErrorAction SilentlyContinue)
}

$mailpit = Get-Command mailpit -ErrorAction SilentlyContinue
if (-not $mailpit) {
    $env:Path = [Environment]::GetEnvironmentVariable("Path", "Machine") + ";" + [Environment]::GetEnvironmentVariable("Path", "User")
    $mailpit = Get-Command mailpit -ErrorAction SilentlyContinue
}
if (-not $mailpit) {
    throw "mailpit is not on PATH. Install with: winget install --id axllent.mailpit --exact"
}

if ((Test-LocalPort 1025) -and (Test-LocalPort 8025)) {
    Write-Output "Mailpit already listening on 127.0.0.1:1025 (SMTP) and 127.0.0.1:8025 (UI)"
    exit 0
}

Start-Process -FilePath $mailpit.Source -ArgumentList "--smtp","127.0.0.1:1025","--listen","127.0.0.1:8025" -WindowStyle Hidden
$deadline = (Get-Date).AddSeconds(10)
do {
    Start-Sleep -Milliseconds 400
    if ((Test-LocalPort 1025) -and (Test-LocalPort 8025)) {
        Write-Output "Mailpit 1.31.6 listening on 127.0.0.1:1025 (SMTP) and 127.0.0.1:8025 (UI)"
        exit 0
    }
} while ((Get-Date) -lt $deadline)

throw "Mailpit did not start on 127.0.0.1:1025 / 8025"
