<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class LeadeeApi
 *
 * This class handles various API-related functionalities for the Leadee WordPress plugin.
 *
 * @package leadee
 * @since   1.0.0
 */
class LEADEE_MainApi {

	/**
	 * LeadeeApi constructor.
	 *
	 * Initializes the LeadeeApi class and sets up necessary dependencies.
	 */
	public function __construct() {
		$this->api       = new LEADEE_ApiHelper();
		$this->functions = new LEADEE_Functions();
	}

	/**
	 * Check 'from' and 'to' parameters and respond accordingly.
	 */
	private function check_from_to() {
		$from_raw = filter_input( INPUT_GET, 'from', FILTER_SANITIZE_NUMBER_INT );
		$from_raw = null !== $from_raw ? sanitize_text_field( $from_raw ) : null;

		$to_raw = filter_input( INPUT_GET, 'to', FILTER_SANITIZE_NUMBER_INT );
		$to_raw = null !== $to_raw ? sanitize_text_field( $to_raw ) : null;

		if ( null === $from_raw || null === $to_raw ) {
			wp_send_json_error( array( 'message' => 'Invalid request' ), 400 );
			exit;
		}
	}

	/**
	 * Handle the main Leadee API request.
	 */
	public function leadee_api() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( array( 'message' => 'Access Denied' ), 403 );

