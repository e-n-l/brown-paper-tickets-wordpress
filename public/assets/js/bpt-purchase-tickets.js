(function($) {
    'use strict';

    var ManageCart = function ManageCart(options) {

        var ajaxOptions = {
                url: bptPurchaseTickets.ajaxurl,
                type: 'POST',
                dataType: 'json'
            },
            shoppingCart = options.shoppingCart;


        if (!options.stage) {
            options.stage = 'getCartInfo';
        }

        if (!options.form) {
            return false;
        }

        if (options.stage === 'addTickets') {

            ajaxOptions.data = {
                action: 'bpt_purchase_tickets',
                tickets: parseTicketForm(options.form),
                nonce: bptPurchaseTickets.nonce,
                stage: 'addTickets'
            };

            console.log(ajaxOptions.data.tickets);
            $.ajax(ajaxOptions)
            .always(function(data) {

            })
            .done(function(data) {
                console.log(data);
                options.shoppingCart.set({
                    message: data.message,
                    ticketsInCart: data.ticketsInCart,
                    cartValue: data.cartValue
                });

                console.log(options.shoppingCart.data);
            })
            .fail(function(xhr) {
                options.shoppingCart.set({
                    error: xhr.responseText
                });
            });
        }

        if (options.stage === 'getCartInfo') {

            ajaxOptions.data = {
                action: 'bpt_purchase_tickets',
                nonce: bptPurchaseTickets.nonce,
                stage: 'getCartInfo'
            };

            $.ajax(ajaxOptions)
            .always()
            .fail()
            .done(function(data) {
                options.shoppingCart.set({
                    ticketsInCart: data.ticketsInCart,
                    cartValue: data.cartValue
                });

                console.log(options.shoppingCart.data);
            });
        }

        if (options.stage === 3) {

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

                var priceTd = price.parent(),
                    priceValue = priceTd.siblings('td.bpt-price-value').data('price-value'),
                    priceName = priceTd.siblings('td.bpt-price-name').data('price-name');

                tickets.prices[price.data('price-id')] = {
                    priceId: price.data('price-id'),
                    shippingMethod: shippingMethod,
                    quantity: price.val(),
                    value: priceValue,
                    name: priceName
                };
            });

            return tickets;
    };



    $(document).on('bptEventListLoaded', function(event) {
        var eventForms = $('.add-to-cart');

        eventForms.each(function(i, form) {
            var $form = $(form),
                submitButton = $form.find('.bpt-submit'),
                postID = $form.parent().parent().data('post-id');



            submitButton.click(function(event) {
                event.preventDefault();

                var params = {
                    stage: 'addTickets',
                    form: '',
                    shoppingCart: shoppingCart
                };

                manageCart(params);
            });
        });
    });

    $(document).ready(function() {
        var template,
            shoppingCart;

        $('body').append('<div id="bpt-shopping-cart"></div>');

        $.ajax({
            url: bptPurchaseTickets.templateUrl
        })
        .fail(function(xhr) {
            template = 'Sorry, the shopping cart could not be loaded.';
        })
        .done(function(data) {
            template = $(data).html();
        })
        .always(function() {

            shoppingCart = new Ractive({
                el: '#bpt-shopping-cart',
                template: template,
                data: {
                    ticketsInCart: []
                }
            });

            var params = {
                stage: 'getCartInfo',
                shoppingCart: shoppingCart
            };

            manageCart(params);
        });
    });

})(jQuery);