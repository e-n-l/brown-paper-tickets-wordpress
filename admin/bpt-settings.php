<?php

require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-api.php');
require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-plugin.php');

use BrownPaperTickets\BPTFeed;
use BrownPaperTickets\BPTPlugin;

$events = new BPTFeed;

$menu_slug = BPTPlugin::get_menu_slug();
$plugin_slug = BPTPlugin::get_plugin_slug();
$plugin_version = BPTPlugin::get_plugin_version();
?>
<h1>
    <img src="<?php echo plugin_dir_url( __FILE__ ).'../assets/img/bpt.png';?>">
</h1>

<div class="wrap">
    <div class="bpt-welcome-panel">
        <div class="bpt-welcome-panel-content">
        
        </div>

        <span class="bpt-welcome-info-plugin-info">Plugin Info: <?php echo $plugin_slug . ' v' . $plugin_version; ?> - <a class="bpt-submit-issue" href="https://github.com/BrownPaperTickets/bpt_wordpress_plugin/issues/new">Submit Bug</a></span>
    </div>
    <nav id="<?php echo $menu_slug;?>">
        <ul>
            <li><a class="bpt-admin-tab" href="#usage">Usage</a></li>
            <li><a class="bpt-admin-tab" href="#account-setup">Account Setup</a></li>
            <li><a class="bpt-admin-tab" href="#general-settings">General Settings</a></li>
            <li><a class="bpt-admin-tab" href="#event-settings">Event Settings</a></li>
            <li><a class="bpt-admin-tab" href="#purchase-settings">Purchase Settings</a></li>
            <li><a class="bpt-admin-tab" href="#help">Help</a></li>
            <li><a class="bpt-admin-tab" href="#credits">Credits</a></li>
            <li><a class="bpt-admin-tab" href="#debug">Debug</a></li>
        </ul>
    </nav>
    <form method="post" action="options.php">
    <?php settings_fields( $menu_slug ); ?>
    <div id="bpt-settings-wrapper">
        <div id="usage">
            <h1>Plugin Usage</h1>
            <p class="bpt-jumbotron">This plugin allows you to display your events within your wordpress posts or using a widget</p>
            <h2>Shortcodes</h2>
            <p>Simply enter one of the shortcodes and place it where you want it in a post or a page.</p>
            <table>
                <tr>
                    <th>Shortcode</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td><pre class="bpt-inline">[list-events]</pre></td>
                    <td>This will display all of your events in a ticket widget format.</td>
                </tr>
                <tr>
                    <td><pre class="bpt-inline">[list-event id="EVENT_ID"]</pre></td>
                    <td>This will display a single event. EVENT_ID is the ID of the event you wish to display.</td>
                </tr>
                <tr>
                    <td><pre class="bpt-inline">[list-events-links]</pre></td>
                    <td>This will simply generate a list of links to your events.</td>
                </tr>
            </table>
            <h2>Widgets</h2>
            <ul>
                <li>Calendar Widget</li>
                <li>Text List Widget</li>

            </ul>
        </div>
        <div id="account-setup">
            <div>
                <?php do_settings_sections( $menu_slug . '_api' ); ?>
                <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
            </div>
        </div>
        <div id="general-settings">
            <div>
                <?php do_settings_sections( $menu_slug . '_general' ); ?>
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
            <h2>In a future release you will be able to enable sales via the plugin.</h2>
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

        </div>

    </div>
    </form>
</div>

<script type="text/ractive" id="bpt-welcome-panel-template">
    {{ #account }}
    <h1>Hi, {{ firstName }}</h1>
    {{ /account}}
    <div class="bpt-status-box">

    </div>
    {{ #request }}
        {{ #message }}
            <div class="bpt-message-box">
                <p class="{{ result === false ? 'bpt-error-message' : 'bpt-success-message' }} ">{{ message }} </p>
            </div>
        {{ /message}}
    {{ /request }}
</script>