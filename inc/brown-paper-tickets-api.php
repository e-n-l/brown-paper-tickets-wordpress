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

        $this->dev_id = get_option('_bpt_dev_id');
        $this->client_id = get_option('_bpt_client_id');

    }

    public function get_all_events_for_account() {

        $events = new EventInfo($this->dev_id);

        return $events->getEvents( $this->client_id, null, true, true );
    }


    /**
     * JSON Functions
     */

    public function get_json_events() {
        /**
         * Get Event List Setting Options
         * 
         */
        $_bpt_dates = get_option('_bpt_show_dates');
        $_bpt_prices = get_option('_bpt_show_prices');
        $_bpt_show_past_dates = get_option('_bpt_show_past_dates');
        $_bpt_show_sold_out_dates = get_option('_bpt_show_sold_out_dates');
        $_bpt_show_sold_out_prices = get_option('_bpt_show_sold_out_prices');

        $_bpt_events = new EventInfo($this->dev_id);

        $_bpt_eventList = $_bpt_events->getEvents($this->client_id, null, $_bpt_dates, $_bpt_prices);

        $_bpt_eventList = $this->sort_prices( $_bpt_eventList );

        if ( $_bpt_dates === 'true' && $_bpt_show_past_dates === 'false' ) {
            $_bpt_eventList = $this->remove_bad_dates( $_bpt_eventList );
        }

        if ( $_bpt_prices === 'true ' && $_bpt_show_sold_out_prices === 'false' ) {
            $_bpt_eventList = $this->remove_bad_prices( $_bpt_eventList );   
        }
        
        return json_encode( $_bpt_eventList );

    }

    public function get_json_account_info() {

        $_bpt_account = new AccountInfo($this->dev_id);

        return json_encode( $account->getAccount($this->client_id ) );
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

    protected function remove_bad_dates($_bpt_eventList) {

        foreach ( $_bpt_eventList as $eventIndex => $event ) {

            foreach ($event['dates'] as $dateIndex => $date) {
                
                if ( $this->date_has_past( $date ) || !$this->date_is_live( $date ) ) {

                    unset( $event['dates'][$dateIndex] );
                }

            }

            $event['dates'] = array_values( $event['dates'] );

            $_bpt_eventList[$eventIndex] = $event;
        }

        return $_bpt_eventList;
    }

    protected function remove_bad_prices( $_bpt_eventList ) {
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

            $_bpt_eventList[$eventIndex] = $event;
        }

        return $_bpt_eventList;
    }

    protected function sort_prices( $_bpt_eventList ) {
        $sort_method = get_option('_bpt_price_sort');

        foreach ( $_bpt_eventList as $eventIndex => $event ) {

            foreach ( $event['dates'] as $dateIndex => $date ) {
                
                if ($sort_method === 'alpha_asc') {
                    $date['prices'] = $this->sortByKey($date['prices'], 'name', true);
                }
                
                if ($sort_method === 'alpha_desc') {
                    $date['prices'] = $this->sortByKey($date['prices'], 'name');
                }

                if ($sort_method === 'value_desc') {
                    $date['prices'] = $this->sortByKey($date['prices'], 'value', true);
                }

                if ($sort_method === 'value_asc') {
                    $date['prices'] = $this->sortByKey($date['prices'], 'value');
                }

                $event['dates'][$dateIndex] = $date;
            }

            $_bpt_eventList[$eventIndex] = $event;
        }

        return $_bpt_eventList;
    }

    protected function sortByKey($array, $key, $reverse = false) {

        //Loop through and get the values of our specified key
        foreach ( $array as $k => $v ) {
            $b[] = strtolower( $v[$key] );
        }

        if ( $reverse === false ) {

            asort( $b );

        } else {

            arsort( $b );

        }
        
        foreach ( $b as $k => $v ) {
            $c[] = $array[$k];
        }

        return $c;

    }

}