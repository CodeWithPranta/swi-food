<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\VendorRegistrationController;
use App\Http\Controllers\FoodController;
use App\Livewire\CartDetails;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Livewire\Chat;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GoogleController;

// Route::get('/home', function () {
//     return view('welcome');
// })->name('home');

Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/page/{slug}', [PageController::class, 'pageView'])->name('page.view');

Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::get('/homestaurant-application', [VendorRegistrationController::class, 'showRegistrationForm'])->name('homestaurant.application')->middleware(['auth', 'verified', 'homestaurant']);
Route::post('/homestaurant-application', [VendorRegistrationController::class, 'submitRegistrationForm'])->name('homestaurant.application.submit')->middleware(['auth', 'verified', 'homestaurant']);
Route::get('/apply/thank-you', [VendorRegistrationController::class, 'thankYou'])->name('homestaurant.application.thankyou')->middleware(['auth', 'verified', 'homestaurant']);

Route::post('/location-filter', [LocationController::class, 'storeOrUpdateLocation'])->name('store-location');
Route::get('/nearby-homestaurants', [LocationController::class, 'index'])->name('nearby.homestaurants');
Route::get('/homestaurant/{id}/{kitchen_name}', [LocationController::class, 'show'])->name('homestaurant.show');
Route::get('/food-details/{id}/{name}', [FoodController::class, 'foodDetails'])->name('food.details');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::controller(GoogleController::class)->group(function(){
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::post('/order-now', [OrderController::class, 'orderNow'])->name('order.now');
    Route::post('/orders/place', [OrderController::class, 'placeOrder'])->name('orders.place');
    Route::get('/orders/success', [OrderController::class, 'orderSuccess'])->name('orders.success');
    Route::get('/orders', [OrderController::class, 'customerOrders'])->name('orders.customer');
    Route::get('/orders/{id}', [OrderController::class, 'orderDetails'])->name('orders.details');
    Route::get('/my-orders/{id}', [OrderController::class, 'customerOrderDetails'])->name('customer.orders.show');
    Route::get('/cart', CartDetails::class)->name('cart.details');

    Route::get('/chat', Chat::class)->name('chat');

    Route::post('/orders/{order}/rate', [RatingController::class, 'submit'])->name('rating.submit');

    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

    Route::get('account', [App\Http\Controllers\AccountController::class, 'index'])->name('account.index');
    Route::post('account/upgrade', [App\Http\Controllers\AccountController::class, 'upgradeAccount'])->name('account.upgrade');
});

require __DIR__.'/auth.php';
