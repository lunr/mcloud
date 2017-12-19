@extends('layouts.default')
@section('content')
    <ul>
    <?php foreach($movies as $movie) : ?>
        <li><?=$movie->title?></li>
    <?php endforeach; ?>
    </ul>
@stop
