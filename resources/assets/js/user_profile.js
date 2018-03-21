require('./helpers');

$(document).ready(function () {

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

});