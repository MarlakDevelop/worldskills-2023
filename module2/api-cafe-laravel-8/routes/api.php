<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkShiftController;
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
Route::pattern('id', '[0-9]+');
Route::pattern('order_id', '[0-9]+');
Route::pattern('work_shift_id', '[0-9]+');
Route::pattern('position_id', '[0-9]+');

Route::post('login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->group(function() {
    Route::get('logout', [UserController::class, 'logout']);
    Route::middleware("ability:admin")->get('user', [UserController::class, 'read']);
    Route::middleware("ability:admin")->post('user', [UserController::class, 'create']);
    Route::middleware("ability:admin")->post('work-shift', [WorkShiftController::class, 'create']);
    Route::middleware("ability:admin")->get('work-shift/{work_shift}/open', [WorkShiftController::class, 'open']);
    Route::middleware("ability:admin")->get('work-shift/{work_shift}/close', [WorkShiftController::class, 'close']);
    Route::middleware("ability:admin")->post('work-shift/{work_shift}/user', [WorkShiftController::class, 'addUser']);
    Route::middleware("ability:admin,waiter")->get('work-shift/{work_shift}/order', [OrderController::class, 'readByWorkShift']);
    Route::middleware("ability:cook")->get('order/taken', [OrderController::class, 'readTaken']);
    Route::middleware("ability:cook,waiter")->patch('order/{order}/change-status', [OrderController::class, 'changeStatus']);
    Route::middleware("ability:waiter")->post('order', [OrderController::class, 'create']);
    Route::middleware("ability:waiter")->get('order/{order}', [OrderController::class, 'readOne']);
    Route::middleware("ability:waiter")->post('order/{order}/position', [OrderController::class, 'addPosition']);
    Route::middleware("ability:waiter")->delete('order/{order}/position/{position}', [OrderController::class, 'removePosition']);
});
