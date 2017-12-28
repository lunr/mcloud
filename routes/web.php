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

Route::get('/movies', 'MovieController@index')->name('movies');

Route::get('/movie/create', 'MovieController@create');
Route::post('/api/movies/update', 'MovieController@update');
Route::get('/api/movies/add', 'MovieController@add');
Route::get('/movie/delete/{id}', 'MovieController@delete')->name('deleteMovie');

Route::get('movie/{movie}', function (App\Movie $movie) {

    if (Gate::denies('update-movie', $movie)) {
        \Session::flash('flash_message', 'You do not have permission to view this movie.');
        \Session::flash('flash_type', 'alert-warning');
        return redirect()->route('movies');
    }

    $page_title = $movie->title;
    $movie_data = $movie->toArray();
    return view('movie', compact('movie', 'movie_data', 'page_title'));
})->name('movie');

Auth::routes();

Route::get('login/github', 'Auth\LoginController@redirectToProvider');
Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');
