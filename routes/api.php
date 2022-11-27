<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\ConcertController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ? PUBLIC ROUTES
Route::post('artist/login', [ArtistController::class, 'login']);
Route::post('artist/register', [ArtistController::class, 'register']);

Route::post('user/login', [UserController::class, 'login']);
Route::post('user/register', [UserController::class, 'register']);

Route::get('tickets', [TicketsController::class, 'index']);
Route::get('tickets/{id}', [TicketsController::class, 'show']);

Route::get('concerts', [ConcertController::class, 'index']);
Route::get('concerts/{id}', [ConcertController::class, 'show']);

// ? PROTECTED ROUTES
Route::middleware('auth:sanctum')->group(function(){
    Route::resource('tickets', TicketsController::class)->except([
        'index', 'show'
    ]);
    Route::resource('concerts', ConcertController::class)->except([
        'index', 'show'
    ]);
    Route::resource('transactions', TransactionController::class);
    Route::post('artist/logout', [ArtistController::class, 'logout']);
    Route::post('user/logout', [UserController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
