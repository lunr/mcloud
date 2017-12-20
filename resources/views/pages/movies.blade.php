@extends('layouts.default')
@section('content')
<div class="form-inline">
    <div class="pull-right">
        <select id="page-size" class="js-change-page-size form-control" name="change-page-size">
            <option selected="selected">
                10
            </option>
            <option>
                25
            </option>
            <option>
                50
            </option>
            <option value="9999999">
                All
            </option>
        </select>
        <input class="form-control" id="movies-filter" data-table="#movies" placeholder="Search my movies" type="text">
        <a class="js-clear-filter btn btn-sm" data-table="#movies" href="#clear" title="clear filter">[clear]</a>
    </div>
    <table id="movies" class="table footable table-responsive table-striped" data-filter="#movies-filter" data-filter-minimum="3" data-page-size="10">
        <thead>
            <th>Title</th>
            <th>Format</th>
            <th>Length</th>
            <th>Release Year</th>
            <th>Rating</th>
        </thead>
        <tbody>
        <?php foreach($movies as $movie) : ?>
            <tr>
                <td><?=$movie->title?></td>
                <td><?=$movie->format?></td>
                <td><?=$movie->length?></td>
                <td><?=$movie->release_year?></td>
                <td><?=$movie->rating?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

    <script type="text/javascript">
        $('table#movies').footable();

        $('.js-clear-filter').click(function (e) {
          e.preventDefault();
          var table = $(this).attr('data-table');

          $(table).trigger('footable_clear_filter');
        });

        $('.js-change-page-size').on('change', function(e) {
            e.preventDefault();
            var pageSize = $(this).val() || 10;
            var table = $(this).attr('data-table');

            $(table).data('page-size', pageSize).trigger('footable_redraw');
        });
    </script>
@stop
