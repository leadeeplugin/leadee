<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Class DriverHelper
 *
 * This class provides helper methods related to drivers.
 */
class LEADEE_DriverHelper {

	/**
	 * Get the post ID of the current page based on the HTTP_REFERER.
	 *
	 * @return int The post ID of the current page, or 0 if not found.
	 */
	public static function take_page_post_id() {
		$referrer_raw = filter_input( INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_URL );

		if ( $referrer_raw ) {
			$referrer_raw = esc_url_raw( wp_unslash( $referrer_raw ) );
			$parsed_url   = wp_parse_url( trim( $referrer_raw ) );

			$slug = sanitize_text_field( $parsed_url['path'] ?? '/' );
			$slug = ltrim( $slug, '/' ) ?: '/'; // Fallback to '/' if $slug is empty after trimming.

			// Get the page by path
			$page = get_page_by_path( $slug );

			return $page->ID ?? 0; // Directly return the page ID or 0 if not set.
		}

		return 0;
	}
}
