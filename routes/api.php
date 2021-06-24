<?php

use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\productController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
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

//set Routes api_link/products
Route::get('products',[productController::class, 'all']);
Route::get('categories',[ProductCategoryController::class, 'all']);

Route::post('register',[UserController::class, 'register']);
Route::post('login',[UserController::class, 'login']);

//Routing User for user that have login
//sanctum untuk mengecek user sudah login atau belum
Route::middleware('auth:sanctum')->group(function(){
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user',[UserController::class, 'updateProfile']);
    Route::post('logout',[UserController::class, 'logout']);

    Route::get('transaction',[TransactionController::class,'all']);
    Route::post('checkout',[TransactionController::class, 'checkout']);
});