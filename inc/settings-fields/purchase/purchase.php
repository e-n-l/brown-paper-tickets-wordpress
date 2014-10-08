<?php

namespace BrownPaperTickets;

require_once( plugin_dir_path( __FILE__ ).'../bpt-option-class.php' );
require_once( plugin_dir_path( __FILE__ ).'/purchase-inputs.php' );
require_once( BptWordpress::plugin_root_dir() . 'lib/BptAPI/vendor/autoload.php');

use BrownPaperTickets\APIv2\ManageCart;
use BrownPaperTickets\APIv2\CartInfo;

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
				array( 'jquery', 'event_feed_js_' . $post->ID, 'ractive_js' ),
				VERSION,
				true
			);

			wp_localize_script(
				'bpt_purchase_tickets', 'bptPurchaseTickets', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'bpt-purchase-tickets' ),
					'templateUrl' => BptWordpress::plugin_root_url() . '/public/assets/templates/shopping-cart.html'
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
		$dev_id = ( get_option( '_bpt_dev_id' ) ? get_option( '_bpt_dev_id' ) : false );

		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'bpt-purchase-tickets' ) || ! $dev_id  ) {
			http_response_code( 401 );
			exit( 'Not authorized.' );
		}

		if ( empty( $_POST['stage'] ) ) {
			http_response_code( 400 );
			exit( 'No stage was sent.' );
		}

		header( 'Content-type: application/json' );

		$manage_cart = new ManageCart( $dev_id );
		$cart_info = new CartInfo( $dev_id );

		if ( $_POST['stage'] === 'addTickets' ) {

			$cart_id = $this->get_cart_id();

			if ( ! $cart_id ) {
				http_response_code( 400 );
				exit( 'Could not initialize cart.' );
			}

			$bpt_session = $_SESSION['bpt_purchase_tickets'];

			$bpt_session['tickets'] = $_POST['tickets']['prices'];

			if ( ! isset( $_POST['tickets'] ) || count( $_POST['tickets'] ) === 0 ) {
				http_response_code( 400 );
				exit( 'No tickets were sent.');
			}

			$params = array(
				'cartID' => $bpt_session['cart_id'],
				'prices' => $bpt_session['tickets'],
			);

			$add_tickets = $manage_cart->addPricesToCart( $params );

			if ( isset( $add_tickets['error'] ) ) {
				http_response_code( 400 );
				exit( json_encode( $add_tickets) );
			}

			if ( ! isset( $add_tickets['error'] ) ) {

				$response = $add_tickets;
				$response['ticketsInCart'] = $cart_info->getCartContents( $bpt_session['cart_id'] );
				$response['cartValue'] = $cart_info->getCartValue( $bpt_session['cart_id'] );
			}

			unset( $response['cartID'] );

			$_SESSION['bpt_purchase_tickets'] = $bpt_session;

			exit( json_encode( $response ) );
		}

		if ( $_POST['stage'] === 'removeTickets' ) {

		}

		if ( $_POST['stage'] === 'addShippingInfo' ) {
			exit( json_encode( $response ) );
		}

		if ( $_POST['stage'] === 'addBillingInfo' ) {
			exit( json_encode( $response ) );
		}

		if ( $_POST['stage'] === 'submitOrder' ) {
			exit( json_encode( $response ) );
		}

		if ( $_POST['stage'] === 'getCartInfo' ) {

			if ( ! session_id() ) {
				session_start();
			}

			if ( isset($_SESSION['bpt_purchase_tickets']['cart_id'] ) ) {
				$response['ticketsInCart'] = $cart_info->getCartContents( $_SESSION['bpt_purchase_tickets']['cart_id'] );
				$response['cartValue'] = $cart_info->getCartValue( $_SESSION['bpt_purchase_tickets']['cart_id'] );
			} else {
				$response['ticketsInCart'] = false;
				$response['cartValue'] = array(
					'cartValue' => '0.00',
				);
			}

			exit( json_encode( $response ) );
		}

		if ( $_POST['stage'] === 'checkout' ) {
			exit( json_encode( $response ) );
		}

		exit( json_encode( $response ) );

	}

	private function get_cart_id() {
		$dev_id    = ( get_option( '_bpt_dev_id' ) ? get_option( '_bpt_dev_id' ) : false );
		$manage_cart = new ManageCart( $dev_id );

		if ( ! session_id() ) {
			session_start();
		}

		$cart_expired = false;

		if ( isset( $_SESSION['bpt_purchase_tickets']['cart_created_at'] ) ) {
			$expiration = $_SESSION['bpt_purchase_tickets']['cart_created_at'] + 15 * 60;
			if ( time() >= $expiration ) {
				$cart_expired = true;
			}
		}

		if ( empty( $_SESSION['bpt_purchase_tickets'] ) || $cart_expired ) {

			$cart_id = $manage_cart->getCartID();

			if ( isset( $cart_id['error'] ) ) {
				return false;
			}

			$bpt_session = array(
				'cart_id' => $cart_id,
				'cart_created_at' => time(),
			);

			$_SESSION['bpt_purchase_tickets'] = $bpt_session;
		}

		return $_SESSION['bpt_purchase_tickets']['cart_id'];
	}
}