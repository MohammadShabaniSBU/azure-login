<?php

use App\Http\Middleware\Azure;
use Illuminate\Support\Facades\Route;

Route::get('/login/azure', [Azure::class, 'azure']);
Route::get('/azure/callback', [Azure::class, 'azurecallback']);
Route::get('/logout/azure', [Azure::class, 'azurelogout']);
