require('./helpers');

$(document).ready(function () {

    // Ad
    $('#ad_form').on(
        'submit',
        function (event) {
            event.preventDefault();

            const button = $(this).find('.btn');

            startRequest(button);

            const credentials = {
                set: $(this).find('select[name="set"]').val(),
                creative: $(this).find('select[name="creative"]').val(),
                name: $(this).find('input[name="name"]').val().trim(),
                status: $(this).find('select[name="status"]').val(),
            };

            axios.post(
                '/user/ad',
                qs.stringify(credentials)
            )
                .then(
                    () => {
                        showMessage('Ad successfully created.');

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

    // Delete Ad
    $('.delete-ad').on(
        'click',
        function (event) {
            event.preventDefault();

            const button = $(this);

            startRequest(button);

            const credentials = {
                ad: $(this).parents('tr').attr('id'),
            };

            axios.post(
                '/user/ad/delete',
                qs.stringify(credentials)
            )
                .then(
                    () => {
                        stopRequest(button);

                        showMessage('Ad successfully deleted.');
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


