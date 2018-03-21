require('./bootstrap');
require('./helpers');

$(document).ready(function () {
    // Selects
    $('select').material_select();

    // Datepicker
    $('.datepicker').pickadate(
        {
            format: 'mm/dd/yyyy',
            selectMonths: true,
            selectYears: 15,
            today: 'Today',
            clear: 'Clear',
            close: 'Ok',
            closeOnSelect: true
        }
    );

    // Logout
    $('#logout-btn').on(
        'click',
        function (event) {
            event.preventDefault();

            axios.post(
                '/account/logout',
                qs.stringify(
                    {}
                )
            )
                .then(
                    () => {
                        window.location.pathname = '/';
                    }
                )
                .catch(
                    () => showError('Can not log out.')
                )
        }
    );

});


