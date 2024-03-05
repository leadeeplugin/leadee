<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Class Detector
 *
 * This class is responsible for detecting user device and browser information as well as extracting source-related data.
 */
class LEADEE_Detector {

	/**
	 * @var LEADEE_Functions
	 */
	private $functions;

	/**
	 * @var leadee\LEADEE_BrowserDetection
	 */
	private $detector;

	/**
	 * Detector constructor.
	 */
	public function __construct() {
		$this->functions = new LEADEE_Functions();
		$this->detector  = new leadee\LEADEE_BrowserDetection();
	}

	/**
	 * Detect user device, browser, and source-related data.
	 *
	 * @param string $useragent              The user agent string.
	 * @param string $source                 The source URL.
	 * @param string $leadee_first_visit_url The first visit URL.
	 *
	 * @return array Associative array containing detected data.
	 */
	public function detect( $useragent, $source, $leadee_first_visit_url ) {
		$device_type            = $this->detector->getDevice( $useragent )['device_type'];
		$device_os              = $this->detector->getOS( $useragent )['os_name'];
		$device_os_version      = $this->detector->getOS( $useragent )['os_version'];
		$device_browser_name    = $this->detector->getBrowser( $useragent )['browser_name'];
		$device_browser_version = $this->detector->getBrowser( $useragent )['browser_version'];

		$parameters = array();

		$leadee_first_visit_url = esc_url_raw( $leadee_first_visit_url );
		$parsed_url             = wp_parse_url( $leadee_first_visit_url );
		if ( isset( $parsed_url['query'] ) ) {
			$parameters = $parsed_url['query'];
			$parameters = explode( '&', $parameters );
		}

		if ( isset( $source ) ) {
			$source = sanitize_text_field( $source );
			$domain = wp_parse_url( html_entity_decode( $source ), PHP_URL_HOST );
		} else {
			$domain = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
		}

		$domain = $this->remove_www_if_exist( $domain );

		$source_category = $this->functions->get_source_category( $domain, $parameters );

		return array(
			'domain'                 => $domain,
			'source_category'        => $source_category,
			'first_url_parameters'   => wp_json_encode( $parameters ),
			'device_type'            => $device_type,
			'device_os'              => $device_os,
			'device_os_version'      => $device_os_version,
			'device_browser_name'    => $device_browser_name,
			'device_browser_version' => $device_browser_version,
		);
	}

	/**
	 * Remove 'www.' from the domain if it exists.
	 *
	 * @param string $domain The domain name.
	 *
	 * @return string|string[]|null The domain with 'www.' removed.
	 */
	private function remove_www_if_exist( $domain ) {
		return preg_replace( '/^www\./i', '', $domain );
	}
}
