<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

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

Route::post('register',[AuthController::class, 'register']);
Route::post('login',[AuthController::class, 'login']);


/**
 * Admin Route
 */
Route::prefix('admin')->middleware('auth:sanctum'/*, CheckPermission::class . ':view-dashboard'*/)->group(function(){
    Route::apiResource('brands',BrandController::class);
    Route::get('brands/{brand}/products',[BrandController::class,'getProducts']);
    Route::apiResource('categories',CategoryController::class);
    Route::get('categories/{category}/parent',[CategoryController::class,'parent']);
    Route::get('categories/{category}/children',[CategoryController::class,'children']);
    Route::get('categories/{category}/products',[CategoryController::class,'getProducts']);
    Route::apiResource('products',ProductController::class);
    Route::apiResource('products.gallery',GalleryController::class);
    Route::apiResource('roles',RoleController::class);
    Route::post('logout',[AuthController::class, 'logout']);

});

