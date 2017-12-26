@extends('layouts.default')
@section('title')
 {{ $page_title }}
@stop
@section('content')
    <router-view></router-view>

    <template id="movie">
        <div>
            <h2>@{{ movie.title }}</h2>
            <dl>
                <dt>Format</dt><dd>@{{ movie.format }}</dt>
                <dt>Length</dt><dd>@{{ movie_length_human }}</dd>
                <dt>Release Year</dt><dd>@{{ movie.release_year }}</dd>
                <dt>Rating</dt><dd>@{{ movie.rating }}</dd>
            </dl>
            <p><button class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i> Edit</button></p>
            <br/>
            <a href="/movies"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back to my movies</a>
        </div>
    </template>

    <template id="movie-edit">
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
                <button type="submit" class="btn btn-primary">Save</button>
                <router-link class="btn btn-default" v-bind:to="'/'">Cancel</router-link>
            </form>
        </div>
    </template>

    <script type="text/javascript">
        var MovieManager = new Vue({
            el: '#main',
            data: {
                formats: [ 'VHS', 'DVD', 'Streaming' ],
                formErrors: {},
                movie: {!! json_encode($movie->toArray()) !!}
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
                updateMovie(e) {
                    e.preventDefault();

                    var controller = this;
                    var movie = controller.movie;

                    controller.formErrors = {};

                    axios.post('/api/movies/update', movie)
                        .then(function (resp) {
                            controller.movie = resp.data;
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
