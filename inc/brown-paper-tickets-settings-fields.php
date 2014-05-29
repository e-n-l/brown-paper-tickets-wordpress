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
            <input name="_bpt_dev_id" value="<?php echo get_option('_bpt_dev_id');?>" type="text">
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
            <input name="_bpt_client_id" value="<?php echo get_option('_bpt_client_id');?>" type="text">
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
            <input id="show-dates-true" name="_bpt_show_dates" <?php echo $this->is_selected('true', '_bpt_show_dates', 'checked');?> value="true" type="radio" />
            <label for="show-dates-true">Yes</label>
            <input id="show-dates-false" name="_bpt_show_dates" <?php echo $this->is_selected('false', '_bpt_show_dates', 'checked'); ?> value="false" type="radio" />
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
            <input id="show-prices-true" name="_bpt_show_prices" <?php echo $this->is_selected('true', '_bpt_show_prices', 'checked');?> value="true" type="radio" />
            <label for="show-prices-true">Yes</label>
            <input id="show-prices-false" name="_bpt_show_prices" <?php echo $this->is_selected('false', '_bpt_show_prices', 'checked'); ?> value="false" type="radio" />
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
            <input id="show-end-time-true" name="_bpt_show_end_time" <?php echo $this->is_selected('true', '_bpt_show_end_time', 'checked');?> value="true" type="radio" />
            <label for="show-end-time-true">Yes</label>
            <input id="show-end-time-false" name="_bpt_show_end_time" <?php echo $this->is_selected('false', '_bpt_show_end_time', 'checked'); ?> value="false" type="radio" />
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

    public function get_date_format_input() {
            $date_formats = array(
                'DD-MM-YYYY' => '28-01-2016',
                'MM-DD-YYYY' => '28 January, 2016',
                'MM-DD-YYYY' => '01-28-2016',
                'MMMM Do, YYYY' => 'January 28th, 2016',
                'MMMM Do, YYYY' => 'January 28th, 2016',
                'ddd MMM Do, YYYY' => 'Thu Jan 28th, 2016',
                'dddd MMM Do, YYYY' => 'Thursday January 28th, 2016',
                'custom' => 'Custom Format'
            );
        ?>
        <div class="date-format-wrapper">
            <select id="date-format" name="_bpt_date_format">
        <?php 

            foreach ( $date_formats as $format => $description ) {
        ?>
                <option value="<?php echo $format; ?>" <?php echo $this->is_selected( $format, '_bpt_date_format', 'selected' ); ?> ><?php echo $description ; ?></option>
        <?php

            }
        ?>
            </select>
            <input class="hidden" id="custom-date-format-input" name="_bpt_custom_date_format" type="text" value="<?php echo get_option('_bpt_custom_date_format'); ?>" />
            <div class="<?php echo BPTPlugin::get_menu_slug(); ?>_help">
                <span>?</span>
                <p>
                    This option determines the format you wish your dates to be displayed in.
                </p>
                
            </div>
        </div>
        <?php
    }
    public function get_time_format_input() {
            $time_formats = array(
                'HH:mm' => '24:30',
                'hh:mm A' => '12:30 PM',
                'hh:mm a' => '12:30 pm',
                'custom' => 'Custom Format'
            );
        ?>
        <div class="time-format-wrapper">
            <select id="time-format" name="_bpt_time_format">
        <?php 

            foreach ( $time_formats as $format => $description ) {
        ?>
                <option value="<?php echo $format; ?>" <?php echo $this->is_selected( $format, '_bpt_time_format', 'selected' ); ?> ><?php echo $description ; ?></option>
        <?php

            }
        ?>
            </select>
            <input class="hidden" id="custom-time-format-input" name="_bpt_custom_time_format" type="text" value="<?php echo get_option('_bpt_custom_time_format'); ?>" />
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
            <input id="print-at-home" value="print_at_home" name="_bpt_shipping_methods[]"  type="checkbox" <?php echo $this->is_selected( 'print_at_home', '_bpt_shipping_methods', 'checked' );?>/>
            
            <label for="will-call">Will-Call</label>
            <input id="will-call" value="will_call" name="_bpt_shipping_methods[]"  type="checkbox" <?php echo $this->is_selected( 'will_call', '_bpt_shipping_methods', 'checked' );?>/>
            
            <label for="physical">Physical</label>
            <input id="physical" value="physical" name="_bpt_shipping_methods[]"  type="checkbox" <?php echo $this->is_selected( 'physical', '_bpt_shipping_methods', 'checked' );?>/>
            
            <label for="mobile">Mobile</label>
            <input id="mobile" value="mobile" name="_bpt_shipping_methods[]"  type="checkbox" <?php echo $this->is_selected( 'mobile', '_bpt_shipping_methods', 'checked' );?>/>
            
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

            $countries = array(
                'Afghanistan',
                'Aland Islands',
                'Albania',
                'Algeria',
                'American Samoa',
                'Andorra',
                'Angola',
                'Anguilla',
                'Antarctica',
                'Antigua And Barbuda',
                'Argentina',
                'Armenia',
                'Aruba',
                'Australia',
                'Austria',
                'Azerbaijan',
                'Azores',
                'Bahamas',
                'Bahrain',
                'Bangladesh',
                'Barbados',
                'Belarus',
                'Belgium',
                'Belize',
                'Benin',
                'Bermuda',
                'Bhutan',
                'Bolivia',
                'Bosnia And Herzegovina',
                'Botswana',
                'Bouvet Island',
                'Brazil',
                'British Indian Ocean Territory',
                'Brunei Darussalam',
                'Bulgaria',
                'Burkina Faso',
                'Burundi',
                'Cambodia',
                'Cameroon',
                'Canada',
                'Cape Verde',
                'Cayman Islands',
                'Central African Republic',
                'Chad',
                'Chile',
                'China',
                'Christmas Island',
                'Cocos (keeling) Islands',
                'Colombia',
                'Comoros',
                'Congo',
                'Congo, The Democratic Republic Of The',
                'Cook Islands',
                'Costa Rica',
                'Cote Divoire',
                'Croatia',
                'Cyprus',
                'Czech Republic',
                'Denmark',
                'Djibouti',
                'Dominica',
                'Dominican Republic',
                'Ecuador',
                'Egypt',
                'El Salvador',
                'Equatorial Guinea',
                'Eritrea',
                'Estonia',
                'Ethiopia',
                'Falkland Islands',
                'Faroe Islands',
                'Fiji',
                'Finland',
                'France',
                'French Guiana',
                'French Polynesia',
                'French Southern Territories',
                'Gabon',
                'Gambia',
                'Georgia',
                'Germany',
                'Ghana',
                'Gibraltar',
                'Greece',
                'Greenland',
                'Grenada',
                'Guadeloupe',
                'Guam',
                'Guatemala',
                'Guernsey',
                'Guinea',
                'Guinea-Bissau',
                'Guyana',
                'Haiti',
                'Heard Island And Mcdonald Islands',
                'Holy See',
                'Honduras',
                'Hong Kong',
                'Hungary',
                'Iceland',
                'India',
                'Indonesia',
                'Iraq',
                'Ireland',
                'Isle Of Man',
                'Israel',
                'Italy',
                'Jamaica',
                'Japan',
                'Jersey',
                'Jordan',
                'Kazakhstan',
                'Kenya',
                'Kiribati',
                'Korea, Republic Of',
                'Kosovo',
                'Kyrgyzstan',
                'Latvia',
                'Lebanon',
                'Lesotho',
                'Liberia',
                'Libyan Arab Jamahiriya',
                'Liechtenstein',
                'Lithuania',
                'Luxembourg',
                'Macao',
                'Macedonia, The Former Yugoslav Republic Of',
                'Madagascar',
                'Madeira',
                'Malawi',
                'Malaysia',
                'Maldives',
                'Mali',
                'Malta',
                'Marshall Islands',
                'Martinique',
                'Mauritania',
                'Mauritius',
                'Mayotte',
                'Mexico',
                'Micronesia, Federated States Of',
                'Moldova',
                'Monaco',
                'Mongolia',
                'Montenegro',
                'Montserrat',
                'Morocco',
                'Mozambique',
                'Myanmar',
                'Namibia',
                'Nauru',
                'Nepal',
                'Netherlands',
                'Netherlands Antilles',
                'New Caledonia',
                'New Zealand',
                'Nicaragua',
                'Niger',
                'Nigeria',
                'Niue',
                'Norfolk Island',
                'Northern Mariana Islands',
                'Norway',
                'Oman',
                'Pakistan',
                'Palau',
                'Palestinian Territory, Occupied',
                'Panama',
                'Papua New Guinea',
                'Paraguay',
                'Peru',
                'Philippines',
                'Pitcairn',
                'Poland',
                'Portugal',
                'Puerto Rico',
                'Qatar',
                'Réunion',
                'Romania',
                'Russian Federation',
                'Rwanda',
                'Saint Barthélemy',
                'Saint Helena',
                'Saint Kitts And Nevis',
                'Saint Lucia',
                'Saint Martin',
                'Saint Pierre And Miquelon',
                'Saint Vincent And The Grenadines',
                'Samoa',
                'San Marino',
                'Sao Tome And Principe',
                'Saudi Arabia',
                'Senegal',
                'Serbia',
                'Seychelles',
                'Sierra Leone',
                'Singapore',
                'Slovakia',
                'Slovenia',
                'Solomon Islands',
                'Somalia',
                'South Africa',
                'South Georgia And The South Sandwich Islands',
                'Spain',
                'Sri Lanka',
                'Suriname',
                'Svalbard And Jan Mayen',
                'Swaziland',
                'Sweden',
                'Switzerland',
                'Taiwan',
                'Tajikistan',
                'Tanzania, United Republic Of',
                'Thailand',
                'Timor-Leste',
                'Togo',
                'Tokelau',
                'Tonga',
                'Trinidad And Tobago',
                'Tunisia',
                'Turkey',
                'Turkmenistan',
                'Turks And Caicos Islands',
                'Tuvalu',
                'Uganda',
                'Ukraine',
                'United Arab Emirates',
                'United Kingdom',
                'United States',
                'United States Minor Outlying Islands',
                'Uruguay',
                'Uzbekistan',
                'Vanuatu',
                'Venezuela',
                'Vietnam',
                'Virgin Islands, British',
                'Virgin Islands, US',
                'Wallis And Futuna',
                'Western Sahara',
                'Yemen',
                'Zambia',
                'Zimbabwe'
            );
        ?>
        <div class="shipping-countries-wrapper">
            <label for="united-states">Default Country</label>
            <select name="_bpt_shipping_countries">

                <?php 
                    foreach ( $countries as $country ) {
                        echo '<option value="' . $country . '"' . $this->is_selected( $country, '_bpt_shipping_countries', 'selected' ) . '>' . $country . '</option>'; 
                    } 
                ?>

            </select>
            
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
            <select id="currency" name="_bpt_currency">
                <option value="usd" <?php echo $this->is_selected( 'usd', '_bpt_currency', 'selected' );?>>USD $</option>
                <option value="cad" <?php echo $this->is_selected( 'cad', '_bpt_currency', 'selected' );?>>CAD $</option>
                <option value="gbp" <?php echo $this->is_selected( 'gbp', '_bpt_currency', 'selected' );?>>GBP £</option>
                <option value="eur" <?php echo $this->is_selected( 'eur', '_bpt_currency', 'selected' );?>>EUR €</option>
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
            <select id="price-sort" name="_bpt_price_sort">
                <option value="alpha_asc" <?php echo $this->is_selected( 'alpha_asc', '_bpt_price_sort', 'selected' );?>>Alphabetical</option>
                <option value="value_asc" <?php echo $this->is_selected( 'value_asc', '_bpt_price_sort', 'selected' );?>>Price Value - Low to High</option>
                <option value="value_desc" <?php echo $this->is_selected( 'value_desc', '_bpt_price_sort', 'selected' );?>>Price Value - High to Low</option>
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
            <input id="show-full-description-true" name="_bpt_show_full_description" <?php echo $this->is_selected('true', '_bpt_show_full_description', 'checked');?> value="true" type="radio" />
            <label for="show-full-description-true">Yes</label>
            <input id="show-full-description-false" name="_bpt_show_full_description" <?php echo $this->is_selected('false', '_bpt_show_full_description', 'checked'); ?> value="false" type="radio" />
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
            <input id="show-past-dates-true" name="_bpt_show_past_dates" <?php echo $this->is_selected('true', '_bpt_show_past_dates', 'checked');?> value="true" type="radio" />
            <label for="show-past-dates-true">Yes</label>
            <input id="show-past-dates-false" name="_bpt_show_past_dates" <?php echo $this->is_selected('false', '_bpt_show_past_dates', 'checked'); ?> value="false" type="radio" />
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
            <input id="show-sold-out-dates-true" name="_bpt_show_sold_out_dates" <?php echo $this->is_selected('true', '_bpt_show_sold_out_dates', 'checked');?> value="true" type="radio" />
            <label for="show-sold-out-dates-true">Yes</label>
            <input id="show-sold-out-dates-false" name="_bpt_show_sold_out_dates" <?php echo $this->is_selected('false', '_bpt_show_sold_out_dates', 'checked'); ?> value="false" type="radio" />
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
            <input id="show-sold-out-prices-true" name="_bpt_show_sold_out_prices" <?php echo $this->is_selected('true', '_bpt_show_sold_out_prices', 'checked');?> value="true" type="radio" />
            <label for="show-sold-out-prices-true">Yes</label>
            <input id="show-sold-out-prices-false" name="_bpt_show_sold_out_prices" <?php echo $this->is_selected('false', '_bpt_show_sold_out_prices', 'checked'); ?> value="false" type="radio" />
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