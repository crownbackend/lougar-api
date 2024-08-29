if ($('#calendar-book').length > 0) {
    function isMobile() {
        const regex = /Mobi|Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i;
        return regex.test(navigator.userAgent);
    }

    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar-book');

        // Charger les événements via AJAX
        $.ajax({
            url: '/proprietaire/reservation/reservations',
            method: 'GET',
            contentType: 'application/json',
            success: function (response) {
                var events = []; // Définir les événements ici pour garantir la portée
                response.reservations.forEach(v => {
                    let color = "#4c40ed1a"
                    let title = v.garage.name
                    let startAt = v.startAt
                    let endAt = v.endAt
                    if(v.status === 1) {
                        color = '#FFE30F'
                        title =  v.garage.name + ' (En attente)'
                    } else if(v.status === 2) {
                        color = '#6DCC76'
                        title =  v.garage.name + ' (Confirmer)'
                    } else if(v.status === 3) {
                        color = '#F82424'
                        title =  v.garage.name + ' (Annuler)'
                    }
                    if(v.info.type === 'day') {
                        endAt = changeEndDateDay(endAt)
                    }
                    events.push({
                        id: v.id,
                        title: title,
                        start: startAt, // Utilisation du format ISO standard
                        end: endAt,   // Utilisation du format ISO standard
                        color: color,
                        textColor: '#000000',
                        extendedProps: {
                            info: v.info
                        }
                    });
                });

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    themeSystem: 'bootstrap5',
                    locale: 'fr',
                    timeZone: 'Europe/Paris',
                    headerToolbar: {
                        left: 'title, prev,today next',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                    },
                    initialView: isMobile ? 'timeGridWeek' : 'listMonth', // Appeler la fonction isMobile()
                    navLinks: true,
                    editable: false,
                    selectable: false,
                    events: events,
                    eventClick: function (event, calEvent, jsEvent, view) {
                        console.log(event.event.id);
                        $(".fc-event-title-container").on("click", function () {
                            const $toggle = $('.toggle-sidebar')
                            $toggle.addClass('sidebar-popup');
                            $.ajax({
                                url: '/proprietaire/reservation/' + event.event.id + '/sidebar',
                                method: 'GET',
                                contentType: 'application/json',
                                success(response) {
                                    $('#content_reservation').html(response);
                                    // Déplacer l'événement .sidebar-close ici, pour que le bouton soit disponible après l'injection
                                    $toggle.on("click", ".sidebar-close", function () {
                                        $('.toggle-sidebar').removeClass('sidebar-popup');
                                    });
                                },
                                error() {
                                    alert('Erreur serveur')
                                }
                            });
                        });
                    }
                });

                calendar.render();
            },
            error: function (response) {
                alert("Erreur serveur !");
            }
        });
    });

    function changeEndDateDay(dateStr) {
        // Date initiale sous forme de chaîne de caractères

// Extraire l'année, le mois et le jour de la chaîne
        let [year, month, day] = dateStr.split('-').map(Number);

// Ajouter un jour
        day += 1;

// Gérer le changement de mois et d'année
        let daysInMonth = new Date(year, month, 0).getDate(); // Nombre de jours dans le mois actuel

        if (day > daysInMonth) {
            day = 1; // Réinitialiser le jour
            month += 1; // Passer au mois suivant
        }

        if (month > 12) {
            month = 1; // Réinitialiser le mois
            year += 1; // Passer à l'année suivante
        }

        return `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    }
}