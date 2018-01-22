require('./bootstrap');

// Message
const showMessage = (message, complete = () => {
    window.location.reload()
}) => {
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
                        .addClass('modal-action modal-close btn waves-effect waves-light grey')
                        .html('Close')
                )
        );

    $('body').append(modal);

    $('#custom-message').modal(
        {
            dismissible: true,
            complete: complete
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

// Process request
const getSpinner = () => {
    return $('<i />')
        .addClass('material-icons right spinner')
        .html('cached')
}

const startRequest = (button, withSpinner = true) => {
    if (button.hasClass('btn-floating')) {
        button.find('.material-icons').hide();
    }

    button.prop('disabled', true);

    if (withSpinner === true) {
        button.append(
            getSpinner()
        );
    }
}

const stopRequest = button => {
    if (button.hasClass('btn-floating')) {
        button.find('.material-icons:hidden').show();
    }

    button.prop('disabled', false);

    $('.spinner').remove();
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

            const button = $(this).find('.btn');

            startRequest(button, false);

            const credentials = {
                ad_account_id: $(this).find('input[name="ad_account_id"]').val(),
                first_name: $(this).find('input[name="first_name"]').val(),
                last_name: $(this).find('input[name="last_name"]').val(),
            };

            axios.post(
                '/user/profile',
                qs.stringify(credentials)
            )
                .then(
                    () => {
                        stopRequest(button);

                        showMessage('Profile successfully saved');
                    }
                )
                .catch(
                    error => {
                        stopRequest(button);

                        showErrors(error.response.data.errors)
                    }
                )
        }
    );

    // Create Ad Company
    $('#campaign_form').on(
        'submit',
        function (event) {
            event.preventDefault();

            const button = $(this).find('.btn');

            startRequest(button);

            const credentials = {
                name: $(this).find('input[name="name"]').val().trim(),
                objective: $(this).find('select[name="objective"]').val(),
                status: $(this).find('select[name="status"]').val()
            };

            axios.post(
                '/user/campaign',
                qs.stringify(credentials)
            )
                .then(
                    () => {
                        stopRequest(button);

                        showMessage('Campaign successfully saved.');
                    }
                )
                .catch(
                    error => {
                        stopRequest(button);

                        showErrors(error.response.data.errors)
                    }
                )
        }
    );

    // Delete Ad Campaign
    $('.delete-campaign').on(
        'click',
        function (event) {
            event.preventDefault();

            const button = $(this);

            startRequest(button);

            const credentials = {
                campaign: $(this).parents('tr').attr('id'),
            };

            axios.post(
                '/user/campaign/delete',
                qs.stringify(credentials)
            )
                .then(
                    () => {
                        stopRequest(button);

                        showMessage('Campaign successfully deleted.');
                    }
                )
                .catch(
                    error => {
                        stopRequest(button);

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

            const button = $(this).find('.btn');

            startRequest(button);

            const credentials = {
                campaign: $(this).find('select[name="campaign"]').val(),
                name: $(this).find('input[name="name"]').val().trim(),
                start_date: $(this).find('input[name="start_date"]').val(),
                end_date: $(this).find('input[name="end_date"]').val(),
                bid_amount: $(this).find('input[name="bid_amount"]').val().trim(),
                daily_budget: $(this).find('input[name="daily_budget"]').val().trim(),
                optimization_goal: $(this).find('select[name="optimization_goal"]').val(),
                billing_event: $(this).find('select[name="billing_event"]').val(),
                interest: $(this).find('input[name="interest"]').val().trim(),
                status: $(this).find('select[name="status"]').val(),
            };

            axios.post(
                '/user/set',
                qs.stringify(credentials)
            )
                .then(
                    () => {
                        showMessage('Set successfully created.');

                        stopRequest(button);
                    }
                )
                .catch(
                    error => {
                        showErrors(error.response.data.errors)

                        stopRequest(button);
                    }
                )
        }
    );

    // Delete Ad Campaign
    $('.delete-set').on(
        'click',
        function (event) {
            event.preventDefault();

            const button = $(this);

            startRequest(button);

            const credentials = {
                set: $(this).parents('tr').attr('id'),
            };

            axios.post(
                '/user/set/delete',
                qs.stringify(credentials)
            )
                .then(
                    () => {
                        stopRequest(button);

                        showMessage('Set successfully deleted.');
                    }
                )
                .catch(
                    error => {
                        stopRequest(button);

                        showErrors(error.response.data.errors)
                    }
                )
        }
    );

    // Ad Creative
    $('#creative_form').on(
        'submit',
        function (event) {
            event.preventDefault();

            const credentials = new FormData();

            credentials.append('name', $(this).find('input[name="name"]').val().trim());
            credentials.append('page', $(this).find('input[name="page"]').val().trim());
            credentials.append('link', $(this).find('input[name="link"]').val().trim());
            credentials.append('message', $(this).find('input[name="message"]').val().trim());
            credentials.append('image_file', $('input[name="image_file"]')[0].files[0]);

            axios.post(
                '/user/creative',
                credentials
            )
                .then(
                    () => {
                        showMessage('Creative successfully created.');
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


