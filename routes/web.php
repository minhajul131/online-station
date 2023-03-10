<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

// Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')-group(function(){
//     Route::match(['get','post'],'login','AdminController@login');
//     //Admin dashboard route
//     Route::get('dashboard','AdminController@dashboard');
// });

Route::group(['prefix'=>'admin'],function(){

    Route::match(['get','post'],'/login',[App\Http\Controllers\Admin\AdminController::class,'login']);
    Route::group(['middleware'=>['admin']],function(){
        //dashboard
        Route::get('/dashboard',[App\Http\Controllers\Admin\AdminController::class,'dashboard']);
        //password update
        Route::match(['get','post'],'/update-admin-password',[App\Http\Controllers\Admin\AdminController::class,'updateAdminPassword']);
        //check current password
        Route::post('/check-admin-password',[App\Http\Controllers\Admin\AdminController::class,'checkAdminPassword']);
        //update details
        Route::match(['get','post'],'/update-admin-details',[App\Http\Controllers\Admin\AdminController::class,'updateAdminDetails']);
        //logout
        Route::get('/logout',[App\Http\Controllers\Admin\AdminController::class,'logout']);
    });
});