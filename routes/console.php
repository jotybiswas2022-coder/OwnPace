<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Schedule — automated communication
|--------------------------------------------------------------------------
|
| These feed the queued notification classes (payment due / overdue). Run
| `php artisan schedule:run` every minute (cron) — or `schedule:work` in dev.
| The queue worker must also be running (php artisan queue:work).
*/

Schedule::command('installments:send-reminders --days=3')
    ->dailyAt('09:00')
    ->withoutOverlapping();

Schedule::command('installments:mark-overdue')
    ->dailyAt('00:30')
    ->withoutOverlapping();
