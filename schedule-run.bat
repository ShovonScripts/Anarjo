@echo off
REM This batch file is meant to be called by Windows Task Scheduler every minute.
REM Setup: Create a Task Scheduler task that runs this .bat every minute.
REM   Trigger:  Daily, repeat every 1 minute, indefinitely
REM   Action:   Start a program -> path to this .bat
REM   Settings: "Allow task to run on demand", "Run whether user is logged on or not"
cd /d "%~dp0"
php artisan schedule:run
