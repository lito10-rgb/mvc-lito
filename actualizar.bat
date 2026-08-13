@echo off
echo ========================================
echo ACTUALIZACION AUTOMATICA MVC-LITO
echo ========================================
echo.

cd /d C:\xampp\htdocs\mvc-lito

echo [1/4] Verificando estado de git...
git status
echo.

echo [2/4] Agregando cambios...
git add .
echo.

echo [3/4] Haciendo commit...
set /p mensaje="Mensaje del commit: "
git commit -m "%mensaje%"
echo.

echo [4/4] Subiendo a GitHub...
git push origin master
echo.

echo ========================================
echo ACTUALIZACION COMPLETADA
echo ========================================
echo.
echo El deploy automatico se ejecutara en GitHub Actions
echo Sitios actualizados:
echo - cafe-peruano.com
echo - equiposymaquinas.com
echo.
pause