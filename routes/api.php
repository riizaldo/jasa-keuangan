<?php

use App\Http\Controllers\Api\LoanApplicationController;
use App\Http\Controllers\Api\InstallmentController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/test', function () {
    return response()->json(['message' => 'API berjalan normal 🚀']);
    Route::get('/loans', [LoanApplicationController::class, 'index']);
    Route::get('/loans/{id}', [LoanApplicationController::class, 'show']);
    Route::post('/loans', [LoanApplicationController::class, 'store']);
    Route::post('/loans/{id}/approve', [LoanApplicationController::class, 'approve']);

    Route::post('/installments/{id}/pay', [InstallmentController::class, 'pay']);

    Route::get('/payments', [PaymentController::class, 'index']);
});
