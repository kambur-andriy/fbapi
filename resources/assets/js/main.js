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

const clearErrors = () => {
    $('.invalid').removeClass('invalid');
    $('.invalid').next('label').attr('data-error', '');
}

// Forms
const clearForm = form => {
    form.find('input[type="text"]').val('');
    form.find('input[type="email"]').val('');
    form.find('input[type="password"]').val('');
    form.find('textarea').val('');
    form.find('input[type="radio"]:eq(0)').prop('checked', true);

    clearErrors();
}

$(document).ready(function () {

    // Paralax
    $('.parallax').parallax();

    // Log in
    $('#login_form').on(
        'submit',
        function (event) {
            event.preventDefault();

            clearErrors();

            const credentials = {
                email: $(this).find('input[name="email"]').val(),
                password: $(this).find('input[name="password"]').val()
            };

            axios.post(
                '/account/login',
                qs.stringify(credentials)
            )
                .then(
                    response => {

                        window.location.pathname = response.data.userPage;

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

    // Sing up
    $('#register_form').on(
        'submit',
        function (event) {
            event.preventDefault();

            clearErrors();

            const credentials = {
                email: $(this).find('input[name="email"]').val(),
                password: $(this).find('input[name="password"]').val(),
                password_confirmation: $(this).find('input[name="password_confirmation"]').val()
            };

            axios.post(
                '/account/register',
                qs.stringify(credentials)
            )
                .then(
                    () => {
                        showMessage('Account successfully created');
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

    // Forgot password
    $('#password_form').on(
        'submit',
        function (event) {
            event.preventDefault();

            clearErrors();

            const credentials = {
                email: $(this).find('input[name="email"]').val(),
            };

            axios.post(
                '/account/reset-password',
                qs.stringify(credentials)
            )
                .then(
                    () => {
                        showMessage('We sent you an email. To reset your account password please click the link in the email.');
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

    // Change password
    $('#reset_password_form').on(
        'submit',
        function (event) {
            event.preventDefault();

            clearErrors();

            const credentials = {
                token: $(this).find('input[name="reset_token"]').val(),
                password: $(this).find('input[name="password"]').val(),
                password_confirmation: $(this).find('input[name="password_confirmation"]').val()
            };

            axios.post(
                '/account/save-password',
                qs.stringify(credentials)
            )
                .then(
                    response => {
                        window.location = response.data.nextStep
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

    // Contact us
    $('#contact_form').on(
        'submit',
        function (event) {
            event.preventDefault();

            clearErrors();

            axios.post(
                '/contact-us',
                qs.stringify(
                    {
                        'name': $(this).find('input[name="name"]').val(),
                        'email': $(this).find('input[name="email"]').val(),
                        'message': $(this).find('textarea[name="message"]').val()
                    }
                )
            )
                .then(
                    () => {
                        clearForm($('#contact_form'));

                        showMessage('Your message successfully sent.');
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


