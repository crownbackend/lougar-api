$(document).ready(function () {
    var $select = $('.select')
    var $price = $('#price')
    if ($select.length > 0) {
        $price.html("€" + $select.select2().find(":selected").data("price-hour") + '/h')

        $select.on('select2:select', function (e) {
            console.log()
            console.log()
            if($(e.params.data.element).data('price-hour')) {
                $price.html("€" + $select.select2().find(":selected").data("price-hour") + '/h')
            } else if($(e.params.data.element).data('price-day')) {
                $price.html("€" + $select.select2().find(":selected").data("price-day") + '/j')
            }
        });
    }
})