require('./helpers');

$(document).ready(function () {

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
                status: $(this).find('select[name="status"]').val(),

                age_min: $(this).find('select[name="age_min"]').val(),
                age_max: $(this).find('select[name="age_max"]').val(),
                gender: $(this).find('input[name="gender"]:checked').val(),
                interest: $(this).find('input[name="interest"]').val().trim(),
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

    // Delete Ad Set
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

});


