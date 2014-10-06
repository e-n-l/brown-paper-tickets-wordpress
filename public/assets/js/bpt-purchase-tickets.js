(function($) {
    'use strict';

    var manageCart = function(params) {
        var ajaxOptions = {
            url: bptPurchaseTickets.ajaxurl,
            type: 'POST',
            dataType: 'json'
        };
        if (!params.stage) {
            params.stage = 1;
        }

        if (!params.form) {
            return false;
        }

        if (params.stage === 1) {
            console.log(params);
            ajaxOptions.data = {
                action: 'bpt_purchase_tickets',
                tickets: parseTicketForm(params.form),
                nonce: bptPurchaseTickets.nonce,
                stage: 1
            };

            $.ajax(ajaxOptions)
            .always(function(data) {

            })
            .done(function(data) {
                console.log(data);
            })
            .fail(function(xhr) {

            });
        }

        if (params.stage === 2) {
        }

        if (params.stage === 3) {

        }
    };

    var parseTicketForm = function($form) {
        var eventId = $form.data('event-id'),
            prices = $form.find('select.bpt-price-qty'),
            shippingMethod = $form.find('select.bpt-shipping-method').val(),
            tickets = {
                eventId: eventId,
                prices: {}
            };

            prices.each(function(i, price) {
                price = $(price);

                tickets.prices[price.data('price-id')] = {
                    shippingMethod: shippingMethod,
                    quantity: price.val()
                };
            });

            return tickets;
    };

    $(document).on('bptEventListLoaded', function(event) {
        var eventForm = $('.add-to-cart');
        eventForm.each(function(i, form) {
            var $form = $(form);

            var submitButton = $form.find('.bpt-submit');

            submitButton.click(function(event) {
                event.preventDefault();

                var params = {
                    stage: 1,
                    form: $form
                };
                manageCart(params);
            });
        });
    });

})(jQuery);