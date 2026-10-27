<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GraficheController;


// Mostra la pagina
Route::get('/progetti', [GraficheController::class, 'index'])
    ->name('progetti.index');

// Salva i dati del form
Route::post('/progetti', [GraficheController::class, 'store'])
    ->name('progetti.store');
    
// Mostra l’elenco e il form di inserimento
Route::post('/progetti', [GraficheController::class, 'store'])
    ->name('progetti.store');

// Salva un nuovo progetto
Route::post('/progetti', [GraficheController::class, 'store'])
    ->name('progetti.store');

// Mostra il form di modifica
Route::get('/progetti/{id}/edit', [GraficheController::class, 'edit'])
    ->name('progetti.edit');

// Salva le modifiche
Route::post('/progetti/{id}/update', [GraficheController::class, 'update'])
    ->name('progetti.update');

// Elimina il progetto
Route::post('/progetti/{id}/delete', [GraficheController::class, 'destroy'])
    ->name('progetti.destroy');
