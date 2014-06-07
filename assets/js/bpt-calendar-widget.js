(function($) {
    'use strict';
    var BptAPI = function BptAPI() {
        
        var eventData,
            failData;

        this.loadCalendar = function setOptions() {

            if ( bptCalendarWidgetAjax.cliendID ) {

                this.getProducerEvents();

            } else {
                this.getEvents();
            }


        };

        this.getEvents = function getEvents() {
            $.ajax(
                bptCalendarWidgetAjax.ajaxurl,
                {
                    type: 'POST',
                    data: {
                        // wp ajax action
                        action : 'bpt_api_ajax',
                        // varsx
                        // send the nonce along with the request
                        bptNonce : bptCalendarWidgetAjax.bptNonce,
                        bptAction: 'get_events',
                        bptWidgetInstance: bptCalendarWidgetAjax.bptWidgetID,
                    },
                    accepts: 'json',
                    dataType: 'json'

                }
            ).done(function(data) {
                eventData = data;
                
            }).fail(function(data) {
               failData = data;
            });
        };

        this.getProducerEvents = function getProducerEvents() {
            $.ajax(
                bptCalendarWidgetAjax.ajaxurl,
                {
                    type: 'POST',
                    data: {
                        // wp ajax action
                        action : 'bpt_api_ajax',
                        // varsx
                        // send the nonce along with the request
                        bptNonce : bptCalendarWidgetAjax.bptNonce,
                        bptAction: 'get_producer_events',
                        bptWidgetInstance: bptCalendarWidgetAjax.bptWidgetID,
                    },
                    accepts: 'json',
                    dataType: 'json'

                }
            ).done(function(data) {
                eventData = data;
                
            }).fail(function(data) {
               failData = data;
            });
        };

        
        this.loadCalendar();
    };

    $(document).ready(function() {
        
        var bptCalendar = new BptAPI();

    });
})(jQuery);