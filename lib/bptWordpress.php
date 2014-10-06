<?php
namespace BrownPaperTickets;
/**
 * A utility library for Wordpress Stuff.
 */
class BptWordpress {

	private function __construct() {

	}

	/**
	 * Get the absolute path of the plugin root directory.
	 *
	 * @return string The path to the plugin's directory with
	 * trailing slash.
	 */
	static function plugin_root_dir() {
		return plugin_dir_path( __DIR__ );
	}

	static function plugin_root_url() {
		return plugins_url( '', plugin_dir_path( __FILE__ ) );
	}
	/**
	 * Determiner whether or not the user is an administrator.
	 * @param  integer  $user_id The Id of the user.
	 * @return boolean           Returns true if user is an admin.
	 */
	static function is_user_an_admin( $user_id = null ) {

		if ( is_numeric( $user_id ) ) {
			$user = get_userdata( $user_id );
		}

		else {

			$user = wp_get_current_user();
		}

		if ( empty( $user ) ) {
			return false;
		}

		if ( in_array( 'administrator', (array) $user->roles ) ) {
			return true;
		}
	}

	static function get_country_list() {
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
			'Zimbabwe',
		);

		return $countries;
	}
	/**
	 * Utilites
	 */

	/**
	 * Determines whether or not an option matches a given value.
	 * @param  [type]  $value  [description]
	 * @param  [type]  $option [description]
	 * @param  [type]  $type   [description]
	 * @return boolean         [description]
	 */
	static function is_selected( $value, $option = null, $type = null ) {

		$opt = get_option( $option );

		if ( is_array( $opt ) ) {

			foreach ( $opt as $single_opt ) {

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