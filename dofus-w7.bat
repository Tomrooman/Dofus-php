@echo off
:Begin
echo.
set /P command= "Commande --> "%*
clear
php "C:\Users\Tom\Desktop\Tom Dofus\Dofus.php" %command%
goto :Begin