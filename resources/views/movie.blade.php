@extends('layouts.default')
@section('title')
 {{ $page_title }}
@stop
@section('content')
    <router-view></router-view>

    <template id="movie" v-if="!editMode">
        <div>
            <h2>@{{ movie.title }}</h2>
            <dl>
                <dt>Format</dt><dd>@{{ movie.format }}</dt>
                <dt>Length</dt><dd>@{{ movie_length_human }}</dd>
                <dt>Release Year</dt><dd>@{{ movie.release_year }}</dd>
                <dt>Rating</dt><dd>@{{ movie.rating }}</dd>
            </dl>
            <p><button class="btn btn-primary btn-sm" v-on:click="enableEditMode"><span class="glyphicon glyphicon-edit"></span> Edit</button></p>
            <br/>
            <a href="/movies"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back to my movies</a>
        </div>
    </template>

    <template id="movie-edit" v-if="editMode">
        <div>
            <h2>Edit movie</h2>
            <form v-on:submit="updateMovie">
                <div class="form-group">
                    <label for="edit-title">Title</label>
                    <input class="form-control" id="edit-title" v-model="movie.title" required/>
                    <span v-if="formErrors.title" class="text-danger">@{{ formErrors.title[0] }}</span>
                </div>
                <div class="form-group">
                    <label for="edit-format">Format</label>
                    <select class="form-control" id="edit-format" v-model="movie.format" required>
                		<option v-for="opt in formats">@{{ opt }}</option>
                    </select>
                    <span v-if="formErrors.format" class="text-danger">@{{ formErrors.format[0] }}</span>
                </div>
                <div class="form-group">
                    <label for="edit-length">Length</label>
                    <input class="form-control" id="edit-length" v-model="movie.length" required/>
                    <span v-if="formErrors.length" class="text-danger">@{{ formErrors.length[0] }}</span>
                </div>
                <div class="form-group">
                    <label for="edit-release-year">Release Year</label>
                    <input class="form-control" id="edit-release-year" v-model="movie.release_year" required/>
                    <span v-if="formErrors.release_year" class="text-danger">@{{ formErrors.release_year[0] }}</span>
                </div>
                <div class="form-group">
                    <label>Rating</label><br>
                    <?php for($i = 1; $i <= 5; $i++) : ?>
                        <label class="radio-inline">
                            <input type="radio" id="rating{{ $i }}" v-model="movie.rating" value="{{ $i }}"> {{ $i }}
                        </label>
                    <?php endfor; ?>
                    <span v-if="formErrors.rating" class="text-danger">@{{ formErrors.rating[0] }}</span>
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-ok-circle"></span> Save</button>
                <a class="btn btn-danger btn-sm" v-if="movie.id" href="#confirm-delete" role="button" data-toggle="modal"><span class="glyphicon glyphicon-remove"></span> Delete</a>
                <button type="button" class="btn btn-default btn-sm" v-on:click="cancelEditMode"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</button>
            </form>
        </div>
    </template>

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Delete Movie
                </div>
                <div class="modal-body">
                    Are you sure you want to delete @{{ movie.title }}?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a href="<?=route('deleteMovie', [ 'id' => $movie->id]);?>" id="deleteMovieButton" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var MovieManager = new Vue({
            el: '#main',
            data: {
                editMode: {!! (!$movie->id ? 'true' : 'false') !!},
                formats: [ 'VHS', 'DVD', 'Streaming' ],
                formErrors: {},
                movie: {!! json_encode($movie_data) !!}
            },
            computed: {
                "movie_length_human": function() {
                    var hours = Math.floor( this.movie.length / 60);
                    var minutes = this.movie.length % 60;

                    var human_readable_length = '';

                    if(hours > 0) {
                        human_readable_length += hours + ' hr ';
                    }

                    if(minutes > 0) {
                        human_readable_length += minutes + ' m';
                    }

                    return human_readable_length;
                }
            },
            methods: {
                enableEditMode: function() {
                    this.editMode = true;
                },
                cancelEditMode: function() {
                    this.editMode = false;
                },
                updateMovie(e) {
                    e.preventDefault();

                    var controller = this;
                    var movie = controller.movie;

                    var newMovie = (typeof movie.id == 'undefined') ? true : false;

                    controller.formErrors = {};

                    axios.post('/api/movies/update', movie)
                        .then(function (resp) {
                            controller.movie = resp.data;
                            controller.editMode = false;

                            if(newMovie) {
                                window.history.pushState('Movie', controller.movie.title, '/movie/' + controller.movie.id);
                                document.title = document.title.split('-')[0] + ' - ' + controller.movie.title;
                                var deleteButtonHref = $('#deleteMovieButton').attr('href');
                                $('#deleteMovieButton').attr('href', deleteButtonHref + '/' + controller.movie.id);
                            }
                        })
                        .catch(function (error) {
                            if (error.response) {
                              controller.formErrors = error.response.data;
                            } else {
                              alert('Could not update the movie');
                            }
                        });
                }
            }
        });
    </script>
@stop
