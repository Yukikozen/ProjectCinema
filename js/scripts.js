$(document).ready(function () {
    $("#Header").load("header.html");
    $("#Footer").load("footer.html");
});

$('#myModal').on('shown.bs.modal', function () {
    $('#myInput').trigger('focus');
});

$('.seat').click(function () {
    if ($(this).attr('src') === 'images/bookings/green-box-hi.png') {
        $(this).attr('src', 'images/bookings/blue-box-hi.png');
    } else {
        $(this).attr('src', 'images/bookings/green-box-hi.png');
    }
});

$(document).ready(function () {

    $('#userinfo').validate({ // initialize the plugin
        rules: {
            "firstName": {
                required: true,
                minlength: 2
            },
            "lastName": {
                required: true,
                minlength: 2
            },
            "email": {
                required: true,
                email: true
            },
            "contact": {
                required: true,
                number: true,
                maxlength: 9
            }
        },
        messages: {
            "firstName": {
                required: "First Name is a required input",
                minlength: jQuery.validator.format("First Name must be at least 2 characters long")
            },
            "lastName": {
                required: "Last Name is a required input",
                minlength: jQuery.validator.format("Last Name must be at least 2 characters long")
            },
            "email": {
                required: "Email is a required input",
                email: jQuery.validator.format("Please enter a valid email")
            },
            "postal": {
                required: "Postal Code is a required input",
                number: jQuery.validator.format("Postal Code must not contain letters")
            },
            "address": {
                required: "Address is a required input"
            },
            "contact": {
                required: "Contact Number is a required input",
                number: jQuery.validator.format("Contact Number must not contain letters"),
                maxlength: jQuery.validator.format("Contact Number cannot exceed 9 numbers")
            }
        }
     
    });

});

$(document).ready(function () {

    $('#paymentinfo').validate({ // initialize the plugin
        rules: {
            "cardname": {
                required: true,
                minlength: 3
            },
            "cardnumber": {
                required: true,
                minlength: 16,
                number: true
            },
            "cvv": {
                required: true,
                number: true,
                maxlength: 3
            }

        },
        messages: {
            "cardname": {
                required: "Card Name is a required input",
                minlength: jQuery.validator.format("Card Name must be at least 3 characters long")
            },
            "cardnumber": {
                required: "Last Name is a required input",
                number: jQuery.validator.format("Card Number cannot contain letters"),
                minlength: jQuery.validator.format("Card Number must be at least 16 characters long")
            },
            "cvv": {
                required: "CVV is a required input",
                number: jQuery.validator.format("CVV cannot contain letters"),
                maxlength: jQuery.validator.format("CVV cannot exceed 3 numbers")
            }
        }
     
    });

});

var count = 0;

$('#btn-checkout').on('click', function(e) {
    window.count = 0;
    $(':checkbox').each(function() {
        if ($(this).is(':checked') && !$(this).is(':disabled')) {
            window.count++;
        }
    });
    if (window.count != 0) {
        $('#checkoutModal').modal('show');
        $('#error-no-seats').hide();
        $('#modal-ticket-count').text(window.count);
        $('#modal-ticket-price').text(window.count * 7);
    } else {
        $('#error-no-seats').show();
    }
});

$(document).ready(function() {
    $('#error-no-seats').hide();
    $("#checkout-form").submit(function(e) {
        if (window.count == 0) {
            e.preventDefault();
        }
        return true;
    });
});
