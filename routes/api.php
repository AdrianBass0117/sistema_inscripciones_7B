<?php

use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

Route::get('/api/constancias/verificar/{codigo}', [ReporteController::class, 'apiVerificarConstancia'])
    ->name('api.constancias.verificar');
