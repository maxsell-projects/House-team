<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// IMPORTAÇÃO: Roda a cada hora, não deixa rodar 2 ao mesmo tempo e salva log
Schedule::command('idealista:sync')
    ->hourly()
    ->withoutOverlapping() // Se o anterior travou, não inicia outro em cima
    ->appendOutputTo(storage_path('logs/idealista_sync.log')); // Cria um log só pra isso

// EXPORTAÇÃO: Roda a cada 15 min
Schedule::command('idealista:export-pending')
    ->everyFifteenMinutes()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/idealista_export.log'));