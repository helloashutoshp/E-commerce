<?php

use App\Http\Controllers\admin\adminController;
use App\Http\Controllers\admin\brandController;
use App\Http\Controllers\admin\categoryCotroller;
use App\Http\Controllers\admin\homeController;
use App\Http\Controllers\admin\imageController;
use App\Http\Controllers\admin\productController;
use App\Http\Controllers\admin\subCategoryController;
use App\Http\Controllers\front\homeController as FrontHomeController;
use App\Http\Controllers\front\shoppingController;
use App\Http\Controllers\paymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [FrontHomeController::class, 'index'])->name('home');
Route::get('/shop/{category?}/{subCategory?}', [shoppingController::class, 'index'])->name('shop');



Route::group(['prefix' => '/admin'], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [adminController::class, 'index'])->name('admin-login');
        Route::post('/authenticate', [adminController::class, 'authenticate'])->name('admin-authenticate');
    });
    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/dashboard', [homeController::class, 'index'])->name('admin-dashboard');
        Route::get('/logout', [homeController::class, 'logout'])->name('admin-logout');

        //category routes
        Route::get('/category/create', [categoryCotroller::class, 'create'])->name('admin-category-create');
        Route::post('/category/store', [categoryCotroller::class, 'store'])->name('admin-category-store');
        Route::get('/get-slug', [categoryCotroller::class, 'slug'])->name('get-slug');
        Route::get('/category', [categoryCotroller::class, 'index'])->name('category-list');
        Route::post('/image-temp-create', [imageController::class, 'index'])->name('temp-image-create');
        Route::get('/category/edit/{id}', [categoryCotroller::class, 'edit'])->name('edit-category');
        Route::post('/category/update', [categoryCotroller::class, 'update'])->name('update-category');
        Route::get('/category/delete/{id}', [categoryCotroller::class, 'destroy'])->name('delete-category');

        //sub-categpory routes
        Route::get('/sub-category/create', [subCategoryController::class, 'create'])->name('admin-sub-category-create');
        Route::post('/sub-category/store', [subCategoryController::class, 'store'])->name('admin-sub-category-store');
        Route::get('/sub-category', [subCategoryController::class, 'index'])->name('sub-category-list');
        Route::get('/sub-category/delete/{id}', [subCategoryController::class, 'destroy'])->name('sub-category-delete-category');
        Route::get('/sub-category/edit/{id}', [subCategoryController::class, 'edit'])->name('edit-sub-category');
        Route::post('/sub-category/update', [subCategoryController::class, 'update'])->name('admin-sub-category-update');

        //brands routes
        Route::get('/brands/create', [brandController::class, 'create'])->name('admin-brand-create');
        Route::post('/brands/store', [brandController::class, 'store'])->name('admin-brand-store');
        Route::get('/brands', [brandController::class, 'index'])->name('admin-brand-list');
        Route::get('/brand/delete/{id}', [brandController::class, 'destroy'])->name('admin-brand-delete');
        Route::get('/brand/edit/{id}', [brandController::class, 'edit'])->name('admin-brand-edit');
        Route::post('/brand/upadate/', [brandController::class, 'update'])->name('update-brand');

        //products route
        Route::get('/product/sub_category', [productController::class, 'subCategory'])->name('product-subCategory');
        Route::get('/product/create', [productController::class, 'create'])->name('admin-product-create');
        Route::post('/product/store', [productController::class, 'store'])->name('admin-product-store');
        Route::get('/products', [productController::class, 'index'])->name('admin-product-list');
        Route::get('/product/delete/{id}', [productController::class, 'destroy'])->name('admin-product-delete');
        Route::get('/product/edit/{id}', [productController::class, 'edit'])->name('admin-product-edit');
        Route::post('/product/update/', [productController::class, 'update'])->name('admin-product-update');
        Route::post('/product/update/image', [productController::class, 'updateImage'])->name('update-productImage');
        Route::get('/product/image-delete', [productController::class, 'deleteProductImage'])->name('deleteProductImage');
    });
});
Route::get('/stripe', [paymentController::class, 'index'])->name('payment-index');
Route::post('/stripe', [paymentController::class, 'catch'])->name('payment-catch');


