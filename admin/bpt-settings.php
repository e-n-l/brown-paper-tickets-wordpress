<?php

require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-api.php');
require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-plugin.php');

use BrownPaperTickets\BPTFeed;
use BrownPaperTickets\BPTPlugin;

$events = new BPTFeed;

$menu_slug = BPTPlugin::get_menu_slug();
?>
<h1>
    <img src="<?php echo plugin_dir_url( __FILE__ ).'../assets/img/bpt.png';?>">
</h1>

<div class="wrap">
    <div class="welcome-panel">
        <a class="welcome-panel-close" href="#">Dismiss</a>
        <div class="welcome-panel-content">
            <h3>Hello There</h3>
            <p class="about-description">This is some message.</p>
            <div class="welcome-panel-column-container">
                <h4>Total Events:</h4>
                <div class="welcome-panel-column">
                    <p></p>
                </div>
            </div>
        </div>
    </div>
    <nav id="<?php echo $menu_slug;?>">
        <ul>
            <li><a class="bpt-admin-tab" href="#account-setup">Account Setup</a></li>
            <li><a class="bpt-admin-tab" href="#event-settings">Event Settings</a></li>
            <li><a class="bpt-admin-tab" href="#purchase-settings">Purchase Settings</a></li>
            <li><a class="bpt-admin-tab" href="#help">Help</a></li>
            <li><a class="bpt-admin-tab" href="#credits">Credits</a></li>
        </ul>
    </nav>
    <form method="post" action="options.php">
    <?php settings_fields( $menu_slug ); ?>
    <div id="bpt-settings-wrapper">
        <div id="account-setup">
            <div>
                <?php do_settings_sections( $menu_slug . '_api'); ?>
                <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
            </div>
        </div>
        <div id="event-settings">
            <div>
                <?php do_settings_sections( $menu_slug . '_event'); ?>
                <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
            </div>
        </div>
        <div id="purchase-settings">
            <div>
            <?php
                    if ( !is_ssl() ) {
            ?>
                        <h3 class="error">Sorry, you must connect via SSL (HTTPS) in order to use this option.</h3>
                        <p>
                            Without SSL on your site, you would be enabling your ticket buyers to submit their Credit Card without any sort of security.
                        </p>
                        <p>
                            You'll want to contact your web host or your web person in order to get SSL set up.
                        </p>
            <?php      
                    } else {
                        do_settings_sections( $menu_slug . '_purchase');
            ?>
                    <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
            <?php
                    }
            ?>
            </div>
        </div>
        <div id="help">
            <h2>Help</h2>
            <ul>
                <li>Go To <a href="http://localhost/bptwp/wp-admin/admin.php?page=brown_paper_tickets_settings_setup_wizard">Setup Wizard</a></li>
            </ul>
        </div>
        <div id="credits">
            <h3>Credits</h3>
            <p>This plugin makes use of Free Software</p>
            <div>
                <ul>
                    <li>jQuery - Bundled with Wordpress</li>
                    <li>Underscore - Bundled with Wordpress</li>
                    <li>Wordpress - (You're using Wordpress)</li>
                    <li>Ractive.js - JS Template Engine</li>
                    <li>Moment.js- DateTime library</li>
                </ul>
            </div>
        </div>
        <div class="plugin-debug">
            <div class="events">
                <a id="get-events" href="#">Get Events</a>
            </div>
        </div>

    </div>
    </form>
</div>