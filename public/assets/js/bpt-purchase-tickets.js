(function($) {
    'use strict';

    var manageCart = function(formParams) {
        console.log(formParams);
    };

    $(document).on('bptEventListLoaded', function(event) {
        var eventForm = $('.add-to-cart');
        eventForm.each(function(i, form) {
            form = $(form);

            var submitButton = form.find('.bpt-submit');

            submitButton.click(function(event) {

                event.preventDefault();
                manageCart(form.serializeArray());
            });
        });
    });

})(jQuery);