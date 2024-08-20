$(document).ready(function () {

    var $select = $('.select')
    var $price = $('#price')
    var $priceResult = $('#price_result')
    if ($select.length > 0) {
        $price.html("€" + $select.select2().find(":selected").data("price-hour") + '/h')
        $priceResult.html("€" + $select.select2().find(":selected").data("price-hour") + '/h')

        $select.on('select2:select', function (e) {
            if($(e.params.data.element).data('price-hour')) {
                $price.html("€" + $select.select2().find(":selected").data("price-hour") + '/h')
                $priceResult.html("€" + $select.select2().find(":selected").data("price-hour") + '/h')
            } else if($(e.params.data.element).data('price-day')) {
                $price.html("€" + $select.select2().find(":selected").data("price-day") + '/j')
                $priceResult.html("€" + $select.select2().find(":selected").data("price-day") + '/j')
            }
        });
    }

    if ($('.timepicker').length > 0) {
        $('.timepicker').datetimepicker({
            format: 'HH:mm',
            icons: {
                up: "fas fa-angle-up",
                down: "fas fa-angle-down",
                next: 'fas fa-angle-right',
                previous: 'fas fa-angle-left'
            }
        });
    }


    if ($('#datetimepickershow').length > 0) {
        $('#datetimepickershow').datetimepicker({
            locale: 'fr',
            inline: true,
            sideBySide: true,
            format: 'DD-MM-YYYY',
            icons: {
                up: "fas fa-angle-up",
                down: "fas fa-angle-down",
                next: 'fas fa-angle-right',
                previous: 'fas fa-angle-left'
            }

        });
    }

})