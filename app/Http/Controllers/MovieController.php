<?php

namespace App\Http\Controllers;

use App\User;
use App\Movie as MovieModel;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class MovieController extends Controller {

    public function index() {

        $movies = MovieModel::all();

        return view('pages.movies', [ 'movies' => $movies ]);

    }

    public function popular() {
        return view('pages.popular');
    }
}
