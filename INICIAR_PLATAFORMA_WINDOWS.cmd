@echo off
setlocal

set "PROJECT_DIR=%~dp0"
cd /d "%PROJECT_DIR%"

echo ============================================
echo   Plataforma de cursos - inici de serveis
echo ============================================
echo.

if exist "public\hot" (
    del /f /q "public\hot" >nul 2>nul
)

if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env" >nul
    )
)

if not exist "vendor" (
    echo Falta la carpeta vendor. S'executara composer install...
    call composer install
    if errorlevel 1 goto :error
)

if not exist "public\build\manifest.json" (
    if not exist "node_modules" (
        echo Falta la carpeta node_modules. S'executara npm install...
        call npm install
        if errorlevel 1 goto :error
    )

    echo Generant assets...
    call npm run build
    if errorlevel 1 goto :error
)

echo Preparant base de dades...
call php artisan key:generate --force >nul 2>nul
call php artisan migrate:fresh --seed --force
if errorlevel 1 goto :error

echo.
echo Iniciant servidor Laravel a http://127.0.0.1:8000
start "Plataforma de cursos" cmd /k "cd /d ""%PROJECT_DIR%"" && php artisan serve --host=127.0.0.1 --port=8000"

timeout /t 3 >nul
start "" "http://127.0.0.1:8000"

echo.
echo La plataforma s'ha iniciat en una finestra nova.
echo Si es la primera vegada, espera uns segons i refresca el navegador.
echo.
pause
exit /b 0

:error
echo.
echo No s'ha pogut iniciar la plataforma.
echo Revisa que PHP, Composer i Node.js estiguin disponibles a Windows.
echo.
pause
exit /b 1
