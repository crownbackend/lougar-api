$(document).ready(function () {
    if ($('.select').length > 0) {
        $('.select').on('select2:select', function (e) {
            console.log(e.params.data.element)
        });
    }
})