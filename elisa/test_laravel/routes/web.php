<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GraficheController;

Route::resource('progetti',GraficheController::class);

Route::get('/', function () {
    return view('welcome');
});
