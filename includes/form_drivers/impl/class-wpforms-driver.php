<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class WpFormsDriver
 *
 * This class implements the FormDriver interface for handling WPForms submissions.
 */
class LEADEE_WpFormsDriver implements LEADEE_FormDriver {


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
	 * Run the WPForms driver to handle form submissions.
	 *
	 * @return int Returns 1 upon successful execution.
	 */
	public function run() {
		$fields = array();
		if ( isset( $_POST['wpforms'] ) && isset( $_POST['wpforms']['fields'] ) ) {
			foreach ( $_POST['wpforms']['fields'] as $key => $value ) {
				if ( is_array( $value ) ) {
					foreach ( $value as $sub_value ) {
						$fields[] = array(
							'field' => sanitize_text_field( $key ),
							'value' => sanitize_text_field( $sub_value ),
						);
					}
				} else {
					$fields[] = array(
						'field' => sanitize_text_field( $key ),
						'value' => sanitize_text_field( $value ),
					);
				}
			}
		}

		$leadee_source          = isset( $_COOKIE['leadee_source'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['leadee_source'] ) ) : null;
		$user_agent             = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : null;
		$leadee_first_visit_url = isset( $_COOKIE['leadee_first_visit_url'] ) ? esc_url_raw( wp_unslash( $_COOKIE['leadee_first_visit_url'] ) ) : null;

		$post_arr = $this->detector->detect( $user_agent, $leadee_source, $leadee_first_visit_url );
		$post_id  = $this->driver_helper->take_page_post_id();

		$form_id = isset( $_POST['wpforms']['id'] ) ? (int) $_POST['wpforms']['id'] : 0;

		$status_and_cost_by_target_type = $this->functions->get_status_and_cost_by_target_type( 'wpforms', $form_id );

		if ( isset( $status_and_cost_by_target_type->status ) && '1' === $status_and_cost_by_target_type->status ) {
			$this->functions->write_lead( $post_id, $form_id, $fields, $post_arr, 'wpforms', $status_and_cost_by_target_type->cost );
		}

		return 1;
	}
}
