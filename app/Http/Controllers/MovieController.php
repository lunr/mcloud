<?php

namespace App\Http\Controllers;

use Gate;

use App\User;
use App\Movie as MovieModel;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class MovieController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $movies = auth()->user()->movies()->orderBy('title', 'asc')->get();

        return view('pages.movies', [ 'movies' => $movies, 'page_title' => 'My Movies' ]);

    }

    public function popular() {
        $my_movies = auth()->user()->movies()->pluck('title')->toArray();

        $popular_movies_cache_key = 'tmdb_popular_movies';
        $popular_movies_cache_expiry_min = 60;
        $error = false;
        $tmdb_config = config('app.tmdb');
        $image_url_prefix = $tmdb_config['image_url_prefix'];

        if ( !Cache::has($popular_movies_cache_key) ) {
            $params = [ 'page' => 1, 'language' => $tmdb_config['language'], 'api_key' => $tmdb_config['api_key'] ];
            $endpoint = sprintf('%s/%s?%s', $tmdb_config['api'], 'popular', http_build_query($params));

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET"
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                $error = $err;
            } else {
                $output = json_decode($response);

                if(!empty($output->results)) {
                    $expiresAt = \Carbon\Carbon::now()->addMinutes($popular_movies_cache_expiry_min);
                    Cache::put($popular_movies_cache_key, json_encode($output->results), $expiresAt);
                }
            }
        }

        $movies = Cache::get($popular_movies_cache_key);

        if($movies) {
            $movies = json_decode($movies);
        }

        $page_title = 'Popular Movies';

        return view('pages.popular', compact('page_title', 'movies', 'my_movies', 'error', 'image_url_prefix'));
    }

    public function create() {
        $movie = new MovieModel;
        $page_title = 'Create';
        $movie_data = array_fill_keys($movie->getFillable(), '');

        return view('movie', compact('movie', 'movie_data', 'page_title'));
    }

    public function update(Request $request) {
        $this->validate($request, [
            'title' => 'required|string|min:1|max:50',
            'format' => 'required',
            'length' => 'required|integer|min:1|max:499',
            'release_year' => 'required|integer|min:1801|max:2099',
            'rating' => 'nullable|integer|min:1|max:5'
        ]);

        $movieId = $request->input('id');

        if($movieId) {
            $movie = MovieModel::find($movieId);

            if (Gate::denies('update-movie', $movie)) {
                abort(403);
            }

            $result = $movie->update($request->all());
        } else {
            $result = $movie = auth()->user()->movies()->create($request->all());
        }

        if($result) {
            return response()->json($movie, 200);
        } else {
            return response()->json([ 'error' => 'Unable to update movie data' ], 500);
        }

    }

    public function delete($id) {
        $movie = MovieModel::find($id);

        if (Gate::denies('update-movie', $movie)) {
            \Session::flash('flash_message', 'You do not have permission to delete this movie.');
            \Session::flash('flash_type', 'alert-warning');
            return redirect()->route('movies');
        }

        $movie->delete();

        \Session::flash('flash_message', 'Movie was deleted successfully');
    	\Session::flash('flash_type', 'alert-info');

        return redirect()->route('movies');
    }

    public function add(Request $request) {
        $source = false;
        $id = false;

        if(!$request->source || !$request->id) {
            return response()->json([ 'error' => 'Source or movie ID must be valid'], 400);
        }

        $tmdb_config = config('app.tmdb');

        $params = [ 'language' => $tmdb_config['language'], 'api_key' => $tmdb_config['api_key'] ];
        $endpoint = sprintf('%s/%s?%s', $tmdb_config['api'], $request->id, http_build_query($params));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return response()->json([ 'error' => 'Movie not found'], 404);
        } else {
            $tmdb_movie = json_decode($response);

            $movieData = [
                'title' => $tmdb_movie->title,
                'format' => 'Streaming',
                'length' => $tmdb_movie->runtime,
                'release_year' => date('Y', strtotime($tmdb_movie->release_date)),
                'rating' => round($tmdb_movie->vote_average / 2)
            ];

            $movie = auth()->user()->movies()->create($movieData);
            return response()->json($movie, 200);
        }

    }
}
