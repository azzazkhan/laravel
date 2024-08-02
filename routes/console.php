<?php

use App\Console\Commands\Ping;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command(Ping::class, ['--message' => 'Scheduled ping ' . now()->format('d-m-Y G:i:s (e)')])
    ->everyThirtySeconds();
