<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
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

        // products
        Route::get('/products',[App\Http\Controllers\Admin\ProductsController::class,'products']);
        Route::post('/update-product-status',[App\Http\Controllers\Admin\ProductsController::class,'updateProductStatus']);
        Route::get('/delete-product/{id}',[App\Http\Controllers\Admin\ProductsController::class,'deleteProduct']);
        Route::match(['get','post'],'/add-edit-product/{id?}',[App\Http\Controllers\Admin\ProductsController::class,'addEditProduct']);
        Route::get('/delete-product-image/{id}',[App\Http\Controllers\Admin\ProductsController::class,'deleteProductImage']);
        Route::get('/delete-product-video/{id}',[App\Http\Controllers\Admin\ProductsController::class,'deleteProductVideo']);

        //attributes
        Route::match(['get','post'],'/add-edit-attributes/{id}',[App\Http\Controllers\Admin\ProductsController::class,'addAttributes']);
        Route::post('/update-attribute-status',[App\Http\Controllers\Admin\ProductsController::class,'updateAttributeStatus']);
        Route::get('/delete-attribute/{id}',[App\Http\Controllers\Admin\ProductsController::class,'deleteAttribute']);
        Route::match(['get','post'],'/edit-attributes/{id}',[App\Http\Controllers\Admin\ProductsController::class,'editAttributes']);

        //images
        Route::match(['get','post'],'/add-images/{id}',[App\Http\Controllers\Admin\ProductsController::class,'addImages']);
        Route::post('/update-image-status',[App\Http\Controllers\Admin\ProductsController::class,'updateImageStatus']);
        Route::get('/delete-image/{id}',[App\Http\Controllers\Admin\ProductsController::class,'deleteImage']);

        //banner
        Route::get('/banners',[App\Http\Controllers\Admin\BannersController::class,'banners']);
        Route::post('/update-banner-status',[App\Http\Controllers\Admin\BannersController::class,'updateBannerStatus']);
        Route::get('/delete-banner/{id}',[App\Http\Controllers\Admin\BannersController::class,'deleteBanner']);
        Route::match(['get','post'],'/add-edit-banner/{id?}',[App\Http\Controllers\Admin\BannersController::class,'addEditBanner']);
        
        // user
        Route::get('/users',[App\Http\Controllers\Admin\UserController::class,'users']);
        Route::post('/update-user-status',[App\Http\Controllers\Admin\UserController::class,'updateUserStatus']);
        
        // coupon
        Route::get('/coupons',[App\Http\Controllers\Admin\CouponsController::class,'coupons']);
        Route::post('/update-coupon-status',[App\Http\Controllers\Admin\CouponsController::class,'updateCouponStatus']);
        Route::get('/delete-coupon/{id}',[App\Http\Controllers\Admin\CouponsController::class,'deleteCoupon']);
        Route::match(['get','post'],'/add-edit-coupon/{id?}',[App\Http\Controllers\Admin\CouponsController::class,'addEditCoupon']);
    });
});

Route::namespace('App\Http\Controllers\Front')->group(function(){
    Route::get('/','IndexController@index');

    //listing categories route
    $catUrls = Category::select('url')->where('status',1)->get()->pluck('url')->toArray();
    foreach ($catUrls as $key => $url) {
        Route::match(['get','post'],'/'.$url,'ProductsController@listing');
    }

    // product detail 
    Route::get('product/{id}','ProductsController@detail');

    // vendor products
    Route::get('products/{vendorid}','ProductsController@vendorListing');
    
    // product attribute size 
    Route::post('get-product-price','ProductsController@getProductPrice');

    //vendor login-register
    Route::get('vendor/login-register','VendorController@loginRegister');
    Route::post('vendor/register','VendorController@vendorRegister');

    //confirm vendor account
    Route::get('vendor/confirm/{code}','VendorController@confirmVendor');

    // add to cart
    Route::post('cart/add','ProductsController@cartAdd');
    
    // cart
    Route::get('cart','ProductsController@cart');

    // update cart item quantity
    Route::post('cart/update','ProductsController@cartUpdate');
    
    // delete cart item
    Route::post('cart/delete','ProductsController@cartDelete');

    //customer/user login-register
    Route::get('user/login-register',['as'=>'login','uses'=>'UserController@loginRegister']);
    //user register
    Route::post('user/register','UserController@userRegister');
    
    Route::group(['middleware'=>['auth']],function(){
        //user account
        Route::match(['get','post'],'user/account','UserController@userAccount');
        // user update pass
        Route::post('user/update-password','UserController@userUpdatePassword');
        
        // user update pass
        Route::match(['get','post'],'checkout','ProductsController@checkout');

        // get delivery address
        Route::post('get-delivery-address','AddressController@getDeliveryAddress');
        // save delivery address
        Route::post('save-delivery-address','AddressController@saveDeliveryAddress');
        // save delivery address
        Route::post('remove-delivery-address','AddressController@removeDeliveryAddress');

        // apply coupon
        Route::post('apply-coupon','ProductsController@applyCoupon');
    });

    //user login
    Route::post('user/login','UserController@userLogin');
    // confirm user account
    Route::get('/user/confirm/{code}','UserController@confirmAccount');
    // forgot password
    Route::match(['get','post'],'user/forgot-password','UserController@forgotPassword');
    //user logout
    Route::get('user/logout','UserController@userLogout');

});