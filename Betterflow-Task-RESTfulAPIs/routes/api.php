<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// API routes for Task resource
Route::apiResource('tasks', TaskController::class);
