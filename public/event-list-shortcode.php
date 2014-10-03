<?php
namespace BrownPaperTickets;

require_once( plugin_dir_path( __FILE__ ).'../lib/bptWordpress.php' );

use BrownPaperTickets\BptWordpress;

$_bpt_show_prices = get_option( '_bpt_show_prices' );
$_bpt_show_dates = get_option( '_bpt_show_dates' );
$_bpt_show_full_description = get_option( '_bpt_show_full_description' );
$_bpt_show_location_after_description = get_option( '_bpt_show_location_after_description' );
$_bpt_shipping_countries    = get_option( '_bpt_shipping_countries' );
$_bpt_shipping_methods = get_option( '_bpt_shipping_methods' );
$_bpt_currency = get_option( '_bpt_currency' );
$_bpt_date_format = esc_html( get_option( '_bpt_date_format' ) );
$_bpt_time_format = esc_html( get_option( '_bpt_time_format' ) );
$_bpt_show_end_time = get_option( '_bpt_show_end_time' );
$_bpt_event_list_style = get_option( '_bpt_event_list_style' );

//$_bpt_event_list_template = get_option( '_bpt_show_event_list_template' );

if ( $_bpt_date_format === 'custom' ) {
	$_bpt_date_format = esc_html( get_option( '_bpt_custom_date_format' ) );
}

if ( $_bpt_time_format === 'custom' ) {
	$_bpt_time_format = esc_html( get_option( '_bpt_custom_time_format' ) );
}

if ( $_bpt_currency === 'cad' ) {
	$_bpt_currency = 'CAD$ ';
}

if ( $_bpt_currency === 'usd' ) {
	$_bpt_currency = '$';
}

if ( $_bpt_currency === 'gbp' ) {
	$_bpt_currency = '£';
}

if ( $_bpt_currency === 'eur' ) {
	$_bpt_currency = '€';
}

$countries = BptWordpress::get_country_list();

if ( $_bpt_event_list_style ) {
	$use_style = ( isset( $_bpt_event_list_style['use_style'] ) ? true : false );

	if ( $use_style ) {
		$css = '<style type="text/css">' . esc_html( $_bpt_event_list_style['custom_css'] ) . '</style>';
	}
}

ob_start();

if ( isset( $css ) ) {
	$allowed_html = array(
		'style' => array(
			'type' => array(),
		),
	);

	echo wp_kses( $css, $allowed_html );
}

?>
<div class="bpt-loading-<?php esc_attr_e( $post->ID );?> hidden">
	Loading Events
	<br />
	<img src="<?php echo esc_url( plugins_url( 'public/assets/img/loading.gif', dirname( __FILE__ ) ) ); ?>">
</div>

<div id="bpt-event-list-<?php esc_attr_e( $post->ID );?>" class="bpt-event-list" data-post-id="<?php esc_attr_e( $post->ID );?>">

</div>
<script type="text/html" id="bpt-event-template">


