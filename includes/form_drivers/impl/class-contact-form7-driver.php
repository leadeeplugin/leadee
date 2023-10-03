<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class ContactForm7Driver
 *
 * This class implements the FormDriver interface for handling Contact Form 7 submissions.
 */
class LEADEE_ContactLEADEEForm7Driver implements LEADEE_FormDriver {


	/**
	 * @var LEADEE_Functions $functions An instance of the Leadee_Functions class.
	 */
	private $functions;

	/**
	 * @var LEADEE_Detector $detector An instance of the Detector class.
	 */
	private $detector;

	/**
	 * @var LEADEE_DriverHelper $driver_helper An instance of the DriverHelper class.
	 */
	private $driver_helper;

	/**
	 * ContactForm7Driver constructor.
	 */
	public function __construct() {
		$this->functions     = new LEADEE_Functions();
		$this->detector      = new LEADEE_Detector();
		$this->driver_helper = new LEADEE_DriverHelper();
	}

	/**
	 * Run the Contact Form 7 driver to handle form submissions.
	 *
	 * @return int Returns 1 upon successful execution.
	 */
	public function run() {
		$post_id                = $this->driver_helper->take_page_post_id();
		$leadee_first_visit_url = isset( $_COOKIE['leadee_first_visit_url'] ) ? esc_url_raw( wp_unslash( $_COOKIE['leadee_first_visit_url'] ) ) : null;
		$leadee_source          = isset( $_COOKIE['leadee_source'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['leadee_source'] ) ) : null;
		$user_agent             = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : null;

		$data_arr = $this->detector->detect( $user_agent, $leadee_source, $leadee_first_visit_url );

		// Here it is impossible to do otherwise, the contact-form passes dynamic fields.
		$fields = array_filter(
			filter_input_array( INPUT_POST, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY ) ?? array(),
			function ( $key ) {
				return strpos( $key, '_wpcf7' ) !== 0;
			},
			ARRAY_FILTER_USE_KEY
		);

		$fields_for_db = array();
		$i             = 0;
		foreach ( $fields as $item ) {
			$fields_for_db[ $i ] = array(
				'field' => '',
				'value' => sanitize_text_field( $item ),
			);
			++$i;
		}
		$form_id = isset( $_POST['_wpcf7'] ) ? (int) $_POST['_wpcf7'] : 0;

		$status_and_cost_by_target_type = $this->functions->get_status_and_cost_by_target_type( 'cf7', $form_id );

		if ( isset( $status_and_cost_by_target_type->status ) && '1' === $status_and_cost_by_target_type->status ) {
			$this->functions->write_lead( $post_id, $form_id, $fields_for_db, $data_arr, 'cf7', $status_and_cost_by_target_type->cost );
		}

		return 1;
	}
}
