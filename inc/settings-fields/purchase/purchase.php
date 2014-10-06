<?php

namespace BrownPaperTickets;
require_once( plugin_dir_path( __FILE__ ).'../bpt-option-class.php' );
require_once( plugin_dir_path( __FILE__ ).'/purchase-inputs.php' );
require_once( BptWordpress::plugin_root_dir() . 'lib/BptAPI/vendor/autoload.php');

use BrownPaperTickets\APIv2\ManageCart;

class PurchaseSettings extends BptOption {

	public function register_settings() {
		register_setting( self::$menu_slug, self::$setting_prefix . 'purchase_settings' );
	}

	public function register_sections() {
		$section_title = 'Purchase Settings';
		$section_suffix = '_purchase';

		$inputs = new PurchaseInputs();

		add_settings_section(
			$section_title,
			$section_title,
			array( $inputs, 'section' ),
			self::$menu_slug . $section_suffix
		);

		add_settings_field(
			self::$setting_prefix . 'enable_sales', // The ID of the input.
			'Enable Sales', // The title of the field.
			array( $inputs, 'enable_sales' ), // Event HTML callback
			self::$menu_slug . $section_suffix, // The settings page.
			$section_title // The section that the field will be rendered in.
		);
	}

	public function load_public_js( $hook ) {
		global $post;

		$options = get_option( '_bpt_purchase_settings' );
		$sales_enabled = ( isset( $options['enable_sales'] ) ? $options['enable_sales'] : false );

		if ( is_a( $post, 'WP_Post' ) && ( has_shortcode( $post->post_content, 'list-events' ) || has_shortcode( $post->post_content, 'list_events' ) ) && $sales_enabled ) {


			wp_enqueue_script(
				'bpt_purchase_tickets',
				BptWordpress::plugin_root_url() . '/public/assets/js/bpt-purchase-tickets.js',
				array( 'jquery', 'event_feed_js_' . $post->ID ),
				VERSION,
				true
			);

			wp_localize_script(
				'bpt_purchase_tickets', 'bptPurchaseTickets', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'bpt-purchase-tickets' ),
				)
			);
		}
	}

	public function set_default_setting_values() {

		$purchase_settings = get_option(
			self::$setting_prefix . 'purchase_settings'
		);

		if ( ! $purchase_settings ) {

			$settings = array(
				'enable_sales' => false,
			);

			update_option( self::$menu_slug . self::$setting_prefix . 'purchase_settings', $settings );
		}
	}

	public function remove_setting_values() {
		delete_option( self::$menu_slug . self::$setting_prefix . 'purchase_settings' );
	}

	public function load_public_ajax_actions() {
		add_action( 'wp_ajax_bpt_purchase_tickets', array( $this, 'purchase_tickets' ) );
		add_action( 'wp_ajax_nopriv_bpt_purchase_tickets', array( $this, 'purchase_tickets' ) );
	}

	public function purchase_tickets() {
		$response = array();

		$nonce = ( isset( $_POST['nonce'] ) ? esc_html( $_POST['nonce'] ): false );
		$dev_id    = ( get_option( '_bpt_dev_id' ) ? get_option( '_bpt_dev_id' ) : false );

		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'bpt-purchase-tickets' ) ) {
			http_response_code( 401 );
			exit( 'Not authorized' );
		}

		if ( empty( $_POST['stage'] ) || ! $dev_id  ) {
			http_response_code( 400 );
			exit( 'No stage was sent.' );
		}

		header( 'Content-type: application/json' );

		$manage_cart = new ManageCart( $dev_id );

		if ( ! session_id() ) {
			session_start();
		}

		if ( empty( $_SESSION['bpt_purchase_tickets'] ) ) {
			$_SESSION['bpt_purchase_tickets'] = array( 'cart_id' => $manage_cart->getCartID() );
			$response['cartID'] = $_SESSION['bpt_purchase_tickets']['cart_id'];
		}

		$response['tickets'] = $_POST['tickets'];

		$response['cartID'] = $_SESSION['bpt_purchase_tickets']['cart_id'];

		exit( json_encode( $response ) );
		// $manage_cart = new ManageCart;
	}
}