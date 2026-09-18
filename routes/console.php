<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('notifications:remind', function () {
    app(\App\Services\RequestNotificationService::class)->reminders();
    $this->info('Request reminders checked.');
})->purpose('Notify Dial-A of upcoming and past-due request stages');

Schedule::command('notifications:remind')->everyMinute()->withoutOverlapping();
