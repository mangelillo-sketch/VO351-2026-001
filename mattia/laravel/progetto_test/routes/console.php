<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Pulizia log giornaliera a mezzanotte
Schedule::command('sistema:pulizia-log --giorni=15')
        ->dailyAt('00:00');

 
// Generazione report ogni ora
Schedule::command('report:genera --tipo=vendite')
        ->hourly();

// Backup settimanale la domenica alle 02:00
Schedule::command('backup:esegui')
        ->weeklyOn(0, '02:00');
