<?php

require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-api.php');

use BrownPaperTickets\APIv2\BrownPaperTicketsAPI;

$events = new BrownPaperTicketsAPI();
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
                <h4>Total Events: <?php echo $events->get_event_count();?></h4>
                <div class="welcome-panel-column">
                    <p></p>
                </div>
            </div>
        </div>
    </div>
    <h2>Account Setup</h2>
    <div>
        <form method="post" action="options.php">
        <?php
            settings_fields(PLUGIN_SLUG.'_settings');
            do_settings_sections( PLUGIN_SLUG.'_settings'); 
        ?>
        <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form>
    </div>

    <div class="events">
        <a id="get-events" href="#">Get Events</a>
    </div>
</div>