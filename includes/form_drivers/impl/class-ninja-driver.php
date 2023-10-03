<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class LEADEE_NinjaDriver
 *
 * This class implements the FormDriver interface for handling Ninja Forms submissions.
 */
class LEADEE_NinjaDriver implements LEADEE_FormDriver {

	/**
	 * @var LEADEE_Functions
	 */
	private $functions;
	/**
	 * @var LEADEE_Detector
	 */
	private $detector;

	/**
	 * @var LEADEE_DriverHelper
	 */
	private $driver_helper;

	/**
	 * NinjaDriver constructor.
	 */
	public function __construct() {
		$this->functions     = new LEADEE_Functions();
		$this->detector      = new LEADEE_Detector();
		$this->driver_helper = new LEADEE_DriverHelper();
	}

	/**
	 * Run the Ninja Forms driver to handle form submissions.
	 *
	 * @param array $data The data received from the form submission.
	 *
	 * @return int|void Returns 1 upon successful execution.
	 */
	public function run() {
		// get post id
		if ( isset( $_POST['formData'] ) ) {
			$post_id = $this->driver_helper->take_page_post_id();

			$form_data = isset( $_POST['formData'] ) ? json_decode( $_POST['formData'] ) : null;

			$form_id = $form_data->id ?? null;
			$fields  = array();

			if ( isset( $form_data->fields ) ) {
				foreach ( $form_data->fields as $key => $obj ) {
					$fields[] = array(
						'field' => sanitize_text_field( $key ),
						'value' => sanitize_text_field( $obj->value ),
					);
				}
			}

			$leadee_source          = isset( $_COOKIE['leadee_source'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['leadee_source'] ) ) : null;
			$user_agent             = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : null;
			$leadee_first_visit_url = isset( $_COOKIE['leadee_first_visit_url'] ) ? esc_url_raw( wp_unslash( $_COOKIE['leadee_first_visit_url'] ) ) : null;

			$data_arr = $this->detector->detect( $user_agent, $leadee_source, $leadee_first_visit_url );

			$status_and_cost_by_target_type = $this->functions->get_status_and_cost_by_target_type( 'ninja', $form_id );
			if ( isset( $status_and_cost_by_target_type->status ) && '1' === $status_and_cost_by_target_type->status ) {
				$this->functions->write_lead( $post_id, $form_id, $fields, $data_arr, 'ninja', $status_and_cost_by_target_type->cost );
			}
		}

		return 1;
	}
}
