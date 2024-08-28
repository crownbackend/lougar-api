if ($('#calendar-book').length > 0) {
    function isMobile() {
        const regex = /Mobi|Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i;
        return regex.test(navigator.userAgent);
    }

    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar-book');

        // Charger les événements via AJAX
        $.ajax({
            url: '/proprietaire/reservations',
            method: 'GET',
            contentType: 'application/json',
            success: function (response) {
                var events = []; // Définir les événements ici pour garantir la portée
                response.reservations.forEach(v => {
                    events.push({
                        id: v.id,
                        title: v.garage.name,
                        start: v.startAt, // Utilisation du format ISO standard
                        end: v.endAt,   // Utilisation du format ISO standard
                        color: '#4c40ed1a',
                        textColor: '#4C40ED',
                        extendedProps: {
                            info: v.info
                        }
                    });
                });

                // Initialiser le calendrier après que les événements soient chargés
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
                    editable: true,
                    selectable: true,
                    events: events, // Charger les événements après l'initialisation
                    eventClick: function (event, calEvent, jsEvent, view) {
                        console.log(event.event)
                        $(".fc-event-title-container").on("click", function () {
                            $('.toggle-sidebar').addClass('sidebar-popup');
                        });
                        $(".sidebar-close").on("click", function () {
                            $('.toggle-sidebar').removeClass('sidebar-popup');
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
}