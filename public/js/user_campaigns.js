/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 45);
/******/ })
/************************************************************************/
/******/ ({

/***/ 2:
/***/ (function(module, exports) {


// Message
window.showMessage = function (message) {
    var complete = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : function () {
        window.location.reload();
    };

    var modal = $('<div />').attr('id', 'custom-message').addClass('modal').append($('<div />').addClass('modal-content').html(message)).append($('<div />').addClass('modal-footer').append($('<a />').addClass('modal-action modal-close btn waves-effect waves-light grey').html('Close')));

    $('body').append(modal);

    $('#custom-message').modal({
        dismissible: true,
        complete: complete
    });

    $('#custom-message').modal('open');
};

// Errors
window.showError = function (errorMessage) {
    Materialize.toast(errorMessage, 10000);
};

window.showErrors = function (errorsList) {
    $.each(errorsList, function (field, error) {
        if (error.length) {
            showError(error);
        }
    });
};

// Process request
window.getSpinner = function () {
    return $('<i />').addClass('material-icons right spinner').html('cached');
};

window.startRequest = function (button) {
    var withSpinner = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;

    if (button.hasClass('btn-floating')) {
        button.find('.material-icons').hide();
    }

    button.prop('disabled', true);

    if (withSpinner === true) {
        button.append(getSpinner());
    }
};

window.stopRequest = function (button) {
    if (button.hasClass('btn-floating')) {
        button.find('.material-icons:hidden').show();
    }

    button.prop('disabled', false);

    $('.spinner').remove();
};

/***/ }),

/***/ 45:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(46);


/***/ }),

/***/ 46:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(2);

$(document).ready(function () {

    // Create Ad Campaign
    $('#campaign_form').on('submit', function (event) {
        event.preventDefault();

        var button = $(this).find('.btn');

        startRequest(button);

        var credentials = {
            name: $(this).find('input[name="name"]').val().trim(),
            objective: $(this).find('select[name="objective"]').val(),
            status: $(this).find('select[name="status"]').val()
        };

        axios.post('/user/campaign', qs.stringify(credentials)).then(function () {
            stopRequest(button);

            showMessage('Campaign successfully saved.');
        }).catch(function (error) {
            stopRequest(button);

            showErrors(error.response.data.errors);
        });
    });

    // Delete Ad Campaign
    $('.delete-campaign').on('click', function (event) {
        event.preventDefault();

        var button = $(this);

        startRequest(button);

        var credentials = {
            campaign: $(this).parents('tr').attr('id')
        };

        axios.post('/user/campaign/delete', qs.stringify(credentials)).then(function () {
            stopRequest(button);

            showMessage('Campaign successfully deleted.');
        }).catch(function (error) {
            stopRequest(button);

            showErrors(error.response.data.errors);
        });
    });
});

/***/ })

/******/ });