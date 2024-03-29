<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Home\HomeSliderController;
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
    return view('frontend.index');
});


//Admin All Route
Route::controller(AdminController::class)->group(function(){
  Route::get('/admin/logout', 'destroy')->name('admin.logout');
  Route::get('/admin/profile', 'profile')->name('admin.profile');
  Route::get('/edit/profile', 'edit_profile')->name('edit.profile');
  Route::post('/store/profile', 'store_profile')->name('store.profile');
  
  Route::get('/change/password', 'change_password')->name('change.password');
  Route::post('/update/password', 'update_password')->name('update.password');
});

// home slide route
Route::controller(HomeSliderController::class)->group(function(){
  Route::get('/home/slide', 'home_slider')->name('home.slide');
  Route::post('/update/slide', 'update_slider')->name('update.slide');
 
});



Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
