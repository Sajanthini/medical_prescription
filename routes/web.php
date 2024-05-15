<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PrescriptionController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});
Auth::routes();

Route::middleware(['auth'])->group(function() {
    Route::get('/home',[HomeController::class,'index']);

    // Routes for prescriptions
    Route::get('/create/prescription', [PrescriptionController::class, 'createPrescriptions'])->name('prescriptions.index'); 
    Route::post('/store/prescription', [PrescriptionController::class, 'storePrescriptions'])->name('prescriptions.store');
    Route::get('/prescriptions', [PrescriptionController::class, 'show'])->name('prescriptions.show');

    // Routes for quotations
    Route::get('/create/{id}/quotation', [QuotationController::class, 'createQuotation']); 
    Route::post('/store/{id}/quotation', [QuotationController::class, 'storeQuotation'])->name('quotation.store'); 
    Route::get('/quotations', [QuotationController::class, 'showQuotation'])->name('quotations.show');
    Route::get('/quotation/items/{id}', [QuotationController::class, 'Item_Find']); 
    Route::post('/quotation/status', [QuotationController::class, 'status_update']);

    // Route for notifications
    Route::post('/notifications/mark-read', [NotificationController::class, 'markRead'])->name('notifications.markRead'); 

});
