<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('debt-payments:generate')
    ->monthlyOn(1, '10:00')
    ->withoutOverlapping()
    ->onOneServer();

Schedule::command('debt-payments:sync-statuses')
    ->everyThirtyMinutes()
    ->withoutOverlapping()
    ->onOneServer();
