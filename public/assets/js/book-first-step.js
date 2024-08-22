$(document).ready(function () {

    var $garageId = $('#garage_id').data('id')
    var $select = $('.select')
    var $price = $('#price')
    var $priceResultDay = $('#price_result_day')
    var $priceResultHours = $('#price_result_hours')
    var $timers = $('.timepicker')
    var $hourContent = $('#hour_content')
    var $endDay = $('#end_day')
    var priceTaux = null

    if ($select.length > 0) {
        $price.html("€" + $select.select2().find(":selected").data("price-hour") + '/h')
        $priceResultHours.html("€" + $select.select2().find(":selected").data("price-hour") + '/h')

        priceTaux = $select.select2().find(":selected").data("price-hour")
        $select.on('select2:select', function (e) {
            if($(e.params.data.element).data('price-hour')) {
                $price.html("€" + $select.select2().find(":selected").data("price-hour") + '/h')
                $priceResultHours.html("€" + $select.select2().find(":selected").data("price-hour") + '/h')
                $endDay.hide()
                $hourContent.show()
                priceTaux = $select.select2().find(":selected").data("price-hour")
            } else if($(e.params.data.element).data('price-day')) {
                $price.html("€" + $select.select2().find(":selected").data("price-day") + '/j')
                $priceResultDay.html("€" + $select.select2().find(":selected").data("price-day") + '/j')
                $endDay.show()
                $hourContent.hide()
                $timers.val('')
                priceTaux = $select.select2().find(":selected").data("price-day")
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
            $timers.each(function () {
                var currentTime = $(this).val(); // Récupérer la valeur de l'heure sélectionnée
                var currentTimeMoment = moment(currentTime, 'HH:mm'); // Convertir en objet moment

                // Comparer l'heure actuelle avec l'heure précédente
                if (previousTime && currentTimeMoment.isBefore(previousTime)) {
                    isValid = false;
                    Swal.fire({
                        icon: "error",
                        title: "L'heure de fin ne peut pas être avant l'heure de début !",
                    });
                    return false; // Sortir de la boucle
                }

                // Mettre à jour l'heure précédente pour la prochaine comparaison
                previousTime = currentTimeMoment;
                if(currentTime) {
                    times.push(currentTime)
                }
            });
            if(times.length >= 2) {
                const TotalPrice = calculatePriceHours(priceTaux, times[0], times[1]);
                $priceResultHours.html("€" + TotalPrice + '/h')
                const data = {
                    startTime: times[0],
                    endTime: times[1],
                    date: startDate,
                    priceTaux: priceTaux,
                    totalPrice: TotalPrice,
                    type: 'hours'
                }
                const token = btoa(JSON.stringify(data));
                // Rediriger vers l'URL avec le token en GET
                window.location.href = '/reservation/' + $garageId+'/etape-2/' + encodeURIComponent(token);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Il faut une heure de début et une heure de fin !",
                });
            }

        } else if ($select.val() === 'j') {
            if(startDate && endDate) {
                const days = calculerDay(startDate, endDate)
                const TotalPrice = days * priceTaux
                $priceResultDay.html("€" + TotalPrice + '/j')
                Swal.fire({
                    title: "Le prix de la location : " + TotalPrice + '€ pour : ' + days + 'jours',
                    showCancelButton: true,
                    confirmButtonText: "Save",
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        console.log('he')
                        var endOfDay = startDate.endOf('day');
                        const data = {
                            startDate: endOfDay.format('YYYY-MM-DD HH:mm:ss'),
                            endDate: endDate.format('YYYY-MM-DD'),
                            priceTaux: priceTaux,
                            totalPrice: TotalPrice,
                            days: days,
                            type: 'day'
                        }
                        const token = btoa(JSON.stringify(data));
                        // Rediriger vers l'URL avec le token en GET
                        window.location.href = '/reservation/' + $garageId+'/etape-2/' + encodeURIComponent(token);
                    }
                });

            } else {
                Swal.fire({
                    icon: "error",
                    title: "Il faut une date de début et une date de fin !",
                });
            }
        }

    })

    function calculatePriceHours(price, start, end) {
        // Convertir les heures en minutes depuis minuit
        const [debutHeures, debutMinutes] = start.split(':').map(Number);
        const [finHeures, finMinutes] = end.split(':').map(Number);

        const debutTotalMinutes = (debutHeures * 60) + debutMinutes;
        const finTotalMinutes = (finHeures * 60) + finMinutes;

        // Calculer la différence de temps en minutes
        let dureeMinutes = finTotalMinutes - debutTotalMinutes;

        // Si l'heure de fin est avant l'heure de début, on suppose que la durée traverse minuit
        if (dureeMinutes < 0) {
            dureeMinutes += 24 * 60; // Ajouter 24 heures en minutes
        }

        // Convertir la durée en heures
        const dureeHeures = dureeMinutes / 60;

        // Calculer le montant total
        const montantTotal = price * dureeHeures;

        return montantTotal.toFixed(2); // Retourne le montant avec 2 décimales
    }

    function calculerDay(startDate, endDate) {
        let diffDays =  endDate.diff(startDate, 'days');
        return diffDays + 1;
    }

})