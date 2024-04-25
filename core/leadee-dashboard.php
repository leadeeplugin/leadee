<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! function_exists( 'wp_get_current_user' ) ) {
	include ABSPATH . 'wp-includes/pluggable.php';
}

/**
 * Add Leadee menu to the WordPress admin.
 */
function leadee_admin_menu() {
	// Add Leadee menu item.
	add_menu_page(
		'Leadee',
		'Leadee <span id="leadee-unread-count" class="leadee-notifications-count update-plugins count-0"><span class="plugin-count">0</span></span>',
		'activate_plugins',
		'leadee-dashboard',
		'leadee_dashboard',
		'data:image/svg+xml;base64,' . base64_encode(
			wp_remote_get(
				LEADEE_PLUGIN_URL . '/core/assets/image/leadee.svg',
				array('sslverify' => false,)
			)['body']
		),
		3
	);


	/**
	 * Render the Leadee dashboard.
	 */
	function leadee_dashboard() {
		?>
		<h1>
			<?php esc_html_e( 'Redirect to dashboard...' ); ?>
		</h1>
		<?php
		$url = get_site_url() . '?leadee-page=dashboard';
		echo( "<script>location.href = '" . esc_url( $url ) . "'</script>" );
	}
}

// Add Leadee admin menu.
add_action( 'admin_menu', 'leadee_admin_menu' );

// Include required files.
require_once LEADEE_PLUGIN_DIR . '/includes/form_drivers/class-detector.php';
require_once LEADEE_PLUGIN_DIR . '/includes/form_drivers/interface/form-driver.php';
require_once LEADEE_PLUGIN_DIR . '/includes/form_drivers/class-driver-helper.php';
require_once LEADEE_PLUGIN_DIR . '/includes/form_drivers/class-form-driver-factory.php';
require_once LEADEE_PLUGIN_DIR . '/includes/form_drivers/impl/class-contact-form7-driver.php';
require_once LEADEE_PLUGIN_DIR . '/includes/form_drivers/impl/class-ninja-driver.php';
require_once LEADEE_PLUGIN_DIR . '/includes/form_drivers/impl/class-wpforms-driver.php';
require_once LEADEE_PLUGIN_DIR . '/core/api/class-leadee-receiver.php';

$leadeePublicApi = new LEADEE_Receiver();
$leadeePublicApi->leadee_detect();
