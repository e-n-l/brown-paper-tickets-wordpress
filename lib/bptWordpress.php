<?php
namespace BrownPaperTickets;
/**
 * A utility library for Wordpress Stuff.
 */
class BptWordpress {

    private function __construct() {

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

        if ( in_array( 'administrator', (array) $user->roles) ) {
            return true;
        }
    }

}