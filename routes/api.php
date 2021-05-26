<?php

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

Route::middleware('authapi')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('auth/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::group(['middleware' => 'authapi'], function () {
    Route::get('auth/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

    // board
    Route::post('board', [\App\Http\Controllers\BoardController::class, 'store']);
    Route::put('board/{board}', [\App\Http\Controllers\BoardController::class, 'update']);
    Route::delete('board/{board}', [\App\Http\Controllers\BoardController::class, 'destroy']);
    Route::get('board', [\App\Http\Controllers\BoardController::class, 'index']);
    Route::get('board/{board}', [\App\Http\Controllers\BoardController::class, 'show']);
    Route::post('board/{board}/member', [\App\Http\Controllers\BoardController::class, 'addMember']);
    Route::delete('board/{board}/member/{user}', [\App\Http\Controllers\BoardController::class, 'removeMember']);

    // list
    Route::post('board/{board}/list', [\App\Http\Controllers\BoardListController::class, 'store']);
    Route::put('board/{board}/list/{boardList}', [\App\Http\Controllers\BoardListController::class, 'update']);
    Route::delete('board/{board}/list/{boardList}', [\App\Http\Controllers\BoardListController::class, 'destroy']);
    Route::post('board/{board}/list/{boardList}/right', [\App\Http\Controllers\BoardListController::class, 'moveRight']);
    Route::post('board/{board}/list/{boardList}/left', [\App\Http\Controllers\BoardListController::class, 'moveLeft']);

    // card
    Route::post('board/{board}/list/{boardList}/card', [\App\Http\Controllers\CardController::class, 'store']);
    Route::put('board/{board}/list/{boardList}/card/{card}', [\App\Http\Controllers\CardController::class, 'update']);
    Route::delete('board/{board}/list/{boardList}/card/{card}', [\App\Http\Controllers\CardController::class, 'destroy']);
    Route::post('card/{card}/up', [\App\Http\Controllers\CardController::class, 'moveUp']);
    Route::post('card/{card}/down', [\App\Http\Controllers\CardController::class, 'moveDown']);
    Route::post('card/{card}/move/{boardList}', [\App\Http\Controllers\CardController::class, 'moveList']);
});
