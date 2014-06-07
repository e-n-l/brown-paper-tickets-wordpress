(function($) {
    'use strict';
    var eventList,
        bptAPI;

    bptAPI = {
        loadEvents: function loadEvents() {


            $('div.bpt-loading').fadeIn();
            $.ajax(
                bptEventFeedAjax.ajaxurl,
                {
                    type: 'POST',
                    data: {
                        // wp ajax action
                        action : 'bpt_api_ajax',
                        // send the nonce along with the request
                        bptEventFeedNonce : bptEventFeedAjax.bptEventFeedNonce,
                        bptData: 'events',
                    },
                    accepts: 'json',
                    dataType: 'json'
                }
            )
            .always(function() {
                $('div.bpt-loading').hide();
            })
            .fail(function() {
                eventList.set({
                    error: 'Unknown Error'
                });
            })
            .done(function(data) {
                eventList.set({
                    bptEvents: data
                });
            })
            .always(function() {
                
            });
            
        }
    };

    $(document).ready(function(){

        eventList = new Ractive({
            el: '#bpt-event-list',
            template: '#bpt-event-template',
            data: {
                formatDate: function formatDate(newFormat, date) {
                    var singleDate = moment(date, 'YYYY-MM-DD');
                    return singleDate.format(newFormat);
                },
                formatTime: function formatTime(newFormat, time) {
                    var singleTime = moment(time, 'H:mm');
                    return singleTime.format(newFormat);
                },
                unescapeHTML: function unescapeHTML(html) {
                    return _.unescape(html);
                },
                formatPrice: function formatPrice(price, currency) {
                    var separator = '.',
                        priceArr;

                    if (currency === '€' ) {
                        separator = ',';
                    }

                    if (price === 0) {
                        return 'Free';
                    }

                    priceArr = price.toString().split('.');

                    if (!priceArr[1]) {
                        price = priceArr[0] + separator + '00';
                    } else {
                        price = priceArr[0] + separator + priceArr[1]; 
                    }

                    return currency + '' + price;
                }

            }
        });

        bptAPI.loadEvents();

        eventList.on({
            showFullDescription: function showFullDescription(event) {
                event.original.preventDefault();
                $(event.node).parent().parent().next('.bpt-event-full-description').toggle('hidden');
            }
        });

    });

})(jQuery);