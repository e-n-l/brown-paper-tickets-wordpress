<?php

    require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-api.php');
    use BrownPaperTickets\BPTSettingsFields;
    $settings_fields = new BPTSettingsFields;

    require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-plugin.php');
    use BrownPaperTickets\BPTPlugin;
    $menu_slug = BPTPlugin::get_menu_slug();
?>
<form id="bpt-setup-wizard-form" method="post" action="options.php">
<?php settings_fields( $menu_slug ); ?>
<div class="bpt-setup-wizard-wrapper">
<h1>
    <img src="<?php echo plugin_dir_url( __FILE__ ).'../assets/img/bpt.png';?>">
</h1>
    <div class="bpt-setup-wizard bpt-intro">
        <h1>Thanks for installing the Brown Paper Tickets Wordpress Plugin.</h1>
        <p>In order to make use of this plugin, you'll need to set up a few options.</p>
        <div class="bpt-setup-wizard-button-container">
            <button class="bpt-setup-wizard-prev-step button-primary button-large" disabled >&laquo; Previous</button>
            <button class="bpt-setup-wizard-next-step button-primary button-large">Next &raquo;</button>
        </div>
    </div>
    <div class="bpt-setup-wizard bpt-step-1">
        <h1>Account Setup</h1>
        <h3>
            First, you'll need you'll Brown Paper Tickets Developer ID.
        </h3>
        <?php $settings_fields->get_developer_id_input(); ?>
        <p>
            If you don't already have one, you'll need to do the following to obtain one.
        </p>
        <ol>
            <li>Log in to Brown Paper Tickets</li>
            <li>Go to <a href="https://www.brownpapertickets.com/user/functions.html" target="_blank">Account Functions</a></li>
            <li>Click on "Developer Tools"</li>
            <li>Click on "Add Developer Tools"</li>
            <li>
                You'll see a new <a href="https://www.brownpapertickets.com/developer/index.html" target="_blank">Developer</a> 
                link in the navigation menu.</li>
            <li>Go <a href="https://www.brownpapertickets.com/developer/index.html" target="_blank">there</a> to find your Developer ID.</li>
            <li>Enter your Developer ID EXACTLY as it appears into the box above</li>
        </ol>
        <div class="bpt-setup-wizard-button-container">
            <button class="bpt-setup-wizard-prev-step button-primary button-large">&laquo; Previous</button>
            <button class="bpt-setup-wizard-next-step button-primary button-large">Next &raquo;</button>
        </div>
    </div>
    <div class="bpt-setup-wizard bpt-step-2">
        <h1>Account Setup</h1>
        <h3>
            Next you'll need your Client ID.
        </h3>
        <?php $settings_fields->get_client_id_input(); ?>
        <p>
            Your Client ID is the Brown Paper Tickets <em>username</em> 
            of your (or the producer whose events you want to list) account.
        </p>
        <p>
            Brown Paper Tickets will allow you to log in using either 
            your username or the email address associated with your account.
        <p>
            Sometimes they are the same, sometimes they are not.
        <p>
            If you are having issues determining what your username is, 
            please contact Client Services at 1.800.838.3006 or send them
            an <a href="mailto:support@brownpapertickets.com">email</a>.
        </p>
        <div class="bpt-setup-wizard-button-container">
            <button class="bpt-setup-wizard-prev-step button-primary button-large">&laquo; Previous</button>
            <button class="bpt-setup-wizard-next-step button-primary button-large">Next &raquo;</button>
        </div>
    </div>
    <div class="bpt-setup-wizard bpt-step-3">
        <h1>Account Test</h1>
        <h3>
            Time to test your Developer ID and Client ID.
        </h3>
        <p>
            If you are having issues determining what your username is, 
            please contact Client Services at 1.800.838.3006 or send them
            an <a href="mailto:support@brownpapertickets.com">email</a>.
        </p>

        <div>
            <button class="bpt-setup-wizard-ajax-call">Test Account</button><img class="bpt-loading hidden" src="<?php echo plugins_url( '/assets/img/loading.gif', dirname( __FILE__ ) ); ?>">
            <div id="bpt-setup-wizard-response"></div>
        </div>
    </div>
    <div class="bpt-setup-wizard bpt-step-4">
        <h1>Setup is complete.</h1>
        <h2>Save the settings</h2>
        <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
        <p>There are many Settings you will want to take a look at.</p>
        <div class="bpt-setup-wizard-bpt-setup-wizard-button-container">
            <button class="bpt-setup-wizard-prev-step button-primary button-large">&laquo; Previous</button>
            <button class="bpt-setup-wizard-next-step button-primary button-large" disabled>Next &raquo;</button>
        </div>
    </div>
    <div class="bpt-setup-wizard-debug">

    
    </div>

</div>
</form>

<script type="text/ractive" id="bpt-setup-wizard-template">
    {{ #eventError }}
        <div>
            <h2>Sorry, something went wrong with the Developer ID </h2>
            <h3>{{ explainError(code, 'events') }}</h3>
            <h4 class="last-name"></h4>
            <span class="total-events"></span>
        </div>
    {{ /eventError }}

    {{ #accountError }}
        <div>
            <h2>Sorry, something went wrong with the Client ID.</h2>
            <h3>{{ explainError(code, 'account') }}</h3>
        </div>
    {{ /accountError }}


    {{ #account }}
        <div>
            <h2>Hello {{ firstName }}.</h2>
            <h4 class="first-name"></h4>
            <h4 class="last-name"></h4>
            <span class="total-events"></span>
        </div>
    {{ /account }}

    <div class="{{ .events.length > 0 ? '' : 'hidden' }}">
        <h3>You currently have {{ .events.length }} active events on Brown Paper Tickets</h3>
        <table>
            <tr>
                <th>Event Title</th>
            </tr>
        {{ #events }}
            <tr>
                <td>{{ title }}</td>
                <td>{{ live }}</td>
            </tr>
        {{ /events}}
        </table>
        <div class="bpt-setup-wizard-button-container">
            <button class="bpt-setup-wizard-prev-step button-primary button-large">Previous</button>
            <button id="bpt-setup-wizard-save" class="bpt-setup-wizard-next-step button-primary button-large" disabled="{{ .eventError || .accountError ? 'disabled' : '' }}">Next</button>
        </div>
    </div>
</script>