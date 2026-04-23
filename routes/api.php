<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SlotitemController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ParentScanController;
use App\Models\appointment;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::post('verfy_otp', [RegisterController::class, 'verify']);
Route::post('forget', [RegisterController::class, 'forget']);
Route::post('update_password', [RegisterController::class, 'updatePassword']);

Route::middleware('auth:sanctum')->group(function () {


    Route::prefix('user')->group(function () {
        Route::post('list', [RegisterController::class, 'list']);
        Route::post('detail', [RegisterController::class, 'detail']);
        Route::post('update', [RegisterController::class, 'update']);
        Route::post('logout', [RegisterController::class, 'logout']);
        Route::post('delete', [RegisterController::class, 'destroy']);
    });



    Route::prefix('scan_category')->group(function () {

        Route::post('list', [ParentScanController::class, 'list']);
        Route::post('add', [ParentScanController::class, 'store']);
        Route::post('update', [ParentScanController::class, 'update']);
        Route::post('delete', [ParentScanController::class, 'destroy']);
    });

    Route::prefix('scan')->group(function () {

        Route::post('list', [ScanController::class, 'list']);
        Route::post('add', [ScanController::class, 'store']);
        Route::post('update', [ScanController::class, 'update']);
        Route::post('delete', [ScanController::class, 'destroy']);
    });






    Route::prefix('slot')->group(function () {

        Route::post('list', [SlotitemController::class, 'list']);
        Route::post('add', [SlotitemController::class, 'store']);
        Route::post('update', [SlotitemController::class, 'update']);
        Route::post('delete', [SlotitemController::class, 'destroy']);
        Route::post('select', [AppointmentController::class, 'slot']);
    });
    Route::prefix('appointment')->group(function () {

        Route::post('list', [AppointmentController::class, 'index']);
        Route::post('add', [AppointmentController::class, 'store']);
        Route::post('update', [AppointmentController::class, 'update']);
        Route::post('delete', [AppointmentController::class, 'destroy']);
    });
});
