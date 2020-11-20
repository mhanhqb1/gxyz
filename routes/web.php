<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

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

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/images', [HomeController::class, 'images'])->name('home.images');
Route::get('/videos', [HomeController::class, 'index'])->name('home.index');
Route::get('/movies', [HomeController::class, 'index'])->name('home.index');
Route::get('/18images', [HomeController::class, 'images18'])->name('home.images18');
Route::get('/18movies', [HomeController::class, 'index'])->name('home.index');

Route::get('/flickrCrawler', [HomeController::class, 'flickrCrawler'])->name('home.flickr_crawler');
Route::get('/flickrDailyCrawler', [HomeController::class, 'flickrDailyCrawler'])->name('home.flickrDailyCrawler');


Route::get('/checkImages', [AdminController::class, 'checkImages'])->name('admin.checkImages');
Route::post('/ajaxUpdateImages', [AdminController::class, 'ajaxUpdateImages'])->name('admin.ajaxUpdateImages');
