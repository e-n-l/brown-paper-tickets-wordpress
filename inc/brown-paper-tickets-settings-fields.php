<?php

/**
 * Brown Paper Tickets Settings Fields HTML
 *
 * Here lies the callbacks for the add_settings_fields() function.
 */
namespace BrownPaperTickets;


require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-plugin.php');
use BrownPaperTickets\BPTPlugin;

class BPTSettingsFields {
       /**
     * Settings Field Stuff
     *
     * I don't like putting the html to be rendered here. I must
     * find a better way to do it.
     */


    public function get_developer_id_input() {
        ?>
        <div class="dev-id-wrapper">
            <input name="dev_id" value="<?php echo get_option('dev_id');?>" type="text">
            <div class="<?php echo BPTPlugin::get_menu_slug() ?>_help">
                <span>?</span>
                <p>
                    To access your developer ID, go here.
                </p>
            
            </div>
        </div>
        <?php
    }

    public function get_client_id_input() {
        ?>
        <div class="client-id-wrapper">
            <input name="client_id" value="<?php echo get_option('client_id');?>" type="text">
            <div class="<?php echo BPTPlugin::get_menu_slug() ?>_help">
                <span>?</span>
                <p>
                    This is your Brown Paper Tickets username.
                </p>
            
            </div>
        </div>
        <?php
    }

    public function get_show_dates_input() {
        ?>
        <div class="show-dates-wrapper">
            <input id="show-dates-true" name="show_dates" <?php echo $this->is_selected('true', 'show_dates', 'checked');?> value="true" type="radio" />
            <label for="show-dates-true">Yes</label>
            <input id="show-dates-false" name="show_dates" <?php echo $this->is_selected('false', 'show_dates', 'checked'); ?> value="false" type="radio" />
            <label for="show-dates-false">No</label>
            <div class="<?php echo BPTPlugin::get_menu_slug() ?>_help">
                <span>?</span>
                <p>
                    This option determines whether or not your event's prices will appear
                    in your event listing. 
                </p>
            
            </div>
        </div>

        <?php 
    }

    public function get_show_prices_input() {
    
        ?>
        <div class="show-prices-wrapper">
            <input id="show-prices-true" name="show_prices" <?php echo $this->is_selected('true', 'show_prices', 'checked');?> value="true" type="radio" />
            <label for="show-prices-true">Yes</label>
            <input id="show-prices-false" name="show_prices" <?php echo $this->is_selected('false', 'show_prices', 'checked'); ?> value="false" type="radio" />
            <label for="show-prices-false">No</label>
            <div class="<?php echo BPTPlugin::get_menu_slug(); ?>_help">
                <span>?</span>
                <p>
                    This option determines whether or not your event's prices will appear
                    in your event listing. 
                </p>
                
            </div>
        </div>

        <?php 
    }

    public function get_show_end_time_input() {

        ?>
        <div class="show-end-time-wrapper">
            <input id="show-end-time-true" name="show_end_time" <?php echo $this->is_selected('true', 'show_end_time', 'checked');?> value="true" type="radio" />
            <label for="show-end-time-true">Yes</label>
            <input id="show-end-time-false" name="show_end_time" <?php echo $this->is_selected('false', 'show_end_time', 'checked'); ?> value="false" type="radio" />
            <label for="show-end-time-false">No</label>
            <div class="<?php echo BPTPlugin::get_menu_slug(); ?>_help">
                <span>?</span>
                <p>
                    This option determines or not to show your event's end time.
                </p>
                
            </div>
        </div>

        <?php       
    }

    public function get_bpt_date_format_input() {
        ?>
        <div class="date-format-wrapper">
            <select id="date-format" name="date_format">
                <option value="us" <?php echo $this->is_selected( 'international', 'date_format', 'selected' ); ?> >DD-MM-YYYY HH:MM (24 Hour)</option>
                <option value="international" <?php echo $this->is_selected( 'us', 'date_format', 'selected' ); ?>>MM-DD-YYYY HH:MM (12 Hour)</option>
            </select>
            <div class="<?php echo BPTPlugin::get_menu_slug(); ?>_help">
                <span>?</span>
                <p>
                    This option determines the format you wish your dates to be displayed in.
                </p>
                
            </div>
        </div>
        <?php
    }

