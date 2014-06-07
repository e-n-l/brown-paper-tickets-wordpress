<?php

namespace BrownPaperTickets;

use BrownPaperTickets\BPTFeed;

class BPTCalendarWidget extends \WP_Widget {


	public function __construct( ) {


		parent::__construct(
			'_bpt_widget_calendar',
			__( 'Brown Paper Tickets Calendar Widget', 'brown-paper-tickets-locale' ),
			array( 'description', __( 'Simple widget to display events in a calendar.', 'brown-paper-tickets-locale' ) )
		);


		if ( is_active_widget( false, false, $this->id_base, true ) ) {

			

		}
	}


	public function form( $instance ) {

		$title        = self::get_title( $instance );
		$display_type = self::get_display_type( $instance );
		$client_id    = self::get_client_id( $instance );

		?>

			<p>
				<label for="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label> 
				<input class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
				
				<label for="<?php esc_attr_e( $this->get_field_id( 'display_type' ) ); ?>"><?php _e( 'Display:' ); ?></label> 
				<select class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'display_type' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'display_type' ) ); ?>">
					<option value="<?php esc_attr_e( 'all_events' ); ?>" <?php selected( $display_type, 'all_events' ); ?>><?php  _e( 'All Your Events' ); ?></option>
					<option value="<?php esc_attr_e( 'producers_event' ); ?>" <?php selected( $display_type, 'producers_event' ); ?>><?php  _e( 'Another Producer\'s Events' ); ?></option>
				</select>

				<label for="<?php esc_attr_e( $this->get_field_id( 'client_id' ) ); ?>"><?php _e( 'Client ID:' ); ?></label>
				<input class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'client_id' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'client_id' ) ); ?>" type="text" value="<?php echo esc_attr( $client_id ); ?>">

			</p>
		<?php
	}

	public function widget( $args, $instance ) {

		$title        = apply_filters( 'widget_title', self::get_title( $instance ) );
		$display_type = self::get_display_type( $instance );
		$client_id    = self::get_client_id( $instance );

		if ( is_active_widget( false, false, $this->id_base, true ) ) {

			wp_enqueue_script( 'bpt_clndr_min_js', plugins_url( '/assets/js/clndr.min.js', dirname( __FILE__ ) ), array( 'underscore' ) ); 
			wp_enqueue_script( 'bpt_calendar_widget_js', plugins_url( '/assets/js/bpt-calendar-widget.js', dirname( __FILE__ ) ), array( 'jquery' ) );
			
			wp_localize_script(
				'bpt_calendar_widget_js', 'bptCalendarWidgetAjax', array(
					'ajaxurl'     => admin_url( 'admin-ajax.php' ),
					'bptNonce'    => wp_create_nonce( 'bpt-nonce' ),
					'displayType' => $display_type,
					'clientID'    => $client_id,
					'widgetID'    => $args['widget_id'],
				)
			);
		}

		echo wp_kses_post( $args['before_widget'] );

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}

		?>
			<div class="bpt-calendar-widget">

			</div>
		<?php

		echo esc_html(  __( 'Hello, World!', 'text_domain' ) );
		echo wp_kses_post( $args['after_widget'] );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title']        = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['display_type'] = ( ! empty( $new_instance['display_type'] ) ) ? strip_tags( $new_instance['display_type'] ) : '';
		$instance['client_id']    = ( ! empty( $new_instance['client_id'] ) ) ? strip_tags( $new_instance['client_id'] ) : '';

		return $instance;
	}


	private static function get_title( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {

			$title = $instance[ 'title' ];

		} else {

			$title = __( 'New title', 'brown-paper-tickets-locale' );
		}

		return $title;
	}

	private static function get_display_type( $instance ) {

		if ( isset( $instance['display_type'] ) ) {

			$display_type = $instance[ 'display_type' ];

		} else {

			$display_type = 'all_events';

		}

		return $display_type;
	}

	private static function get_client_id( $instance ) {
		if ( isset( $instance['client_id'] ) ) {

			$client_id = $instance[ 'client_id' ];

		} else {

			$client_id = null;

		}

		return $client_id;
	}


}

class BPTEventListWidget extends \WP_Widget {


	public function __construct() {
		parent::__construct(
			'_bpt_widget_event_list',
			__( 'Brown Paper Tickets Event List Widget', 'brown-paper-tickets-locale' ),
			array( 'description', __( 'Simple widget to display events in a calendar.', 'brown-paper-tickets-locale' ) )
		);
	}
}
