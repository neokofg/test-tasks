<?php

use App\Http\Controllers\Product\IndexProductController;
use Illuminate\Support\Facades\Route;

// ATTENTION: я бы добавил v1 префикс, но в задании указано именно api/prices.
Route::get('/prices', IndexProductController::class);
