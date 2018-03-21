require('./helpers');

$(document).ready(function () {

    // Create Ad Campaign
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

});


