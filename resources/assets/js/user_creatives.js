require('./helpers');

$(document).ready(function () {

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

    // Delete Ad Creative
    $('.delete-creative').on(
        'click',
        function (event) {
            event.preventDefault();

            const button = $(this);

            startRequest(button);

            const credentials = {
                creative: $(this).parents('tr').attr('id'),
            };

            axios.post(
                '/user/creative/delete',
                qs.stringify(credentials)
            )
                .then(
                    () => {
                        stopRequest(button);

                        showMessage('Creative successfully deleted.');
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


