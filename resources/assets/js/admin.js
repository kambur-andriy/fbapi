require('./bootstrap');



$(document).ready(function () {

    // Logout
    $('#logout-btn').on('click', function (event) {
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

    });


});