{{ #bptEvents }}
	{{ ^.error }}
	<div intro="slide" class="bpt-event bpt-default-theme">
		<h2 class="bpt-event-title">{{{ unescapeHTML(title) }}}</h2>


		<?php if ( $_bpt_show_location_after_description === 'false' ) { ?>

			<div class="bpt-event-location">
				<div class="address1">{{ address1 }}</div>
				<div class="address2">{{ address2 }}</div>
				<div>
					<span class="city">{{ city }}</span>, <span class="state">{{ state }}</span> <span class="zip">{{ zip }}</span>
				</div>
			</div>

		<?php } ?>

		<div class="bpt-event-short-description">
			<p>
				{{ shortDescription }}
				<br />
			</p>
		</div>

		<?php if ( $_bpt_show_full_description === 'false' ) { ?>
		<p>
			<a href="#" class="bpt-show-full-description" on-click="showFullDescription">Show Full Description</a>
		</p>
		<div class="bpt-event-full-description hidden">
			<p>{{{ unescapeHTML(fullDescription) }}}</p>
		</div>

		<?php } else { ?>

		<div class="bpt-event-full-description">
			<p>{{{ unescapeHTML(fullDescription) }}}</p>
		</div>

		<?php }

	if ( $_bpt_show_location_after_description === 'true' ) { ?>

			<div class="bpt-event-location">
				<div class="address1">{{ address1 }}</div>
				<div class="address2">{{ address2 }}</div>
				<div>
					<span class="city">{{ city }}</span>, <span class="state">{{ state }}</span> <span class="zip">{{ zip }}</span>
				</div>
			</div>

		<?php }

	if ( $_bpt_show_dates === 'true' ) { ?>
			<form data-event-id="{{ id }}" data-event-title="{{ title }}" method ="post" class="add-to-cart" action="https://www.brownpapertickets.com/addtocart.html" target="_blank">
				<input type="hidden" name="event_id" value="{{ id }}" />
				<div class="event-dates">
					<label for="dates-{{ id }}">Select a Date:</label>
					<select class="bpt-date-select" id="dates-{{ id }}" value="{{ .selectedDate }}">
					{{ #dates }}
						<option class="event-date" value="{{ . }}" >
							{{ formatDate( '<?php esc_attr_e( $_bpt_date_format ); ?>', dateStart ) }}
							{{ formatTime( '<?php esc_attr_e( $_bpt_time_format ); ?>', timeStart ) }}
							<?php echo ( $_bpt_show_end_time === 'true' ? 'to {{ formatTime( \'' . $_bpt_time_format . '\', timeEnd ) }}' : '' ); ?>
						</option>
					{{ /dates }}
					</select>
				</div>
				<fieldset>
				{{ #selectedDate }}
					<input name="date_id" value="{{ id }}" type="hidden">
					<table id="price-list-{{ id }}" class="bpt-event-list-prices">
					<tr>
						<th>Price Name</th>
						<th>Price Value</th>
						<th>Quantity</th>
					</tr>
					{{ #prices }}
					<tr data-price-id="{{ id }}" class="{{ isHidden(hidden) }}" >
						<td class="bpt-price-name">
						{{ name }}
						{{ #hidden }}
							<?php echo ( BptWordpress::is_user_an_admin() ? '<br/><a href="#" on-click="unhidePrice" class="bpt-unhide-price" data-price-name="{{ name }}" data-price-id="{{ id }}">(Display Price)</a>' : '' ); ?>
						{{ /hidden}}
						
						{{ ^hidden }}
							<?php echo ( BptWordpress::is_user_an_admin() ? '<br/><a href="#" on-click="hidePrice" class="bpt-hide-price" data-price-name="{{ name }}" data-price-id="{{ id }}">(Hide Price)</a>' : '' ); ?>
						{{ /hidden }}

						</td>
						<td class="bpt-price-value">{{ formatPrice(value, '<?php esc_attr_e( $_bpt_currency ); ?>' ) }}</td>
						<td>
							<select class="bpt-shipping-qty" name="price_{{ id }}">

		<?php
		$shipping_incr = 0;

		while ( $shipping_incr <= 50 ) {
			echo '<option value="' . $shipping_incr . '">' . $shipping_incr . '</option>';
			$shipping_incr++;
		}
		?>
							</select>
						</td>
					</tr>
					{{ / }}
					</table>
					<div class="shipping-info">
						<label class="bpt-shipping-method" for="shipping_{{ id }}">Delivery Method</label>
						<select class="bpt-shipping-method" id="shipping_{{ id }}" name="shipping_{{ id }}">
		<?php
		foreach ( $_bpt_shipping_methods as $shipping_method ) {
			switch ( $shipping_method ) {
				case 'print_at_home':
					echo '<option value="5">Print-At-Home (No Additional Fee)</option>';
					break;

				case 'will_call':
					echo '<option value="4">Will-Call (No additional fee!)</option>';
					break;

				case 'physical':
					echo '<option value="1">Physical Tickets - USPS 1st Class (No additional fee!)</option>';
					echo '<option value="2">Physical Tickets - USPS Priority Mail ($5.05)</option>';
					echo '<option value="3">Physical Tickets - USPS Express Mail ($18.11)</option>';
					break;
			}
		}
		?>
						</select>
						<br />
						<label class="bpt-shipping-country" class="bpt-shipping-country-label" for="country-id-{{ id }}">Delivery Country</label>
						<select class="bpt-shipping-country" id="country-id-{{   id }}" name="country_id">
		<?php
								$country_incr = 1;
		foreach ( $countries as $country ) {

			if ( $country === 'Azores' ) {
				echo '<option value="243"' . ($country === get_option( '_bpt_shipping_countries' ) ? 'selected' : '' ) . '>' . $country . '</option>';

				continue;
			}

				echo '<option value="' . $country_incr . '"' . ($country === get_option( '_bpt_shipping_countries' ) ? 'selected' : '' ) . '>' . $country . '</option>';
				$country_incr++;
		}
		?>
						</select>
					</div>
				{{ / }}
				</fieldset>
				<div class="bpt-event-footer">
					<div class="bpt-add-to-cart">
						<button class="bpt-submit" type="submit">Add to Cart</button>
						<span class="bpt-cc-logos">
							<img src="<?php echo esc_url( plugins_url( 'public/assets/img/visa_icon.png', __DIR__ ) ); ?>" />
							<img src="<?php echo esc_url( plugins_url( 'public/assets/img/mc_icon.png', __DIR__ ) ); ?>" />
							<img src="<?php echo esc_url( plugins_url( 'public/assets/img/discover_icon.png', __DIR__ ) ); ?>" />
							<img src="<?php echo esc_url( plugins_url( 'public/assets/img/amex_icon.png', __DIR__ ) ); ?>" />
						</span>
					</div>
				</div>
			</form>
		<?php } ?>
		<div class="bpt-powered-by">
			<a href="http://www.brownpapertickets.com/event/{{ id }}" target="_blank"><span>View Event on </span><img src="<?php echo esc_url( plugins_url( 'public/assets/img/bpt-footer-logo.png', __DIR__ ) ); ?>" /></a>
		<div>
	</div>
	{{ /.error }}
{{ /bptEvents }}


{{ #bptError }}
	<div intro="slide" class="bpt-event bpt-default-theme">
	<h2>Sorry, an error has occured while loading events.</h2>
	<p>{{ error }}</p>
{{ /bptError }}

</script>
<?php
	$event_list = ob_get_clean();

	return $event_list;
?>
