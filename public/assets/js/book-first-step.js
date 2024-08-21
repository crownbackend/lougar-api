$(document).ready(function () {

    var $select = $('.select')
    var $price = $('#price')
    var $priceResult = $('.price_result')
    var $timers = $('.timepicker')
    var $hourContent = $('#hour_content')
    var $endDay = $('#end_day')

    if ($select.length > 0) {
        $price.html("€" + $select.select2().find(":selected").data("price-hour") + '/h')
        $priceResult.html("€" + $select.select2().find(":selected").data("price-hour") + '/h')

        $select.on('select2:select', function (e) {
            if($(e.params.data.element).data('price-hour')) {
                $price.html("€" + $select.select2().find(":selected").data("price-hour") + '/h')
                $priceResult.html("€" + $select.select2().find(":selected").data("price-hour") + '/h')
                $endDay.hide()
                $hourContent.show()
            } else if($(e.params.data.element).data('price-day')) {
                $price.html("€" + $select.select2().find(":selected").data("price-day") + '/j')
                $priceResult.html("€" + $select.select2().find(":selected").data("price-day") + '/j')
                $endDay.show()
                $hourContent.hide()
                $timers.val('')
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

    if ($('#datetimepickershow_start').length > 0) {
        $('#datetimepickershow_start').datetimepicker({
            locale: 'fr',
            inline: true,
            sideBySide: true,
            minDate:new Date(),
            format: 'DD-MM-YYYY',
            icons: {
                up: "fas fa-angle-up",
                down: "fas fa-angle-down",
                next: 'fas fa-angle-right',
                previous: 'fas fa-angle-left'
            }
        });
    }

    if ($('#datetimepickershow_end').length > 0) {
        $('#datetimepickershow_end').datetimepicker({
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
        $('#datetimepickershow_end').data("DateTimePicker").clear();
    }

    $('#send').click(function (e) {
        let startDate = null;
        let endDate = null;
        var isValid = true;
        var previousTime = null;
        let times = []

        if ($('#datetimepickershow_start').length > 0) {
            startDate = $('#datetimepickershow_start').data('DateTimePicker').date();
        }
        if ($('#datetimepickershow_end').length > 0) {
            endDate = $('#datetimepickershow_end').data('DateTimePicker').date();
        }

        if($select.val() === 'h') {
            $timers.each(function (index) {
                var currentTime = $(this).val(); // Récupérer la valeur de l'heure sélectionnée
                var currentTimeMoment = moment(currentTime, 'HH:mm'); // Convertir en objet moment

                // Comparer l'heure actuelle avec l'heure précédente
                if (previousTime && currentTimeMoment.isBefore(previousTime)) {
                    isValid = false;
                    alert("L'heure de fin ne peut pas être avant l'heure de début.");
                    return false; // Sortir de la boucle
                }

                // Mettre à jour l'heure précédente pour la prochaine comparaison
                previousTime = currentTimeMoment;
                times.push(currentTime)
            });
            console.log(times)
        } else if ($select.val() === 'j') {
            if(startDate && endDate) {
                console.log('dates')
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Il faut une date de début et une date de fin !",
                });
            }
        }


    })

})