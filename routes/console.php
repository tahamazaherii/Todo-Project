<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();



// این روت برای چک کردن هر ساعت برای چک کردن تسک هایی که در موعد که قرار دادن انجام نشده 
Schedule::command('todos:check-due')->hourly();
