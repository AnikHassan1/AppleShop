<?php

use App\Http\Controllers\brandController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PolicieController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\userController;
use App\Http\Middleware\userAuthentificationMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Pages.homePage');
});
// users
Route::get('/homepage',[HomeController::class,'Homepage']);


// users
Route::get('/users',[userController::class,'users']);
// Brands
Route::get('/BrandList',[brandController::class,'BrandList']);
// category
Route::get('/CategoryList',[categoryController::class,'CategoryList']);

// product Routing

Route::get('/ListProductByCategory/{id}',[ProductController::class,'ListProductByCategory']);
Route::get('/ListProductByBrand/{id}',[ProductController::class,'ListProductByBrand']);
Route::get('/ListProductByRemark/{remark}',[ProductController::class,'ListProductByRemark']);


// Product Slider
Route::get('/ListProductSlider',[ProductController::class,'ListProductSlider']);
// Product Details
Route::get('/ProductDetailsById/{id}',[ProductController::class,'ProductDetailsById']);
Route::get('/ListReviewByProduct/{product_id}',[ProductController::class,'ListReviewByProduct']);

// PolicyByType
Route::get('/PolicyByType/{type}',[PolicieController::class,'PolicyByType']);





// user Auth
Route::get('/userLogin/{userEmail}',[userController::class,'userLogin']);
Route::get('/userVerify/{UserEmail}/{otp}',[userController::class,'userVerify']);
Route::get('/userLogOut',[userController::class,'userLogOut']);

// profile
Route::post('/CreateProfile',[profileController::class,'CreateProfile'])->middleware(userAuthentificationMiddleware::class);
Route::get('/ReadProfile',[profileController::class,'ReadProfile'])->middleware(userAuthentificationMiddleware::class);

// Product Review
Route::post('/createProductReview',[ProductController::class,'createProductReview'])->middleware(userAuthentificationMiddleware::class);
// Product Wishlist
Route::get('/createWishlist/{product_id}',[ProductController::class,'createWishlist'])->middleware(userAuthentificationMiddleware::class);
Route::get('/removeWishlist/{product_id}',[ProductController::class,'removeWishlist'])->middleware(userAuthentificationMiddleware::class);
Route::get('/productWishlist',[ProductController::class,'productWishlist'])->middleware(userAuthentificationMiddleware::class);

// CartList
Route::post('/productCart',[ProductController::class,'productCart'])->middleware(userAuthentificationMiddleware::class);
Route::get('/CartList',[ProductController::class,'CartList'])->middleware(userAuthentificationMiddleware::class);
Route::get('/CartListDelete/{product_id}',[ProductController::class,'CartListDelete'])->middleware(userAuthentificationMiddleware::class);

// Invoicess
Route::get('/InvoiceCreate',[InvoiceController::class,'InvoiceCreate'])->middleware(userAuthentificationMiddleware::class);
Route::get('/invoiceList',[InvoiceController::class,'invoiceList'])->middleware(userAuthentificationMiddleware::class);
Route::get('/invoiceProductList/{invoice_Id}',[InvoiceController::class,'invoiceProductList'])->middleware(userAuthentificationMiddleware::class);

// payment 
Route::post("/PaymentSuccess",[InvoiceController::class,"paymentSuccess"]);
Route::post("/PaymentFail",[InvoiceController::class,"paymentCencel"]);
Route::post("/PaymentCancel",[InvoiceController::class,"paymentFail"]);