<?php
use Illuminate\Support\Facades\Schedule;

// Cron: * * * * * cd /path/to/laravel && php artisan schedule:run >> /dev/null 2>&1
Schedule::command('rooms:cleanup --force')->dailyAt('06:00');
