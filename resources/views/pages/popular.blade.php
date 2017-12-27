@extends('layouts.default')
@section('title')
 {{ $page_title }}
@stop
@section('content')
    <div class="popular-banner text-primary">
        <h3><span class="glyphicon glyphicon-film"></span>
        Add popular movies to your collection or start <a href="/movies" class="text-underline">adding your own favorite movies</a> now.</h3>
    </div><br>

    <?php if($movies) : ?>
        <div class="row imagetiles">
            <?php foreach($movies as $movie) : ?>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <a href="#movie-<?=$movie->id?>" role="button" data-toggle="modal">
                    <img  class="img-responsive" src="<?=$image_url_prefix?>/<?=$movie->poster_path?>">
                </a>
                <div class="modal fade" id="movie-<?=$movie->id?>">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <div class="row">
                                <div class="col-xs-6 col-md-4">
                                    <img  class="img-responsive" src="<?=$image_url_prefix?>/<?=$movie->poster_path?>">
                                </div>
                                <div class="col-xs-12 col-md-8">
                                    <h2 class="modal-title"><?=$movie->title?></h2>
                                    <hr class="modal-hr">
                                    <p><?=$movie->overview?></p>
                                    <p><b>Release Date:</b> <?=date('m/d/Y', strtotime($movie->release_date))?></p>
                                    <p><b>Average Rating:</b> <?=round($movie->vote_average / 2)?></p>
                                    <hr>
                                    <!-- @todo check if user is logged in here, do fancy ajax and swap button out-->
                                    <?php
                                        $addToBtnShow = in_array($movie->title, $my_movies) ? 'display:none' : '';
                                        $addedBtnShow = in_array($movie->title, $my_movies) ? '' : 'display:none';
                                    ?>
                                    <a class="js-add-movie btn btn-primary btn-sm" style="<?=$addToBtnShow?>" href="/api/movies/add?source=tmdb&id=<?=$movie->id?>"><span class="glyphicon glyphicon-plus"></span> Add to My Movies</a>
                                    <button type="button" class="btn btn-success btn-sm js-added-movie" style="<?=$addedBtnShow?>" disabled><span class="glyphicon glyphicon-check"></span> Added to My Movies</a>
                                </div>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
@stop
@section('footer')
<div class="popular-banner">
    <hr>
    <p><em>Popular movies list provided by <a href="https://www.themoviedb.org" target="_blank">The Movie Database</a></em></p>
</div>
@stop
