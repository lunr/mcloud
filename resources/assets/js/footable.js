$(document).ready(function() {
    $('table.footable').footable();

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
});
