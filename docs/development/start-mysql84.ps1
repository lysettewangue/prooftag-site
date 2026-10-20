# Start the local MySQL 8.4 instance used by this project (port 3307).
# MySQL 5.7 on 3306 is left untouched.
$ErrorActionPreference = "Stop"
$ini = Join-Path $env:LOCALAPPDATA "Programs\mysql-8.4-extract\my.ini"
$mysqld = Join-Path $env:LOCALAPPDATA "Programs\mysql-8.4-extract\PFiles64\MySQL\MySQL Server 8.4\bin\mysqld.exe"
$mysql = Join-Path $env:LOCALAPPDATA "Programs\mysql-8.4-extract\PFiles64\MySQL\MySQL Server 8.4\bin\mysql.exe"
try {
    & $mysql --defaults-file=$ini -u root -e "SELECT 1;" | Out-Null
    Write-Output "MySQL 8.4.9 already listening on 127.0.0.1:3307"
    exit 0
} catch {
    Start-Process -FilePath $mysqld -ArgumentList "--defaults-file=`"$ini`"" -WindowStyle Hidden
    Start-Sleep -Seconds 5
    & $mysql --defaults-file=$ini -u root -e "SELECT VERSION();"
}
