<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Database connection check
Route::get('/db', function() {
    try {
        DB::connection()->getPdo();
        return Response("Connection successful", 200)
        ->header('Content-Type', 'text/plain');
    } catch (\Exception $e) {
        return Response("Error connecting to database.", 500)
        ->header('Content-Type', 'text/plain');
    }
});

// plain web check NGINX & PHP working
Route::get('/', function() {
    return Response("Connection successful", 200)
    ->header('Content-Type', 'text/plain');
});
