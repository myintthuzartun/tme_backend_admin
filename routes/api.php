<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorLevelController;
use App\Http\Controllers\AddBusinessTypeController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminImageController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\CurrencyController;

// API Routes

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/user', [UserController::class, 'index']);
Route::get('/user/{id}', [UserController::class, 'show']);
Route::put('user/{id}', [UserController::class, 'update']);  // Edit user
// Delete user
Route::delete('{user/id}', [UserController::class, 'delete']);  // Delete user

// Vendor Level routes
Route::post('/vendor-levels', [VendorLevelController::class, 'store']); // POST route for creating vendor levels
Route::get('/vendor-levels', [VendorLevelController::class, 'index']);  // GET route for fetching all vendor levels
Route::put('/vendor-levels/{id}', [VendorLevelController::class, 'update']); // PUT route for updating a vendor level
Route::delete('/vendor-levels/{id}', [VendorLevelController::class, 'destroy']); // DELETE route for deleting a vendor level

// Business Name routes
Route::post('/business_type', [AddBusinessTypeController::class, 'store']);
Route::get('/business_type', [AddBusinessTypeController::class, 'index']);  // GET route for fetching all business types
Route::put('/business_type/{id}', [AddBusinessTypeController::class, 'update']);  // GET route for fetching all business types
Route::delete('/business_type/{id}', [AddBusinessTypeController::class, 'destroy']);  // GET route for fetching all business types


// AdminProfile Name routes
Route::get('admin-profiles', [AdminProfileController::class, 'index']);
Route::post('admin-profiles', [AdminProfileController::class, 'store']);
Route::get('admin-profiles/{id}', [AdminProfileController::class, 'show']);
Route::put('admin-profiles/{id}', [AdminProfileController::class, 'update']);
Route::delete('admin-profiles/{id}', [AdminProfileController::class, 'destroy']);

Route::get('admin-image', [AdminImageController::class, 'index']);
Route::post('admin-image', [AdminImageController::class, 'store']);
Route::get('admin-image/{id}', [AdminImageController::class, 'show']);
Route::post('/admin-image/{id}/update', [AdminImageController::class, 'update']);  //Main for update with post method
Route::delete('admin-image/{id}', [AdminImageController::class, 'destroy']);

Route::get('/faq', [FaqController::class, 'index']);
Route::post('/faq', [FaqController::class, 'store']);
Route::get('/faq/{id}', [FaqController::class, 'show']);
Route::put('/faq/{id}', [FaqController::class, 'update']);
Route::delete('/faq/{id}', [FaqController::class, 'destroy']);


Route::get('/currency', [CurrencyController::class, 'index']);
Route::post('/currency', [CurrencyController::class, 'store']);
Route::get('/currency/{id}', [CurrencyController::class, 'show']);
Route::put('/currency/{id}', [CurrencyController::class, 'update']);
Route::delete('/currency/{id}', [CurrencyController::class, 'destroy']);