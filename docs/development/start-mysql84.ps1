# Start the local MySQL 8.4 instance used by this project (port 3307).
# MySQL 5.7 on 3306 is left untouched.
$ErrorActionPreference = "Stop"
$ini = Join-Path $env:LOCALAPPDATA "Programs\mysql-8.4-extract\my.ini"
$mysqld = Join-Path $env:LOCALAPPDATA "Programs\mysql-8.4-extract\PFiles64\MySQL\MySQL Server 8.4\bin\mysqld.exe"
$mysql = Join-Path $env:LOCALAPPDATA "Programs\mysql-8.4-extract\PFiles64\MySQL\MySQL Server 8.4\bin\mysql.exe"

if (-not (Test-Path -LiteralPath $ini)) {
    throw "MySQL option file not found: $ini"
}
if (-not (Test-Path -LiteralPath $mysqld)) {
    throw "MySQL server not found: $mysqld"
}
if (-not (Test-Path -LiteralPath $mysql)) {
    throw "MySQL client not found: $mysql"
}

& $mysql --defaults-file=$ini -u root -e "SELECT 1;" 2>$null | Out-Null
if ($LASTEXITCODE -eq 0) {
    Write-Output "MySQL 8.4.9 already listening on 127.0.0.1:3307"
    exit 0
}

Start-Process -FilePath $mysqld -ArgumentList "--defaults-file=`"$ini`"" -WindowStyle Hidden
$deadline = (Get-Date).AddSeconds(15)

do {
    Start-Sleep -Milliseconds 500
    & $mysql --defaults-file=$ini -u root -e "SELECT 1;" 2>$null | Out-Null

    if ($LASTEXITCODE -eq 0) {
        $version = & $mysql --defaults-file=$ini -u root -N -s -e "SELECT VERSION();"
        Write-Output "MySQL $version listening on 127.0.0.1:3307"
        exit 0
    }
} while ((Get-Date) -lt $deadline)

throw "MySQL did not start on 127.0.0.1:3307"
