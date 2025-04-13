<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// API routes for Task resource
Route::middleware('apikey')->group(function () {
    Route::apiResource('tasks', TaskController::class);
});
