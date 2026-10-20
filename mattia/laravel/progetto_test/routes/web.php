<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibroController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/libri', [
    LibroController::class, 'index'
]);
Route::get('/libri/create', [
    LibroController::class, 'create'
])->name("libri.create");
Route::post('/libri/create', [
    LibroController::class, 'create'
])->name("libri.create.post");
Route::get('/libri/view', [
    LibroController::class, 'view'
])->name("libri.view");
