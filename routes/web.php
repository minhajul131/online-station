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
        Route::get('/dashboard',[App\Http\Controllers\Admin\AdminController::class,'dashboard']);

        Route::get('/logout',[App\Http\Controllers\Admin\AdminController::class,'logout']);
    });
});