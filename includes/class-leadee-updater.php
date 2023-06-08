<?php

class leadee_Updater
{

    public static function update_plugin($upgrader_object, $options)
    {
        //code for updates

        if ($options['action'] == 'update' && $options['type'] == 'plugin' && in_array('leadee/leadee.php', $options['plugins'])) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/leadee/leadee.php');
            $current_version = $plugin_data['Version'];
            $latest_version = $options['new_version'];

            if (version_compare($current_version, $latest_version, '<')) {
                // Run update script
                // ...
            }
        }

    }

} // class