			return;
		}

		$from_num_format = $to_num_format = 0;

		$gmt_offset     = get_option( 'gmt_offset', 0 );
		$gmt_offset_sec = $gmt_offset * 3600;

		$action   = isset( $_GET['leadee-api'] ) ? sanitize_text_field( $_GET['leadee-api'] ) : null;
		$from_raw = filter_input( INPUT_GET, 'from', FILTER_SANITIZE_NUMBER_INT );
		$from_raw = null !== $from_raw ? sanitize_text_field( $from_raw ) : null;

		$to_raw = filter_input( INPUT_GET, 'to', FILTER_SANITIZE_NUMBER_INT );
		$to_raw = null !== $to_raw ? sanitize_text_field( $to_raw ) : null;

		$timezone = timezone_name_from_abbr( '', $gmt_offset_sec, true );
		$timezone = false === $timezone ? 'UTC' : $timezone;

		$timezone_raw = isset( $_GET['timezone'] ) ? sanitize_text_field( wp_unslash( $_GET['timezone'] ) ) : '';

		if ( null !== $timezone_raw ) {
			$timezone = $timezone_raw;
		}

		if ( null !== $from_raw && null !== $to_raw ) {
			$from_num_format = intval( $from_raw );
			$to_num_format   = intval( $to_raw );
			$from            = gmdate( 'Y-m-d', intval( $from_num_format / 1000 ) + $gmt_offset_sec ) . ' 00:00:00';
			$to              = gmdate( 'Y-m-d', intval( $to_num_format / 1000 ) + $gmt_offset_sec ) . ' 23:59:59';
		}

		switch ( $action ) {
			case 'leadee-data':
				$this->leadee_data( $from, $to, $timezone );
				break;
			case 'settings-set-option-value':
				$this->settings_set_option_value();
				break;
			case 'dashboard-get-leads-counter':
				$this->dashboard_get_leads_counter();
				break;
			case 'dashboard-get-stat-data':
				$this->dashboard_get_stat_data( $from_num_format, $to_num_format, $timezone, $from, $to );
				break;
			case 'leadee-data-target':
				$this->leadee_data_target( $from, $to );
				break;
			case 'save-target-setting':
				$this->save_target_setting();
				break;
			case 'leadee-data-goal-settings':
				$this->leadee_data_goal_settings();
				break;
			case 'get-last-lead-data':
				$this->get_last_lead_data( $timezone );
				break;
			case 'leadee-data-target-current':
				$this->leadee_data_target_current();
				break;
			case 'target-month-sum-save':
				$this->target_month_sum_save();
				break;
			case 'export':
				$this->export( $from, $to, $timezone );
				break;
		}
	}

	/**
	 * Get leads data within a specified date range and filter.
	 *
	 * @param string $from Start date for data.
	 * @param string $to End date for data.
	 * @param string $timezone Timezone for date and time information.
	 */
	private function leadee_data( $from, $to, $timezone ) {
		$this->check_from_to();
		// todo change filter
		$filter_raw = isset( $_GET['filter'] ) ? sanitize_text_field( wp_unslash( urldecode( $_GET['filter'] ) ) ) : null;

		// Sanitizing values before using them in this method
		$filters_arr = $this->api->prepare_filter( $filter_raw );

		if ( null === $filters_arr ) {
			wp_send_json_error( array( 'message' => 'Invalid request' ), 400 );

			return;
		}

		try {
			$result = $this->api->get_leads_data( $from, $to, $filters_arr, $timezone );
			wp_send_json( $result );
		} catch ( Exception $e ) {
			wp_send_json_error( array( 'message' => 'Error processing request: ' . esc_html( $e->getMessage() ) ), 500 );
		}
	}

	/**
	 * Set a plugin option value in settings.
	 */
	private function settings_set_option_value() {
		$type_raw   = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : null;
		$option_raw = isset( $_POST['option'] ) ? sanitize_text_field( wp_unslash( $_POST['option'] ) ) : null;
		$value_raw  = filter_input( INPUT_POST, 'value', FILTER_SANITIZE_NUMBER_INT );
		$value_raw  = null !== $value_raw ? intval( $value_raw ) : null;

		if ( null !== $type_raw && null !== $option_raw && null !== $value_raw ) {
			try {
				$this->functions->set_setting_option_value( $type_raw, $option_raw, $value_raw );
				wp_send_json_success( array( 'res' => 'saved' ) );
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'message' => 'Error processing request: ' . esc_html( $e->getMessage() ) ), 500 );
			}
		} else {
			wp_send_json_error( array( 'message' => 'Invalid request: Missing option or value' ), 400 );
		}
	}

	/**
	 * Get the count of all leads.
	 */
	private function dashboard_get_leads_counter() {
		try {
			wp_send_json_success( array( 'allLeads' => $this->functions->get_total_leads() ) );
		} catch ( Exception $e ) {
			wp_send_json_error( array( 'message' => 'Error processing request: ' . $e->getMessage() ), 500 );
		}
	}

	/**
	 * Get various statistical data for the plugin's dashboard.
	 *
	 * @param int    $from_num_format Start date in numeric format.
	 * @param int    $to_num_format End date in numeric format.
	 * @param string $timezone Timezone for date and time information.
	 * @param string $from Start date for data.
	 * @param string $to End date for data.
	 */
	private function dashboard_get_stat_data( $from_num_format, $to_num_format, $timezone, $from, $to ) {
		$this->check_from_to();
		try {
			$period_color = $this->functions->get_period_data_from_calend( $from_num_format, $to_num_format, $timezone );

			$result_data = array(
				'dataMainChart'   => $this->functions->get_data_main_chart( $period_color ),
				'dataScreenSize'  => $this->functions->get_data_screen_size( $from, $to, 5 ),
				'dataChartSource' => $this->functions->get_data_chart_source( $period_color ),
				'dataNewLeads'    => $this->functions->get_data_new_leads( $timezone, 6 ),
				'countersData'    => $this->functions->get_counters_data(),
				'osClients'       => $this->functions->get_os_clients_data_by_top( $from, $to, 5 ),
				'popularPages'    => $this->functions->get_popular_pages_data( $from, $to ),
			);

			wp_send_json_success( $result_data );
		} catch ( Exception $e ) {
			wp_send_json_error( array( 'message' => 'Error processing request: ' . $e->getMessage() ), 500 );
		}
	}

	/**
	 * Get leads target data within a date range.
	 *
	 * @param string $from Start date for data.
	 * @param string $to End date for data.
	 */
	private function leadee_data_target( $from, $to ) {
		$this->check_from_to();
		try {
			$result = $this->api->get_leads_target_data( $from, $to );
			wp_send_json( $result );
		} catch ( Exception $e ) {
			wp_send_json_error( array( 'message' => 'Error processing request: ' . $e->getMessage() ), 500 );
		}
	}

	/**
	 * Save target settings for leads.
	 */
	private function save_target_setting() {
		if ( isset( $_POST['rows'] ) && is_array( $_POST['rows'] ) ) {
			try {
				foreach ( $_POST['rows'] as $row ) {
					$type       = isset( $row['type'] ) ? sanitize_text_field( $row['type'] ) : null;
					$identifier = isset( $row['identifier'] ) ? sanitize_text_field( $row['identifier'] ) : null;
					$cost       = isset( $row['cost'] ) ? floatval( $row['cost'] ) : null;
					$status     = isset( $row['status'] ) ? sanitize_text_field( $row['status'] ) : null;

					if ( null === $type || null === $identifier || null === $cost || null === $status ) {
						wp_send_json_error( array( 'message' => 'Invalid request: Missing field' ), 400 );

						return;
					}

					$this->api->save_target_setting( $type, $identifier, $cost, $status );
				}
				wp_send_json_success( array( 'res' => 'saved' ) );
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'message' => 'Error processing request: ' . esc_html( $e->getMessage() ) ), 500 );
			}
		} else {
			wp_send_json_error( array( 'message' => 'Invalid request' ), 400 );

			return;
		}
	}

	/**
	 * Get leads target data settings.
	 */
	private function leadee_data_goal_settings() {
		try {
			wp_send_json( $this->api->get_leads_target_data_settings() );
		} catch ( Exception $e ) {
			wp_send_json_error( array( 'message' => 'Error processing request: ' . $e->getMessage() ), 500 );
		}
	}

	/**
	 * Get the data for the last lead.
	 *
	 * @param string $timezone Timezone for date and time information.
	 */
	private function get_last_lead_data( $timezone ) {
		try {
			$result = $this->functions->get_last_lead_data( $timezone );
			wp_send_json_success( $result );
		} catch ( Exception $e ) {
			wp_send_json_error( array( 'message' => 'Error processing request: ' . esc_html( $e->getMessage() ) ), 500 );
		}
	}

	/**
	 * Get current month's leads target data.
	 */
	private function leadee_data_target_current() {
		try {
			wp_send_json_success( $this->functions->read_current_month_targets_data() );
		} catch ( Exception $e ) {
			wp_send_json_error( array( 'message' => 'Error processing request: ' . $e->getMessage() ), 500 );
		}
	}

	/**
	 * Save a target setting for the month.
	 */
	private function target_month_sum_save() {
		$sum = isset( $_POST['sum'] ) ? (int) $_POST['sum'] : null;
		if ( empty( $sum ) ) {
			wp_send_json_error( array( 'message' => 'Invalid request' ), 400 );

			return;
		}

		try {
			$this->functions->set_setting_option_value( 'setting-target', 'month-target', $sum );
			wp_send_json_success( array( 'res' => 'saved' ) );
		} catch ( Exception $e ) {
			wp_send_json_error( array( 'message' => 'Error processing request: ' . esc_html( $e->getMessage() ) ), 500 );
		}
	}

	/**
	 * Export leads data in different formats (e.g., XLS, CSV).
	 *
	 * @param string $from Start date for data.
	 * @param string $to End date for data.
	 * @param string $timezone Timezone for date and time information.
	 */
	private function export( $from, $to, $timezone ) {
		$this->check_from_to();
		$type_raw = isset( $_GET['type'] ) ? sanitize_text_field( wp_unslash( $_GET['type'] ) ) : null;

		if ( null === $type_raw ) {
			wp_send_json_error( array( 'message' => 'Invalid request' ), 400 );

			return;
		}

		try {
			switch ( $type_raw ) {
				case 'xls':
					$pdf  = new LEADEE_ExcelGenerator();
					$file = $pdf->create_excel_doc( $from, $to, $timezone );
					break;
				case 'csv':
					$pdf  = new LEADEE_CsvGenerator();
					$file = $pdf->create_csv_doc( $from, $to, $timezone );
					break;
				default:
					wp_send_json_error( array( 'message' => 'Invalid request' ), 400 );

					return;
			}
			wp_send_json_success( array( 'file' => base64_encode( $file ) ) );
		} catch ( Exception $e ) {
			wp_send_json_error( array( 'message' => 'Error processing request: ' . esc_html( $e->getMessage() ) ), 500 );
		}
	}
}
