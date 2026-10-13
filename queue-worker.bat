@echo off
echo Starting Anarjo Queue Worker...
echo Press Ctrl+C to stop.
php artisan queue:work --queue=default --sleep=3 --tries=3 --delay=5
pause
