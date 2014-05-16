(function($) {
    $(document).ready(function(){
        $('#get-events').click(function(){
            $.post(
                bptEventFeed.ajaxurl,
                {
                    // wp ajax action
                    action : 'event_feed_ajax',
                    // vars
                    title : $('input[name=title]').val(),
                    // send the nonce along with the request
                    eventFeedNonce : bptEventFeed.eventFeedNonce
                },
                function( response ) {
                    console.log( response );
                }
            );
            return false;
        }); 
    });
})(jQuery);