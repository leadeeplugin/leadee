<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class FormDriverFactory
 *
 * This class is responsible for creating the appropriate FormDriver based on the incoming data.
 */
class LEADEE_FormDriverFactory {

	/**
	 * Create an instance of the appropriate FormDriver based on the incoming data.
	 *
	 * @return LEADEE_FormDriver|null An instance of the FormDriver interface or null if no suitable driver is found.
	 */
	public static function create() {
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {
			$action         = isset( $_POST['action'] ) ? sanitize_text_field( wp_unslash( $_POST['action'] ) ) : '';
			$requested_with = isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) : '';

			switch ( true ) {
				case ( false !== strpos( $action, 'nf_ajax_submit' ) || ( isset( $_POST['ninja'] ) && 'xmlhttprequest' !== $requested_with ) ):
					return new LEADEE_NinjaDriver();
				case ( false !== strpos( $action, '_wpcf7' ) || ( isset( $_POST['_wpcf7'] ) && 'xmlhttprequest' !== $requested_with ) ):
					return new LEADEE_ContactLEADEEForm7Driver();
				case ( false !== strpos( $action, 'wpforms' ) || ( isset( $_POST['wpforms'] ) && 'xmlhttprequest' !== $requested_with ) ):
					return new LEADEE_WpFormsDriver();
				default:
					return null;
			}
		}

		return null;
	}
}
