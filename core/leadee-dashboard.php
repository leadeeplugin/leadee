<?php
if (!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php");
}

function leadee_admin_menu()
{
    add_menu_page(
        __('Leadee', 'leadee-dashboard'),
        __('Leadee', 'leadee-dashboard'),
        'activate_plugins',
        'leadee-dashboard',
        'leadee_dashboard',
        'data:image/svg+xml;base64,' . base64_encode(file_get_contents(LEADEE_PLUGIN_DIR . '/core/assets/image/leadee.svg')),
        3
    );
    function leadee_dashboard()
    {
        ?>
        <h1>
            <?php esc_html_e('Redirect to dashboard...'); ?>
        </h1>
        <?php
        $url = get_site_url() . "?leadee-page=dashboard";
        echo("<script>location.href = '" . $url . "'</script>");
    }
}


add_action('admin_menu', 'leadee_admin_menu');


//detector
require_once LEADEE_PLUGIN_DIR . '/includes/form_drivers/class-detector.php';
//utils
require_once LEADEE_PLUGIN_DIR . '/includes/form_drivers/utils/class-cookie-util.php';
//driver interface
require_once LEADEE_PLUGIN_DIR . '/includes/form_drivers/interface/form-driver.php';
//driver helper
require_once LEADEE_PLUGIN_DIR . '/includes/form_drivers/class-driver-helper.php';
//driver factory
require_once LEADEE_PLUGIN_DIR . '/includes/form_drivers/class-form-driver-factory.php';
//contact form 7
require_once LEADEE_PLUGIN_DIR . '/includes/form_drivers/impl/class-contact-form7-driver.php';
//ninja
require_once LEADEE_PLUGIN_DIR . '/includes/form_drivers/impl/class-ninja-driver.php';
//wpforms
require_once LEADEE_PLUGIN_DIR . '/includes/form_drivers/impl/class-wpforms-driver.php';
//receiver
require_once LEADEE_PLUGIN_DIR . '/core/api/class-leadee-receiver.php';


$leadeePublicApi = new LeadeeReceiver();
$leadeePublicApi->leadee_detect();
