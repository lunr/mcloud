<?php

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

Route::get('/', 'MovieController@popular');

Route::post('/api/movies/update', 'MovieController@update');

Route::get('/movies', 'MovieController@index')->name('movies');

Route::get('movie/{movie}', function (App\Movie $movie) {
    $page_title = $movie->title;
    return view('movie', compact('movie', 'page_title'));
})->name('movie');
