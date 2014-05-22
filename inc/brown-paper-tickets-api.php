<?php

namespace BrownPaperTickets;

require_once( plugin_dir_path( __FILE__ ).'/../lib/BptAPI/vendor/autoload.php');
use BrownPaperTickets\APIv2\EventInfo;
use BrownPaperTickets\APIv2\AccountInfo;
/**
 * This handles all of the event info formatting using the data obtained
 * via the BPT APIv2 PHP class.
 */

class BPTFeed {

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

        if ( strtotime($date['dateStart'] ) < time() ) {
            return true;
        }
        return false;
    }

    public function date_is_live($date) {

        if ( $date['live'] === false ) {
            return false;
        }

        return true;
    }

    public function date_is_sold_out($date) {

        if ( $this->date_has_past( $date ) === true && strtotime( $date['dateStart'] ) >= time() ) {
            return false;
        }

        return true;
    }

    /**
     * Price Methods
     */

    public function price_is_live($price) {

        if ( $price['live'] === false) {
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

    public function remove_bad_dates($eventList) {

        foreach ( $eventList as $eventIndex => $event ) {

            foreach ($event['dates'] as $dateIndex => $date) {
                
                if ( $this->date_has_past( $date ) || !$this->date_is_live( $date ) ) {

                    unset( $event['dates'][$dateIndex] );
                }

            }

            $event['dates'] = array_values( $event['dates'] );

            $eventList[$eventIndex] = $event;
        }

        return $eventList;
    }

    public function remove_bad_prices( $eventList ) {
        foreach ( $eventList as $eventIndex => $event ) {

            foreach ( $event['dates'] as $dateIndex => $date ) {
                
                foreach ( $date['prices'] as $priceIndex => $price ) {

                    if ( $this->price_is_live( $price ) === false ) {
                        unset( $date['prices'][$priceIndex] );
                    }

                }
                
                $date['prices'] = array_values( $date['prices'] );

                $event['dates'][$dateIndex] = $date;
            }

            $eventList[$eventIndex] = $event;
        }

        return $eventList;
    }

    public function get_json_events() {
        /**
         * Get Event List Setting Options
         * 
         */
        $dates = get_option('show_dates');
        $prices = get_option('show_prices');
        $show_past_dates = get_option('show_past_dates');
        $show_sold_out_dates = get_option('show_sold_out_dates');
        $show_sold_out_prices = get_option('show_sold_out_prices');

        $events = new EventInfo($this->dev_id);

        $eventList = $events->getEvents($this->client_id, null, $dates, $prices);

        if ( $dates === 'true' && $show_past_dates === 'false' ) {
            $eventList = $this->remove_bad_dates( $eventList );
        }

        if ( $prices === 'true ' && $show_sold_out_prices === 'false' ) {
            $eventList = $this->remove_bad_prices( $eventList );   
        }
        
        return json_encode( $eventList );

    }

    public function get_json_account_info() {

        $account = new AccountInfo($this->dev_id);

        return json_encode( $account->getAccount($this->client_id ) );
    }
}