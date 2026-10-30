<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response()->json([
    'application' => 'API Gestion des notes',
    'statut' => 'ok',
]));
