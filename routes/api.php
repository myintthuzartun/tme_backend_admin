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

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessTypeController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::get('/business', [BusinessTypeController::class, 'business']);

Route::middleware('auth:api')->get('/me', [AuthController::class, 'me']);
Route::middleware('jwt.auth')->get('/me', [AuthController::class, 'me']);

//Admin routes
use App\Http\Controllers\VendorLevelController;
Route::post('/admin_register', [AuthController::class, 'admin_register']);
Route::post('/admin_login', [AuthController::class, 'admin_login']);
// Vendor Level routes
Route::post('/vendor-levels', [VendorLevelController::class, 'store']); // POST route for creating vendor levels
Route::get('/vendor-levels', [VendorLevelController::class, 'index']);  // GET route for fetching all vendor levels
Route::put('/vendor-levels/{id}', [VendorLevelController::class, 'update']); // PUT route for updating a vendor level
Route::delete('/vendor-levels/{id}', [VendorLevelController::class, 'destroy']); // DELETE route for deleting a vendor level
// Business Name routes
Route::post('/business', [BusinessTypeController::class, 'store']);
Route::get('/business', [BusinessTypeController::class, 'index']);
Route::put('/business/{id}', [BusinessTypeController::class, 'update']);  // GET route for fetching all business types
Route::delete('/business/{id}', [BusinessTypeController::class, 'destroy']);  // GET route for fetching all business types
//Category routes
use App\Http\Controllers\CategoryController;
Route::post('/category', [CategoryController::class, 'add']);

Route::get('/category', [CategoryController::class, 'list']);
Route::get('/category/{id}', [CategoryController::class, 'show']);

Route::put('/category/{id}', [CategoryController::class, 'update']);

Route::delete('/category/{id}', [CategoryController::class, 'delete']);
