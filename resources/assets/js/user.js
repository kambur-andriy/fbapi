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
    Materialize.toast(errorMessage, 10000);
}

const showErrors = errorsList => {
    $.each(
        errorsList,
        (field, error) => {
            if (error.length) {
                showError(error);
            }
        }
    )
}

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
                        showErrors(error.response.data.errors)
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
                '/user/campaign',
                qs.stringify(credentials)
            )
                .then(
                    response => {
                        showMessage('Campaign successfully saved.');

                        $('ad_campaigns tbody').prepend(
                            $('<tr />')
                                .append(
                                    $('<td />').html(response.data.name)
                                )
                                .append(
                                    $('<td />').html(response.data.objective)
                                )
                                .append(
                                    $('<td />').html(response.data.status)
                                )
                        )
                    }
                )
                .catch(
                    error => {
                        showErrors(error.response.data.errors)
                    }
                )
        }
    );

    // Ad Set
    $('#set_form').on(
        'submit',
        function (event) {
            event.preventDefault();

            const credentials = {
                name: $(this).find('input[name="name"]').val().trim(),
                start_date: $(this).find('input[name="start_date"]').val(),
                end_date: $(this).find('input[name="end_date"]').val(),
                bid_amount: $(this).find('input[name="bid_amount"]').val().trim(),
                daily_budget: $(this).find('input[name="daily_budget"]').val().trim(),
                optimization_goal: $(this).find('select[name="optimization_goal"]').val(),
                billing_event: $(this).find('select[name="billing_event"]').val(),
                interest: $(this).find('input[name="interest"]').val().trim(),
                campaign: $(this).find('select[name="campaign"]').val(),
            };

            axios.post(
                '/user/set',
                qs.stringify(credentials)
            )
                .then(
                    response => {
                        showMessage('Set successfully created.');

                        $('ad_sets tbody').prepend(
                            $('<tr />')
                                .append(
                                    $('<td />').html(response.data.name)
                                )
                                .append(
                                    $('<td />').html(response.data.daily_budget)
                                )
                                .append(
                                    $('<td />').html('')
                                )
                                .append(
                                    $('<td />').html(response.data.status)
                                )
                        )
                    }
                )
                .catch(
                    error => {
                        showErrors(error.response.data.errors)
                    }
                )
        }
    );

});