    public function get_shipping_methods_input() {
        ?>
        <div class="shipping-methods-wrapper">
            <label for="print-at-home">Print at Home</label>
            <input id="print-at-home" value="print_at_home" name="shipping_methods[]"  type="checkbox" <?php echo $this->is_selected( 'print_at_home', 'shipping_methods', 'checked' );?>/>
            
            <label for="will-call">Will-Call</label>
            <input id="will-call" value="will_call" name="shipping_methods[]"  type="checkbox" <?php echo $this->is_selected( 'will_call', 'shipping_methods', 'checked' );?>/>
            
            <label for="physical">Physical</label>
            <input id="physical" value="physical" name="shipping_methods[]"  type="checkbox" <?php echo $this->is_selected( 'physical', 'shipping_methods', 'checked' );?>/>
            
            <label for="mobile">Mobile</label>
            <input id="mobile" value="mobile" name="shipping_methods[]"  type="checkbox" <?php echo $this->is_selected( 'mobile', 'shipping_methods', 'checked' );?>/>
            
            <div class="<?php echo BPTPlugin::get_menu_slug(); ?>_help">
                <span>?</span>
                <p>
                    <strong>This plugin has no method to determine which shipping options are available for your events.</strong>
                    <strong>You must ensure that the options you select here are actually enabled on your event</strong>
                    Select the shipping methods you wish to display for your events.
                    <ul>
                        <li>Print at Home - This method allows ticket buyers to print their tickets at home. No Fee</li>
                        <li>Will Call - This method allows the ticket buyer to pick up their tickets at the box office prior to the show. No fee</li>
                        <li>Physical - This method will allow physical tickets to be shipped to the ticket buyer, fulfilled by Brown Paper Tickets. Fee. </li>
                        <li>Mobile - This method will send the user a text message with their ticket purchase allowing producers who use the Brown Paper Tickets Mobile Scanner App to scan tickets at the door.</li>
                    </ul>
                </p>
            </div>

        </div>
        <?php
    }

    public function get_shipping_countries_input() {
        ?>
        <div class="shipping-countries-wrapper">
            <label for="united-states">United States</label>
            <input id="united-states" value="us" name="shipping_countries[]"  type="checkbox" <?php echo $this->is_selected( 'us', 'shipping_countries', 'checked' );?>/>
            
            <label for="canada">Canada</label>
            <input id="canada" value="canada" name="shipping_countries[]"  type="checkbox" <?php echo $this->is_selected( 'canada', 'shipping_countries', 'checked' );?>/>
            
            <label for="uk">United Kingdom</label>
            <input id="uk" value="uk" name="shipping_countries[]"  type="checkbox" <?php echo $this->is_selected( 'uk', 'shipping_countries', 'checked' );?>/>
            
            <div class="<?php echo BPTPlugin::get_menu_slug(); ?>_help">
                <span>?</span>
                <p>
                   The countries you wish to allow shipping to and from.
                </p>
            </div>
        </div>
        <?php
    }

    public function get_currency_input() {
        ?>
        <div class="currency-wrapper">
            <select id="currency" name="currency">
                <option value="usd" <?php echo $this->is_selected( 'usd', 'currency', 'selected' );?>>USD $</option>
                <option value="cad" <?php echo $this->is_selected( 'cad', 'currency', 'selected' );?>>CAD $</option>
                <option value="gbp" <?php echo $this->is_selected( 'gbp', 'currency', 'selected' );?>>GBP £</option>
                <option value="eur" <?php echo $this->is_selected( 'eur', 'currency', 'selected' );?>>EUR €</option>
            </select>
            
            <div class="<?php echo BPTPlugin::get_menu_slug(); ?>_help">
                <span>?</span>
                <p>
                   The your event's prices should be displayed in.
                </p>
            </div>
        </div>
        <?php
    }

    public function get_price_sort_input() {
        ?>
        <div class="price-sort-wrapper">
            <select id="price-sort" name="price_sort">
                <option value="alpha_asc" <?php echo $this->is_selected( 'alpha_asc', 'price_sort', 'selected' );?>>Alphabetical</option>
                <option value="value_asc" <?php echo $this->is_selected( 'value_asc', 'price_sort', 'selected' );?>>Price Value - Low to High</option>
                <option value="value_desc" <?php echo $this->is_selected( 'value_desc', 'price_sort', 'selected' );?>>Price Value - High to Low</option>
            </select>
            
            <div class="<?php echo BPTPlugin::get_menu_slug(); ?>_help">
                <span>?</span>
                <p>
                   The order by which you wish to display prices.
                </p>
            </div>
        </div>
        <?php
    }

