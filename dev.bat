@echo off

set cmd=%1
set allArgs=%*
for /f "tokens=1,* delims= " %%a in ("%*") do set allArgsExceptFirst=%%b
set secondArg=%2

REM If no command provided, list commands
if "%cmd%" == "" (
    set valid=true
    echo The following commands are available:
    echo   .\dev up
    echo   .\dev down
    echo   .\dev craft [args]
    echo   .\dev composer [args]
    echo   .\dev login [args]
)

if "%cmd%" == "up" (
    set valid=true
    docker-compose -f docker-compose.yml -p craft-static up -d
    docker exec -it --user root --workdir /app php-craft-static bash -c "cd /app && composer install"
)

if "%cmd%" == "down" (
    set valid=true
    docker-compose -f docker-compose.yml -p craft-static down
)

if "%cmd%" == "craft" (
    set valid=true
    docker exec -it --user root --workdir /app php-craft-static bash -c "php %allArgs%"
)

if "%cmd%" == "composer" (
    set valid=true
    docker exec -it --user root --workdir /app php-craft-static bash -c "%allArgs%"
)

if "%cmd%" == "login" (
    set valid=true
    docker exec -it --user root %secondArg%-craft-static bash
)

if not "%valid%" == "true" (
    echo Specified command not found
    exit /b 1
)

exit /b 0
