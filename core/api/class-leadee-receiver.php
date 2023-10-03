<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class LeadeeReceiver
 *
 * This class handles the reception and detection of data from external sources for the Leadee WordPress plugin.
 *
 * @package leadee
 * @since   1.0.0
 */
class LEADEE_Receiver {


	/**
	 * LeadeeReceiver constructor.
	 *
	 * Initializes the LeadeeReceiver class and sets up necessary dependencies.
	 */
	public function __construct() {
		$this->api       = new LEADEE_ApiHelper();
		$this->functions = new LEADEE_Functions();
	}


	/**
	 * Detect and process incoming data.
	 *
	 * This method detects incoming data, processes it, and triggers the appropriate actions based on the data source.
	 * It uses a FormDriverFactory to create the appropriate driver for processing the data.
	 */
	public function leadee_detect() {
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {
			$driver = LEADEE_FormDriverFactory::create();

			if ( isset( $driver ) ) {
				$driver->run();
			}
		}
	}
}
