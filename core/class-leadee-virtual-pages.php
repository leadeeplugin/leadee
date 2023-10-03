<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Class LeadeeVirtualPages
 *
 * This class is responsible for initializing virtual pages within the WordPress site.
 *
 * @package leadee
 * @since   1.0.0
 */
class LEADEE_VirtualPages {

	/**
	 * Initialize virtual pages.
	 *
	 * This method sets up virtual pages by adding a callback function to the 'template_redirect' action hook.
	 * It generates the page's header, includes the specified page template, and adds the footer before exiting.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $page        The name of the virtual page to be displayed.
	 */
	public function init_pages( $plugin_name, $page ) {
		add_action(
			'template_redirect',
			function () use ( $plugin_name, $page ) {
				$this->generate_head( $plugin_name, $page );
				require_once LEADEE_PLUGIN_DIR . '/core/partials/header.php';
				require_once LEADEE_PLUGIN_DIR . "/core/pages/{$page}.php";
				require_once LEADEE_PLUGIN_DIR . '/core/partials/footer.php';
				exit;
			}
		);
	}

	/**
	 * Generate the HTML head section for the virtual page.
	 *
	 * This method generates the HTML head section for the virtual page, including meta tags and the page title.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $page        The name of the virtual page.
	 */
	private function generate_head( $plugin_name, $page ) {
		$admin_language = get_user_locale();
		$title          = ucfirst( $plugin_name ) . ' - ' . ucfirst( $page );
		$head           = '<!DOCTYPE html>
        <html lang="' . esc_attr( $admin_language ) . '" prefix="og: http://ogp.me/ns#">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
            <title>' . esc_html( $title ) . '</title>';
		echo wp_kses_post( $head );
	}
}
