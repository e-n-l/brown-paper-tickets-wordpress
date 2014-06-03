<?php
/**
 * Brown Paper Tickets
 */

namespace BrownPaperTickets;

const VERSION = '0.1';

const PLUGIN_SLUG = 'brown_paper_tickets';

require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-api.php');
use BrownPaperTickets\BPTFeed;


require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-settings-fields.php');
use BrownPaperTickets\BPTSettingsFields;

class BPTPlugin {

    protected $dev_id;
    protected $client_id;
    protected $settings_fields;
    protected static $menu_slug;
    protected static $plugin_slug;
    protected static $plugin_version;
    protected static $instance = null;

    public function __construct() {

        $this->dev_id = get_option( '_bpt_dev_id' );
        $this->client_id = get_option( '_bpt_client_id' );
        $this->settings_fields = new BPTSettingsFields;

        self::$menu_slug = PLUGIN_SLUG.'_settings';

        self::$plugin_slug = PLUGIN_SLUG;

        self::$plugin_version = VERSION;

        $this->load_shared();
        $this->load_public();
        
        if ( is_admin() ) {
            $this->load_admin();
        }

    }

    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function get_plugin_slug() {
        return self::$plugin_slug;
    }

    public static function get_plugin_version() {
        return self::$plugin_version;
    }

    public static function get_menu_slug() {
        return self::$menu_slug;
    }

    public static function activate() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        add_option('_bpt_show_wizard', 'true');

