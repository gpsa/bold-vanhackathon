(function ($) {
    var grid = jQuery("#grid-selection");

    grid.bootgrid({
        ajax: true,
        post: function () {
            /* To accumulate custom parameter with the request object */
            return {
                id: "b0df282a-0d67-40e5-8558-c9e93b7befed"
            };
        },
        url: "/reviews/list",
        selection: true,
        multiSelect: true,
        formatters: {
            'dateFromApi': function (column, row) {
                return row.createdAt.date;
            },
            'appSlug': function (column, row) {
                return row.appSlug.name;
            },
            'rating': function (column, row) {
                return (row.previousStarRating && row.previousStarRating !== null
                    ? '<span class="glyphicon glyphicon-asterisk"></span>'.repeat(row.starRating)
                    + '<br />' + '<span class="glyphicon glyphicon-asterisk"></span>'.repeat(row.previousStarRating)
                    + ' (original)'
                    : '<span class="glyphicon glyphicon-asterisk"></span>'.repeat(row.starRating));
            }
        }
    }).on("selected.rs.jquery.bootgrid", function (e, rows) {
        var rowIds = [];
        for (var i = 0; i < rows.length; i++) {
            rowIds.push(rows[i].id);
        }
        alert("Select: " + rowIds.join(","));
    }).on("deselected.rs.jquery.bootgrid", function (e, rows) {
        var rowIds = [];
        for (var i = 0; i < rows.length; i++) {
            rowIds.push(rows[i].id);
        }
        alert("Deselect: " + rowIds.join(","));
    })

    let search = $('.search');

    search.remove();

    let dropdownFilterByProduct = $('#dropdownFilterByProduct');
    var aDropDown = dropdownFilterByProduct.find('.dropdown-menu>li');

    aDropDown.find('a').click(function (event) {

        event.preventDefault();
        aDropDown.not(this).removeClass('active')
        aDropDown.has(this).addClass('active');

        let filter = $(this).data('value');


        grid.bootgrid("search", filter.length === 0 ? '' : {'appSlug': {"cond": "in", "text": [filter]}});
    })
})(jQuery);