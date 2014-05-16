<?php

namespace BrownPaperTickets\APIv2;

require_once( plugin_dir_path( __FILE__ ).'/../lib/BptAPI/vendor/autoload.php');
/**
 * This handles all of the event info formatting using the data obtained
 * via the BPT APIv2 PHP class.
 */

class BrownPaperTicketsAPI {

    protected $dev_id;
    protected $client_id;

    public function __construct() {

        $this->dev_id = get_option('dev_id');
        $this->client_id = get_option('client_id');

    }

    public function get_all_events_for_account() {

        $events = new EventInfo($this->dev_id);

        return $events->getEvents( $this->client_id, null, true, true );
    }

    /**
     * View Rendering Functions
     */

    public function get_event_html() {

        $events = $this->get_all_events_for_account();

        if ( isset( $events['result'] ) and $events['result'] == 'fail' ) {
            return $this->generate_error_html($events);
        }

        return $this->generate_event_html($events);
    }

    public function generate_event_html($events) {
        require_once( plugin_dir_path( __FILE__ ).'/../public/event-partial.php' );
    }

    public function generate_date_html($dates, $event_id) {
        require_once( plugin_dir_path( __FILE__ ).'/../public/date-partial.php' );
    }

    public function generate_price_html($prices) {
        require_once( plugin_dir_path( __FILE__ ).'/../public/price-partial.php' );
    }

    public function generate_error_html($error) {
        require_once( plugin_dir_path( __FILE__ ).'/../public/error-partial.php' );
    }

    /**
     * Event Methods
     */
    
    public function get_event_count() {
        $events = new EventInfo($this->dev_id);

        return count( $events->getEvents( $this->client_id ) );
    }
    /**
     * Date Methods
     * 
     */
    public function date_has_past($date) {

        if ( !$date['live'] && strtotime($date['dateStart'] ) < time() ) {
            return false;
        }
        return true;
    }

    public function date_is_sold_out($date) {
        if ( !$this->date_had_past( $date ) && strtotime( $date['dateStart'] ) >= time() ) {
            return false;
        }

        return true;
    }

    /**
     * Price Methods
     */

    public function price_live($price) {
        if (!$price['live']) {
            return false;
        }

        return true;
    }

    /**
     * Conversion Methods
     */
    /**
     * Convert Date. Converst the Date to a human readable date.
     * 
     * @param  string $date The String that needs to be formatted.
     * @return string       The formatted date string.
     */
    public function convert_date($date) {
        return strftime("%B %e, %Y", strtotime( $date ) );
    }

    /**
     * Convert Time. Converst the Time to a human readable date.
     * @param  string $time The string to be formated.
     * @return string       The formatted string.
     */
    public function convert_time( $date ) {
        return strftime( "%l:%M%p", strtotime( $date ) ) ;
    }


    public function get_json_events( $dates = false, $prices = false ) {

        $events = new EventInfo($this->dev_id);
        

        return json_encode( $events->getEvents($this->client_id, null, $dates, $prices) );

    }
}