        self::set_default_event_option_values();
    }

    public static function deactivate() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        self::remove_event_options();
    }

    public static function uninstall() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        check_admin_referer( 'bulk-plugins' );

        // Important: Check if the file is the one
        // that was registered during the uninstall hook.
        if ( __FILE__ != WP_UNINSTALL_PLUGIN ) {
            return;
        }

        self::remove_event_options();
    }

    public function load_admin() {
        add_action( 'admin_init',array( $this, 'bpt_show_wizard' ) );
        add_action( 'admin_menu', array( $this, 'create_bpt_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
    }

    public function load_public() {
        add_shortcode( 'list-event', array( $this, 'list_event_shortcode' ) );
        add_shortcode( 'list-events', array( $this, 'list_events_shortcode' ) );
        add_shortcode( 'list-events-links', array( $this, 'list_events_shortcode' ) );
        
        add_action( 'wp_enqueue_scripts', array( $this, 'load_public_scripts' ) );
    }

    public function load_shared() {
        add_action( 'wp_ajax_bpt_api_ajax', array( $this, 'bpt_api_ajax' ) );
    }

    public function load_admin_scripts($hook) {

        if ( $hook === 'toplevel_page_brown_paper_tickets_settings' ) {

            $this->load_ajax_required();

            wp_enqueue_style( 'bpt_admin_css', plugins_url( '/admin/assets/css/bpt-admin.css', dirname( __FILE__ ) ), false, VERSION );

            wp_enqueue_script( 'bpt_admin_js', plugins_url( '/admin/assets/js/bpt-admin.js', dirname( __FILE__ ) ), array( 'jquery' ) ); 
            wp_localize_script( 'bpt_admin_js', 'bptWP', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'bptNonce' => wp_create_nonce( 'bpt-nonce' ))
            );
        }
        
        if ( $hook === 'admin_page_brown_paper_tickets_settings_setup_wizard' ) {

            $this->load_ajax_required();
            
            wp_enqueue_style( 'bpt_admin_css', plugins_url( '/admin/assets/css/bpt-admin.css', dirname( __FILE__ ) ), false, VERSION );

            wp_enqueue_style( 'bpt_setup_wizard_css', plugins_url( '/admin/assets/css/bpt-setup-wizard.css', dirname( __FILE__ ) ), false, VERSION );
            
            wp_enqueue_script( 'bpt_setup_wizard_js', plugins_url( '/admin/assets/js/bpt-setup-wizard.js', dirname( __FILE__ ) ), array( 'jquery' ) ); 
            
            wp_localize_script( 'bpt_setup_wizard_js', 'bptSetupWizardAjax', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'bptSetupWizardNonce' => wp_create_nonce( 'bpt-setup-wizard-nonce' ))
            );
        }
    }

    public function load_ajax_required() {

        // Include Ractive Templates
        wp_enqueue_script( 'ractive_js', plugins_url( '/assets/js/ractive.js', dirname(__FILE__) ), array() );
        wp_enqueue_script( 'ractive_transitions_fade_js', plugins_url( '/assets/js/ractive-transitions-slide.js', dirname(__FILE__) ), array() );
        wp_enqueue_script( 'moment_with_langs_min', plugins_url( '/assets/js/moment-with-langs.min.js', dirname(__FILE__) ), array() );

    }

    public function load_public_scripts() {
        global $post;

        if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'list-events') ) {

            $this->load_ajax_required();

            wp_enqueue_script( 'event_feed_js', plugins_url( '/assets/js/event-feed.js', dirname(__FILE__) ), array( 'jquery', 'underscore' ) ); 
            wp_localize_script( 'event_feed_js', 'bptEventFeedAjax', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'bptEventFeedNonce' => wp_create_nonce( 'bpt-event-feed-nonce' ))
            );

            wp_enqueue_style( 'bpt_event_list_css', plugins_url( '/assets/css/bpt-event-list-shortcode.css', dirname( __FILE__ ) ), false, VERSION );
        }
    }

    public function create_bpt_settings() {

        add_menu_page(
            'Brown Paper Tickets',
            'BPT Settings',
            'administrator',
            self::$menu_slug,
            array( $this, 'render_bpt_options_page'),
            'dashicons-tickets'
        );

        add_submenu_page( 
            null,  //or 'options.php'
            'BPT Setup Wizard',
            'BPT Setup Wizard',
            'manage_options',
            self::$menu_slug . '_setup_wizard',
            array ( $this, 'render_bpt_setup_wizard_page')
        );


        $this->register_bpt_general_settings();
        $this->register_bpt_api_settings();
        $this->register_bpt_event_list_settings();
        $this->register_bpt_purchase_settings();
    }

    public function bpt_show_wizard() {

        if ( get_option('_bpt_show_wizard' ) === 'true' ) {

            update_option('_bpt_show_wizard', 'false');

            if( !isset( $_GET['activate-multi'] ) ) {

                wp_redirect('admin.php?page=brown_paper_tickets_settings_setup_wizard');

            }
        }
    }

    public function register_bpt_general_settings() {
        $setting_prefix = '_bpt_';
        $section_suffix = '_general';
        $section_title = 'General Settings';

        register_setting( self::$menu_slug, $setting_prefix . 'show_wizard' );
        register_setting( self::$menu_slug, $setting_prefix . 'cache_time' );

        add_settings_section( $section_title, $section_title, null, self::$menu_slug . $section_suffix );

        add_settings_field( $setting_prefix . 'cache_time', 'Cache Time', array( $this->settings_fields, 'get_cache_time_input' ), self::$menu_slug . $section_suffix, $section_title );
        
     }

    public function register_bpt_event_list_settings() {
        $setting_prefix = '_bpt_';
        $section_suffix = '_event';
        $section_title = 'Event Display Settings';
        $date_section_title = 'Date Display Settings';
        $price_section_title = 'Price Display Settings';

        // Event Settings
        register_setting( self::$menu_slug, $setting_prefix . 'show_full_description' );

        // Date Settings
        register_setting( self::$menu_slug, $setting_prefix . 'show_dates' );
        register_setting( self::$menu_slug, $setting_prefix . 'date_format' );
        register_setting( self::$menu_slug, $setting_prefix . 'time_format' );
        // custom_date_field is registered but it doesn't have a settings filed added.
        // That is added manually in the settings-fields.
        register_setting( self::$menu_slug, $setting_prefix . 'custom_date_format' );
        register_setting( self::$menu_slug, $setting_prefix . 'custom_time_format' );
        register_setting( self::$menu_slug, $setting_prefix . 'show_sold_out_dates' );
        register_setting( self::$menu_slug, $setting_prefix . 'show_past_dates' );
        register_setting( self::$menu_slug, $setting_prefix . 'show_end_time' );

        // Price Settings
        register_setting( self::$menu_slug, $setting_prefix . 'show_prices' );
        register_setting( self::$menu_slug, $setting_prefix . 'shipping_methods' );
        register_setting( self::$menu_slug, $setting_prefix . 'shipping_countries' );
        register_setting( self::$menu_slug, $setting_prefix . 'currency' );
        register_setting( self::$menu_slug, $setting_prefix . 'price_sort' );
        register_setting( self::$menu_slug, $setting_prefix . 'show_sold_out_prices' );


        add_settings_section( $section_title, $section_title, null, self::$menu_slug . $section_suffix );
        add_settings_section( $date_section_title, $date_section_title, null, self::$menu_slug . $section_suffix );
        add_settings_section( $price_section_title, $price_section_title, null, self::$menu_slug . $section_suffix );


        // Add the settings fields.
        // Event Fields
        add_settings_field( $setting_prefix . 'show_full_description', 'Display Full Description by Default', array( $this->settings_fields, 'get_show_full_description_input' ), self::$menu_slug . $section_suffix, $section_title );
        
        // Date Fields
        add_settings_field( $setting_prefix . 'show_dates', 'Display Dates', array ( $this->settings_fields, 'get_show_dates_input' ), self::$menu_slug . $section_suffix, $date_section_title );
        add_settings_field( $setting_prefix . 'show_past_dates', 'Display Past Dates', array ( $this->settings_fields, 'get_show_past_dates_input' ), self::$menu_slug . $section_suffix, $date_section_title );
        add_settings_field( $setting_prefix . 'show_end_time', 'Display Event End Time', array( $this->settings_fields, 'get_show_end_time_input' ), self::$menu_slug . $section_suffix, $date_section_title );
        add_settings_field( $setting_prefix . 'show_sold_out_dates', 'Display Sold Out Dates', array ( $this->settings_fields, 'get_show_sold_out_dates_input' ), self::$menu_slug . $section_suffix, $date_section_title );
        add_settings_field( $setting_prefix . 'date_format', 'Date Format', array( $this->settings_fields, 'get_date_format_input' ), self::$menu_slug . $section_suffix, $date_section_title );
        add_settings_field( $setting_prefix . 'time_format', 'Time Format', array( $this->settings_fields, 'get_time_format_input' ), self::$menu_slug . $section_suffix, $date_section_title );

        // Price Fields
        add_settings_field( $setting_prefix . 'show_prices', 'Display Prices', array( $this->settings_fields, 'get_show_prices_input' ), self::$menu_slug . $section_suffix, $price_section_title );
        add_settings_field( $setting_prefix . 'show_sold_out_prices', 'Display Sold Out Prices', array ( $this->settings_fields, 'get_show_sold_out_prices_input' ), self::$menu_slug . $section_suffix, $price_section_title );
        add_settings_field( $setting_prefix . 'shipping_methods', 'Shipping Methods', array( $this->settings_fields, 'get_shipping_methods_input' ), self::$menu_slug . $section_suffix, $price_section_title );
        add_settings_field( $setting_prefix . 'shipping_countries', 'Default Shipping Country', array( $this->settings_fields, 'get_shipping_countries_input' ), self::$menu_slug . $section_suffix, $price_section_title );
        add_settings_field( $setting_prefix . 'currency', 'Currency', array( $this->settings_fields, 'get_currency_input' ), self::$menu_slug . $section_suffix, $price_section_title );
        add_settings_field( $setting_prefix . 'price_sort', 'Price Sort', array( $this->settings_fields, 'get_price_sort_input' ), self::$menu_slug . $section_suffix, $price_section_title );
    }

    /**
     * Set the Default Values
     */
    private static function set_default_event_option_values() {

        $setting_prefix = '_bpt_';
        $section_suffix = '_event';
        $section_title = 'Event Display Settings';
        $date_section_title = 'Date Display Settings';
        $price_section_title = 'Price Display Settings';

        add_option( self::$menu_slug . $setting_prefix . 'show_full_description', 'false' );

        // Date Settings
        add_option( self::$menu_slug . $setting_prefix . 'show_dates', 'true' );
        add_option( self::$menu_slug . $setting_prefix . 'date_format', 'MMMM Do, YYYY' );
        add_option( self::$menu_slug . $setting_prefix . 'time_format', 'hh:mm A' );

        add_option( self::$menu_slug . $setting_prefix . 'show_sold_out_dates', 'false' );
        add_option( self::$menu_slug . $setting_prefix . 'show_past_dates', 'false' );
        add_option( self::$menu_slug . $setting_prefix . 'show_end_time', 'true' );

        // Price Settings
        add_option( self::$menu_slug . $setting_prefix . 'show_prices', 'true' );
        add_option( self::$menu_slug . $setting_prefix . 'shipping_methods', array('print_at_home', 'will_call' ) );
        add_option( self::$menu_slug . $setting_prefix . 'shipping_countries', 'United States' );
        add_option( self::$menu_slug . $setting_prefix . 'currency', 'usd' );
        add_option( self::$menu_slug . $setting_prefix . 'price_sort', 'value_asc' );
        add_option( self::$menu_slug . $setting_prefix . 'show_sold_out_prices', 'false' );
    }

    private static function remove_event_options() {

        $setting_prefix = '_bpt_';
        $section_suffix = '_event';
        $section_title = 'Event Display Settings';
        $date_section_title = 'Date Display Settings';
        $price_section_title = 'Price Display Settings';

        delete_option( self::$menu_slug . 'show_full_description' );

        // Date Settings
        delete_option( self::$menu_slug . 'show_dates' );
        delete_option( self::$menu_slug . 'date_format' );
        delete_option( self::$menu_slug . 'time_format' );

        delete_option( self::$menu_slug . 'show_sold_out_dates' );
        delete_option( self::$menu_slug . 'show_past_dates' );
        delete_option( self::$menu_slug . 'show_end_time' );

        // Price Settings
        delete_option( self::$menu_slug . 'show_prices' );
        delete_option( self::$menu_slug . 'shipping_methods' );
        delete_option( self::$menu_slug . 'shipping_countries' );
        delete_option( self::$menu_slug . 'currency' );
        delete_option( self::$menu_slug . 'price_sort' );
        delete_option( self::$menu_slug . 'show_sold_out_prices' );
    }


    /**
     * Register the API Credential Settings Fields
     *
     * Set the $section title variable to what you want the 
     */
    public function register_bpt_api_settings() {
        $setting_prefix = '_bpt_';
        $section_suffix = '_api';
        $section_title = 'API Credentials';

        register_setting( self::$menu_slug, $setting_prefix . 'dev_id');
        register_setting( self::$menu_slug, $setting_prefix . 'client_id');

        add_settings_section( $section_title, $section_title, array( $this, 'render_bpt_options_page' ), self::$menu_slug . $section_suffix );

        add_settings_field( $setting_prefix . 'dev_id', 'Developer ID', array( $this->settings_fields, 'get_developer_id_input' ), self::$menu_slug . $section_suffix, $section_title );
        add_settings_field( $setting_prefix . 'client_id', 'Client ID', array( $this->settings_fields, 'get_client_id_input' ), self::$menu_slug . $section_suffix, $section_title );
    }

    public function register_bpt_calendar_settings() {
        $setting_prefix = '_bpt_';
        $section_prefix = '_calendar';
        $section_title = 'Calendar Settings';

    }

    public function register_bpt_purchase_settings() {
        $setting_prefix = '_bpt_';
        $section_suffix = '_purchase';
        $section_title = 'Ticket Purchase Settings';

        register_setting( self::$menu_slug, $setting_prefix . 'allow_purchase' );

        add_settings_section( $section_title, $section_title, array( $this, 'render_bpt_options_page' ), self::$menu_slug . $section_suffix );

        add_settings_field( $setting_prefix . 'allow_purchase', 'Allow Purchase from Within Event List', array( $this->settings_fields, 'get_allow_purchase_input' ), self::$menu_slug . $section_suffix, $section_title );

    }

    public function list_event_shortcode( $atts ) {

        require_once( plugin_dir_path( __FILE__ ) . '../public/event-list-shortcode.php' );
    }

    public function list_events_shortcode( $atts ) {

        return require_once( plugin_dir_path( __FILE__ ) . '../public/event-list-shortcode.php' );
    }

    public function render_bpt_options_page() {
        require_once( plugin_dir_path( __FILE__ ) . '../admin/bpt-settings.php' );
    }

    public function render_bpt_setup_wizard_page() {
        require_once( plugin_dir_path( __FILE__ ) . '../admin/bpt-setup-wizard.php' );
    }

    /**
     * AJAX Stuff
     */
    public function bpt_api_ajax() {
        header('Content-type: application/json');

        if ( $_POST['bptData'] === 'account' ) {
            $nonce = $_POST['bptNonce'];

            if ( ! wp_verify_nonce( $nonce, 'bpt-nonce' ) ) {
                exit(
                    json_encode(
                        array(
                            'error' => 'Could not obtain account info.'
                        )
                    )
                );
            }



            $account = new BPTFeed;

            $response = $account->get_json_account();

            exit( $response );
        }

        if ( $_POST['bptData'] === 'events' ) {

            $nonce = $_POST['bptEventFeedNonce'];

            if ( ! wp_verify_nonce( $nonce, 'bpt-event-feed-nonce' ) ) {
                exit(
                    json_encode(
                        array(
                            'error' => 'Could not obtain events.'
                        )
                    )
                );
            }

            $events = new BPTFeed;

            $response = $events->get_json_events();

            exit($response);

        }

        if ( $_POST['bptData'] === 'accountTest' ) {

            $nonce = $_POST['bptSetupWizardNonce'];

            if ( ! wp_verify_nonce( $nonce, 'bpt-setup-wizard-nonce' ) ) {
                exit(
                    json_encode(
                        array(
                            'error' => 'Error.'
                        )
                    )
                );
            }

            $dev_id = $_POST['devID'];

            if ( $dev_id === null ) {
                exit(
                    json_encode(
                        array(
                            'error' => 'No Developer ID.'
                        )
                    )
                );
            }


            $client_id = $_POST['clientID'];

            if ( $client_id === null ) {
                exit(
                    json_encode(
                        array(
                            'error' => 'No Client ID.'
                        )
                    )
                );
            }

            $account = new BPTFeed;

            $response = $account->bpt_setup_wizard_test($dev_id, $client_id);

            exit($response);

        }


    }

}