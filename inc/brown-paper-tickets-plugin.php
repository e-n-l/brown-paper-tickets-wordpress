<?php
/**
 * Brown Paper Tickets
 */

const VERSION = '0.1';

const PLUGIN_SLUG = 'brown_paper_tickets';

require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-api.php');
use BrownPaperTickets\APIv2\BrownPaperTicketsAPI;

class BrownPaperTicketsPlugin {

    protected $dev_id;
    protected $client_id;
    protected $menu_slug;

    protected static $instance = null;

    private function __construct() {

        $this->dev_id = get_option( 'dev_id' );
        $this->client_id = get_option( 'client_id' );
        $this->menu_slug = PLUGIN_SLUG.'_settings';

        if ( is_admin() ) {
            $this->load_admin();
        }

        $this->load_shared();
        $this->load_public();
    }

    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function get_plugin_slug() {
        return self::PLUGIN_SLUG;
    }

    public static function activate() {

    }

    public static function deactivate() {

    }

    public function load_admin() {
        add_action( 'admin_menu', array( $this, 'create_bpt_settings' ) );

    }

    public function load_public() {
        add_shortcode( 'list_events', array( $this, 'event_list_shortcode' ) );

    }

    public function load_shared() {
        add_action( 'wp_ajax_event_feed_ajax', array( $this, 'event_feed_ajax') );
        add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
    }

    public function load_admin_scripts() {
        wp_enqueue_script( 'event_feed_js', plugins_url( '/assets/js/event-feed.js', dirname(__FILE__) ), array( 'jquery' )); 
        wp_localize_script( 'event_feed_js', 'bptEventFeed', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'eventFeedNonce' => wp_create_nonce( 'event-feed-nonce' ))
        );
    }
    public function create_bpt_settings() {

        add_menu_page(
            'Brown Paper Tickets',
            'BPT Settings',
            'administrator',
            $this->menu_slug,
            array( $this, 'render_bpt_options_page')
        );

        $this->register_bpt_api_settings();
        $this->register_bpt_event_list_settings();
    }

    public function register_bpt_event_list_settings() {
        $section_title = 'Event List Settings';

        register_setting( $this->menu_slug, 'show_dates' );
        register_setting( $this->menu_slug, 'show_prices' );
    }

    /**
     * Register the API Credential Settings Fields
     */
    public function register_bpt_api_settings() {
        $section_title = 'API Credentials';

        register_setting( $this->menu_slug, 'dev_id');
        register_setting( $this->menu_slug, 'client_id');

        add_settings_section( $section_title, $section_title, array( $this, 'render_bpt_options_page' ), $this->menu_slug );

        add_settings_field( 'dev_id', 'Developer ID', array( $this, 'get_developer_id_input' ), $this->menu_slug, $section_title );
        add_settings_field( 'client_id', 'Client ID', array( $this, 'get_client_id_input' ), $this->menu_slug, $section_title );
    }

    public function event_list_shortcode( $atts ) {
        
    }

    public function render_bpt_options_page() {
        include_once( plugin_dir_path( __FILE__ ).'../admin/bpt-settings.php' );
    }


    /**
     * Settings Field Stuff
     */
    
    public function get_developer_id_input( ) {
        echo '<input name="dev_id" value="'.get_option('dev_id').'" type="text" />';
        echo '<span class="'.PLUGIN_SLUG.'_settings'.'_help">?</span>';
    }

    public function get_client_id_input( ) {
        echo '<input name="client_id" value="'.get_option('client_id').'" type="text" />';
        echo '<span class="'.PLUGIN_SLUG.'_settings'.'_help">?</span>';
    }


    /**
     * AJAX Stuff
     */
    public function event_feed_ajax() {

        $nonce = $_POST['eventFeedNonce'];

        if ( ! wp_verify_nonce( $nonce, 'event-feed-nonce' ) ) {
            exit('Error');
        }

        $events = new BrownPaperTicketsAPI($this->dev_id);

        $response = $events->get_json_events();

        exit($response);

    }

}