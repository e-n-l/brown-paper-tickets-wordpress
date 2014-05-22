(function($) {
    $(document).ready(function(){

        bptAPI = {
            loadEvents: function loadEvents() {
                $('div.bpt-loading').fadeIn();
                $.post(
                    bptEventFeed.ajaxurl,
                    {
                        // wp ajax action
                        action : 'bpt_feed_ajax',
                        // vars
                        title : $('input[name=title]').val(),
                        // send the nonce along with the request
                        bptFeedNonce : bptEventFeed.bptFeedNonce,
                        bptData: 'events'
                    }
                )
                .always(function() {
                    $('div.bpt-loading').hide()
                })
                .fail(function() {

                })
                .done(function(data) {
                    console.log(data);
                    bptAPI.renderData(data);
                })
                .always(function() {
                    
                });
            },
            renderData: function renderData(data) {

                for (var i = 0; i < data.length; i++) {
                    var singleEvent = $('.bpt-single-event.hidden').clone(true, true).removeClass('hidden'),
                        title = singleEvent.children('h2.bpt-event-title')
                        shortDescription = singleEvent.children('.bpt-event-short-description'),
                        fullDescription = singleEvent.children('.bpt-event-full-description');

                    title.text(data[i].title);

                    shortDescription.text(data[i].shortDescription);
                    fullDescription.html(_.unescape(data[i].fullDescription));

                    singleEvent.appendTo('.bpt-events');
                }
            }
        }

        $('a.bpt-show-full-description').click(function(e) {
            e.preventDefault();

            $(this).next('.bpt-event-full-description').toggle('hidden');
        });

        bptAPI.loadEvents();
    });
})(jQuery);