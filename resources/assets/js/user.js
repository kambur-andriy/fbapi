require('./bootstrap');

// Message
const showMessage = message => {
    const modal = $('<div />')
        .attr('id', 'custom-message')
        .addClass('modal')
        .append(
            $('<div />')
                .addClass('modal-content')
                .html(message)
        )
        .append(
            $('<div />').addClass('modal-footer')
                .append(
                    $('<a />')
                        .attr('href', '')
                        .addClass('modal-action modal-close waves-effect waves-green btn-flat')
                        .html('Close')
                )
        );

    $('body').append(modal);

    $('#custom-message').modal(
        {
            dismissible: true
        }
    );

    $('#custom-message').modal('open')
}

// Errors
const showError = errorMessage => {
    Materialize.toast(errorMessage, 4000);
}

$(document).ready(function () {
    // Selects
    $('select').material_select();

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

    // Save profile
    $('#profile_form').on(
        'submit',
        function (event) {
            event.preventDefault();

            const credentials = {
                first_name: $(this).find('input[name="first_name"]').val(),
                last_name: $(this).find('input[name="last_name"]').val(),
            };

            axios.post(
                '/user/profile',
                qs.stringify(credentials)
            )
                .then(
                    () => {
                        showMessage('Profile successfully saved');
                    }
                )
                .catch(
                    error => {

                        const {errors} = error.response.data;

                        $.each(
                            errors,
                            (field, error) => {
                                if (error.length) {
                                    showError(error);
                                }
                            }
                        )
                    }
                )
        }
    );

    // Ad Company
    $('#company_form').on(
        'submit',
        function (event) {
            event.preventDefault();

            const credentials = {
                name: $(this).find('input[name="name"]').val().trim(),
                objective: $(this).find('select[name="objective"]').val()
            };

            axios.post(
                '/user/company',
                qs.stringify(credentials)
            )
                .then(
                    response => {
                        showMessage('Company successfully saved.');

                        $('ad_companies tbody').prepend(
                            $('<tr />')
                                .append(
                                    $('<td />').html(response.data.name)
                                )
                                .append(
                                    $('<td />').html(response.data.objective)
                                )
                        )
                    }
                )
                .catch(
                    error => {

                        const {errors} = error.response.data;

                        $.each(
                            errors,
                            (field, error) => {
                                if (error.length) {
                                    showError(error);
                                }
                            }
                        )
                    }
                )
        }
    );

});


