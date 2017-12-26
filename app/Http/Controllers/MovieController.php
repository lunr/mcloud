<?php

namespace App\Http\Controllers;

use App\User;
use App\Movie as MovieModel;
use Illuminate\View\View;
use Illuminate\Http\Request;
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

    public function update(Request $request) {
        $this->validate($request, [
            'title' => 'required|string|min:1|max:50',
            'format' => 'required',
            'length' => 'required|integer|min:1|max:500',
            'release_year' => 'required|integer|min:1800|max:2100',
            'rating' => 'nullable|integer|min:1|max:5'
        ]);

        $movieId = $request->input('id');

        if($movieId) {
            $movie = MovieModel::find($movieId);
        } else {
            $movie = new MovieModel();
        }

        $result = $movie->update($request->all());

        if($result) {
            return response()->json($movie, 200);
        } else {
            return response()->json([ 'error' => 'Unable to update movie data' ], 500);
        }

    }
}
