
if ($('#calendar-book').length > 0) {
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar-book');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            themeSystem: 'bootstrap5',
            locale: 'fr',
            headerToolbar: {
                left: 'title, prev,today next',
                //center: '',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            initialView: 'timeGridWeek',
            navLinks: true, // can click day/week names to navigate views
            // businessHours: true, // display business hours
            editable: true,
            selectable: true,
            events: [
                {
                    title: '10:00am House Clean..',
                    start: '2024-08-27 10:00',
                    end: '2024-08-27 16:00',
                    color: '#4c40ed1a',
                    textColor: '#4C40ED'
                },
            ],
            loading: function (isLoading) {
                console.log(isLoading)
                if (isLoading) {
                    //$('#loading').show();
                } else {
                    //$('#loading').hide();
                }
            },
            eventClick: function (event, calEvent, jsEvent, view) {
                $(".fc-event-title-container").on("click", function () {
                    console.log(this)
                    $('.toggle-sidebar').addClass('sidebar-popup');
                });
                $(".sidebar-close").on("click", function () {
                    $('.toggle-sidebar').removeClass('sidebar-popup');
                });
            }
        });

        calendar.render();
    });
}