    public function get_show_full_description_input() {

        ?>
        <div class="show-full-description-wrapper">
            <input id="show-full-description-true" name="show_full_description" <?php echo $this->is_selected('true', 'show_full_description', 'checked');?> value="true" type="radio" />
            <label for="show-full-description-true">Yes</label>
            <input id="show-full-description-false" name="show_full_description" <?php echo $this->is_selected('false', 'show_full_description', 'checked'); ?> value="false" type="radio" />
            <label for="show-full-description-false">No</label>
            <div class="<?php echo BPTPlugin::get_menu_slug(); ?>_help">
                <span>?</span>
                <p>
                    This option determines or not to show the full description by default.
                </p>
                
            </div>
        </div>

        <?php       
    }

    public function get_show_past_dates_input() {

        ?>
        <div class="show-past-dates-wrapper">
            <input id="show-past-dates-true" name="show_past_dates" <?php echo $this->is_selected('true', 'show_past_dates', 'checked');?> value="true" type="radio" />
            <label for="show-past-dates-true">Yes</label>
            <input id="show-past-dates-false" name="show_past_dates" <?php echo $this->is_selected('false', 'show_past_dates', 'checked'); ?> value="false" type="radio" />
            <label for="show-past-dates-false">No</label>
            <div class="<?php echo BPTPlugin::get_menu_slug(); ?>_help">
                <span>?</span>
                <p>
                    If you would like to show past dates, select yes.
                </p>
                
            </div>
        </div>

        <?php       
    }

    public function get_show_sold_out_dates_input() {

        ?>
        <div class="show-sold-out-dates-wrapper">
            <input id="show-sold-out-dates-true" name="show_sold_out_dates" <?php echo $this->is_selected('true', 'show_sold_out_dates', 'checked');?> value="true" type="radio" />
            <label for="show-sold-out-dates-true">Yes</label>
            <input id="show-sold-out-dates-false" name="show_sold_out_dates" <?php echo $this->is_selected('false', 'show_sold_out_dates', 'checked'); ?> value="false" type="radio" />
            <label for="show-sold-out-dates-false">No</label>
            <div class="<?php echo BPTPlugin::get_menu_slug(); ?>_help">
                <span>?</span>
                <p>
                    If you would like to show sold out dates, select yes.
                </p>
                
            </div>
        </div>

        <?php       
    }

    public function get_show_sold_out_prices_input() {

        ?>
        <div class="show-sold-out-prices-wrapper">
            <input id="show-sold-out-prices-true" name="show_sold_out_prices" <?php echo $this->is_selected('true', 'show_sold_out_prices', 'checked');?> value="true" type="radio" />
            <label for="show-sold-out-prices-true">Yes</label>
            <input id="show-sold-out-prices-false" name="show_sold_out_prices" <?php echo $this->is_selected('false', 'show_sold_out_prices', 'checked'); ?> value="false" type="radio" />
            <label for="show-sold-out-prices-false">No</label>
            <div class="<?php echo BPTPlugin::get_menu_slug(); ?>_help">
                <span>?</span>
                <p>
                    If you would like to show sold out prices, select yes.
                </p>
            </div>
        </div>

        <?php       
    }

    public function is_selected( $value, $option, $type = null ) {

        $opt = get_option( $option );

        if ( is_array( $opt ) ) {

            foreach ( $opt as $single_opt) {

               if ( $value === $single_opt && $type === null ) {
                    return true;
                }

                if ( $value === $single_opt && $type === 'checked' ) {
                    return 'checked';
                }
                
                if ( $value === $single_opt && $type === 'selected' ) {
                    return 'selected="true"';
                }

            }

        }

        if ( $value === $opt && $type === null ) {
            return true;
        }

        if ( $value === $opt && $type === 'checked' ) {
            return 'checked';
        }
        
        if ( $value === $opt && $type === 'selected' ) {
            return 'selected="true"';
        }

        return false;
    }
}