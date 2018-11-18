@echo off

set filename=temp.sql
set /p filename="Enter a filename [%filename%]: " 

php bin/console doctrine:schema:update --dump-sql > %filename%
pause