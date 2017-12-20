<?php

namespace App\Http\Controllers;

use App\User;
use App\Movie as MovieModel;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class MovieController extends Controller {

    public function index() {

        $movies = MovieModel::all();

        // @todo add order by name

        return view('pages.movies', [ 'movies' => $movies, 'page_title' => 'My Movies' ]);

    }

    public function popular() {
        return view('pages.popular');
    }
}
