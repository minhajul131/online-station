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
        //update vendor Details
        Route::match(['get','post'],'/update-vendor-details/{slug}',[App\Http\Controllers\Admin\AdminController::class,'updateVendorDetails']);

        //view admin vendor subAdmins
        Route::get('/admins/{trpe?}',[App\Http\Controllers\Admin\AdminController::class,'admins']);

        Route::get('/view-vendor-details/{id}',[App\Http\Controllers\Admin\AdminController::class,'viewVendorDetails']);

        Route::post('/update-admin-status',[App\Http\Controllers\Admin\AdminController::class,'updateAdminStatus']);
        
        //logout
        Route::get('/logout',[App\Http\Controllers\Admin\AdminController::class,'logout']);

        //sections
        Route::get('/sections',[App\Http\Controllers\Admin\SectionController::class,'sections']);
        Route::post('/update-section-status',[App\Http\Controllers\Admin\SectionController::class,'updateSectionStatus']);        
        Route::get('/delete-section/{id}',[App\Http\Controllers\Admin\SectionController::class,'deleteSection']);       
        Route::match(['get','post'],'/add-edit-section/{id?}',[App\Http\Controllers\Admin\SectionController::class,'addEditSection']);       
        
        // categories
        Route::get('/categories',[App\Http\Controllers\Admin\CategoryController::class,'categories']);
        Route::post('/update-category-status',[App\Http\Controllers\Admin\CategoryController::class,'updateCategoryStatus']);

        Route::match(['get','post'],'/add-edit-category/{id?}',[App\Http\Controllers\Admin\CategoryController::class,'addEditCategory']);
        Route::get('/append-categories-level',[App\Http\Controllers\Admin\CategoryController::class,'appendCategoryLevel']);
        Route::get('/delete-category/{id}',[App\Http\Controllers\Admin\CategoryController::class,'deleteCategory']);
        Route::get('/delete-category-image/{id}',[App\Http\Controllers\Admin\CategoryController::class,'deleteCategoryImage']);

        //brands
        Route::get('/brands',[App\Http\Controllers\Admin\BrandController::class,'brands']);
        Route::post('/update-brand-status',[App\Http\Controllers\Admin\BrandController::class,'updateBrandStatus']);
        Route::get('/delete-brand/{id}',[App\Http\Controllers\Admin\BrandController::class,'deleteBrand']);
        Route::match(['get','post'],'/add-edit-brand/{id?}',[App\Http\Controllers\Admin\BrandController::class,'addEditBrand']);
    });
});