(function($) {
    'use strict';
    var BptAPI;

    BptAPI = function BptAPI() {
        this.loadCalendar();
    };

    BptAPI.prototype.eventList = [];

    BptAPI.prototype.loadCalendar = function loadCalendar() {

        if ( bptCalendarWidgetAjax.cliendID ) {

            return this.getProducersEvents();

        } else {

            return this.getEvents();
        
        }


    };

     BptAPI.prototype.getEvents = function getEvents() {

        $.ajax(
            bptCalendarWidgetAjax.ajaxurl,
            {
                type: 'POST',
                data: {
                    // wp ajax action
                    action : 'bpt_get_calendar_events',
                    // varsx
                    // send the nonce along with the request
                    bptNonce : bptCalendarWidgetAjax.bptNonce,
                    widgetID: bptCalendarWidgetAjax.widgetID,
                },
                accepts: 'json',
                dataType: 'json'

            }
        ).done(function(data) {

            return data;
            
        }).fail(function(data) {
           return  data;
        });
    };

    BptAPI.prototype.getProducersEvents = function getProducersEvents() {

        var self = this;

        $.ajax(
            bptCalendarWidgetAjax.ajaxurl,
            {
                type: 'POST',
                data: {
                    // wp ajax action
                    action : 'bpt_get_calendar_events',
                    // varsx
                    // send the nonce along with the request
                    bptNonce : bptCalendarWidgetAjax.bptNonce,
                    widgetID: bptCalendarWidgetAjax.widgetID,
                },
                accepts: 'json',
                dataType: 'json'

            }
        ).done(function(data) {

            self.setEvents(data);
            
        }).fail(function(data) {

           failData = data;

        });

    };

    BptAPI.prototype.setEvents = function setEvents(events) {

        console.log('settingEvents');

        this.eventList = events;
    };


    
    $(document).ready(function() {

        var bptAPI = new BptAPI();


        $('.bpt-calendar-widget').clndr({
            events: bptAPI.events,
            clickEvents: {
                click: function(target) {
                    console.log(this.options.events);
                }
            }
        });

    });
})(jQuery);