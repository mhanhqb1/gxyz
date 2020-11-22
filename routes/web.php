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
Route::get('/image/{id}', [HomeController::class, 'imageDetail'])->name('home.imageDetail');
Route::get('/videos', [HomeController::class, 'videos'])->name('home.videos');
Route::get('/video/{id}', [HomeController::class, 'videoDetail'])->name('home.videoDetail');
Route::get('/movies', [HomeController::class, 'index'])->name('home.movies');
Route::get('/18images', [HomeController::class, 'images18'])->name('home.images18');
Route::get('/18movies', [HomeController::class, 'index'])->name('home.18movies');

Route::get('/flickrCrawler', [HomeController::class, 'flickrCrawler'])->name('home.flickr_crawler');
Route::get('/flickrDailyCrawler', [HomeController::class, 'flickrDailyCrawler'])->name('home.flickrDailyCrawler');
Route::get('/youtubeCrawler', [HomeController::class, 'youtubeCrawler'])->name('home.youtubeCrawler');


Route::get('/checkImages', [AdminController::class, 'checkImages'])->name('admin.checkImages');
Route::post('/ajaxUpdateImages', [AdminController::class, 'ajaxUpdateImages'])->name('admin.ajaxUpdateImages');
Route::get('/checkVideos', [AdminController::class, 'checkVideos'])->name('admin.checkVideos');
Route::post('/ajaxUpdateVideos', [AdminController::class, 'ajaxUpdateVideos'])->name('admin.ajaxUpdateVideos');


//Clear Cache facade value:
Route::get('/s-clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/s-migrate', function() {
    $exitCode = Artisan::call('migrate');
    return '<h1>migrate</h1>';
});

//Reoptimized class loader:
Route::get('/s-optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/s-route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/s-route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/s-view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/s-config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('route:cache');
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Clear Config cleared</h1>';
});
