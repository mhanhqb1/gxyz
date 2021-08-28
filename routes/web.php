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
Route::get('/hot-girl-images', [HomeController::class, 'images'])->name('home.images');
Route::get('/sexy-girl-image/{id}', [HomeController::class, 'imageDetail'])->name('home.imageDetail');
Route::get('/idol/{id}', [HomeController::class, 'idolDetail'])->name('home.idolDetail');
Route::get('/hot-girl-videos', [HomeController::class, 'videos'])->name('home.videos');
Route::get('/sexy-girl-video/{id}', [HomeController::class, 'videoDetail'])->name('home.videoDetail');
Route::get('/movies', [HomeController::class, 'index'])->name('home.movies');
Route::get('/sexy-girl-images', [HomeController::class, 'images18'])->name('home.images18');
Route::get('/sexy-girl-videos', [HomeController::class, 'videos18'])->name('home.18videos');
Route::post('/getVideoStream', [HomeController::class, 'getVideoStream'])->name('home.getVideoStream');

Route::get('/flickrCrawler', [HomeController::class, 'flickrCrawler'])->name('home.flickr_crawler');
Route::get('/flickrDailyCrawler', [HomeController::class, 'flickrDailyCrawler'])->name('home.flickrDailyCrawler');
Route::get('/youtubeCrawler', [HomeController::class, 'youtubeCrawler'])->name('home.youtubeCrawler');
Route::get('/twitterCrawler', [HomeController::class, 'twitterCrawler'])->name('home.twitterCrawler');


Route::get('/checkImages', [AdminController::class, 'checkImages'])->name('admin.checkImages');
Route::post('/ajaxUpdateImages', [AdminController::class, 'ajaxUpdateImages'])->name('admin.ajaxUpdateImages');
Route::get('/checkVideos', [AdminController::class, 'checkVideos'])->name('admin.checkVideos');
Route::post('/ajaxUpdateVideos', [AdminController::class, 'ajaxUpdateVideos'])->name('admin.ajaxUpdateVideos');
Route::get('/addSource', [AdminController::class, 'addSource'])->name('admin.addSource');
Route::post('/saveSource', [AdminController::class, 'saveSource'])->name('admin.saveSource');


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
