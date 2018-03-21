
// Message
window.showMessage = (message, complete = () => {
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
window.showError = errorMessage => {
    Materialize.toast(errorMessage, 10000);
}

window.showErrors = errorsList => {
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
window.getSpinner = () => {
    return $('<i />')
        .addClass('material-icons right spinner')
        .html('cached')
}

window.startRequest = (button, withSpinner = true) => {
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

window.stopRequest = button => {
    if (button.hasClass('btn-floating')) {
        button.find('.material-icons:hidden').show();
    }

    button.prop('disabled', false);

    $('.spinner').remove();
}