<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\WishListController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/listings', [ListingController::class, 'index']);
Route::get('/listings/{id}', [ListingController::class, 'show']);
Route::get('/search', [ListingController::class, 'search']);
Route::post('/contact', [ContactController::class, 'contact']);

Route::middleware('auth:sanctum')->group(function () {
//user
  Route::controller(AuthController::class)->group(function () {
    Route::get('/user', 'user');
    Route::post('/logout', 'logout');

  });

//Listing
  Route::controller(ListingController::class)->group(function () {
    // get the listings
    Route::get('/user-listings/{id}', 'user_listing');
    //create listing
    Route::post('listings', 'store');
    // show single listing
    Route::get('listings/{id}/edit', 'edit');
    // update listing
    Route::post('listings/{listing}', 'update');
    //delete listing
    Route::delete('listings/{id}', 'destroy');

  });

//Wishlist
  Route::controller(WishListController::class)->group(function () {
    // get the withlist
    Route::get('/wishlist', 'create');

  });
});
