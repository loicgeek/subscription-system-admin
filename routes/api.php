<?php

use Illuminate\Support\Facades\Route;
use NtechServices\SubscriptionSystemAdmin\Http\Controllers\Api\PlanController;
use NtechServices\SubscriptionSystemAdmin\Http\Controllers\Api\FeatureController;

Route::prefix('subscription')->group(function () {
    // Public routes
    Route::get('/plans', [PlanController::class, 'index']);
    Route::get('/plans/{plan}', [PlanController::class, 'show']);
    Route::get('/plans/{plan}/features', [PlanController::class, 'features']);

    
});