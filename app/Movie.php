<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $table = 'movies';

    protected $fillable = ['title', 'format', 'length', 'release_year', 'rating'];

    /*
     * get_runtime
     *
     * Helper function to calculate the human-friendly runtime
     * of a movie from an integer (in minutes) to the human-friendly
     * run time of `X hr Y m`
    */
    public function get_runtime() {
        if(!$this->length) { return ''; }

        $hours = floor($this->length / 60);
        $minutes = ($this->length % 60);

        $runtime = '';

        if($hours) {
            $runtime .= $hours . ' hr ';
        }

        if($minutes) {
            $runtime .= $minutes . ' m';
        }

        return $runtime;
    }
}
