<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly
}

/**
 * Class LeadeeApiHelper
 *
 * This class provides helper methods for handling API-related functionality within the Leadee WordPress plugin.
 *
 * @package leadee
 * @since   1.0.0
 */
class LEADEE_ApiHelper {
	/**
	 * LeadeeApiHelper constructor.
	 *
	 * Initializes the LeadeeApiHelper class and sets up necessary dependencies.
	 */
	public function __construct() {
		$this->functions = new LEADEE_Functions();
	}//end __construct()

	/**
	 * Set names for field data.
	 *
	 * @param string $fields JSON-encoded field data.
	 *
	 * @return array Processed and sanitized field data.
	 */
	public function set_names_for_field_data( $fields ) {
		// Decoding JSON to array, it's a good practice to add error checking here
		$decoded_fields = json_decode( $fields, true );
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			// Handle JSON decoding error
			return array();
		}

		$data = array();
		foreach ( $decoded_fields as $key => $field ) {
			// Sanitizing values before using them
			$sanitized_key   = sanitize_text_field( $key );
			$sanitized_value = isset( $field['value'] ) ? sanitize_text_field( $field['value'] ) : '';

			$data[ $sanitized_key ] = array(
				'field_name' => '',
				'value'      => $sanitized_value,
			);
		}

		return $data;
	}//end set_names_for_field_data()


	/**
	 * Get the name of a form by its ID.
	 *
	 * @param integer $form_id The ID of the form.
	 *
	 * @return string The name of the form, sanitized and escaped.
	 */
	public function get_form_name( $form_id ) {
		// Casting $form_id to integer to ensure it's an integer
		$form_id = (int) $form_id;

		if ( $form_id > 0 ) {
			// Escaping output before returning it
			return esc_html( $this->functions->get_post_name_by_id( $form_id ) );
		} else {
			return '';
		}
	}//end get_form_name()


	/**
	 * Prepare and sanitize filter data.
	 *
	 * @param string $filter_string Filter data in a string format.
	 *
	 * @return array An array of sanitized and prepared filter data.
	 */
	public function prepare_filter( $filter_string ) {
		if ( ! is_string( $filter_string ) ) {
			return array();
		}

		$filters_arr_prepare = explode( ';', $filter_string );
		$filters_arr         = array();

		foreach ( $filters_arr_prepare as $key => $item ) {
			$item_array = explode( '#', $item );

			if ( isset( $item_array[0] ) && isset( $item_array[1] ) ) {
				// Sanitize the keys and values to prevent XSS attacks
				$sanitized_key   = sanitize_text_field( $item_array[0] );
				$sanitized_value = sanitize_text_field( trim( $item_array[1] ) );

				$filters_arr[ $key ]['key']   = $sanitized_key;
				$filters_arr[ $key ]['value'] = $sanitized_value;
			}
		}

		return $filters_arr;
	}//end prepare_filter()


	/**
	 * Get leads data based on various parameters.
	 *
	 * @param string $from Start date for the data.
	 * @param string $to End date for the data.
	 * @param array  $filters_arr Filters for the data.
	 * @param string $timezone Timezone for date and time information.
	 *
	 * @return array Leads data retrieved based on the provided parameters.
	 */
	public function get_leads_data( $from, $to, $filters_arr, $timezone ) {
		$from = sanitize_text_field( $from );
		$to   = sanitize_text_field( $to );

		$result = array();
		$draw   = isset( $_GET['draw'] ) ? (int) $_GET['draw'] : 0;
		if ( null !== $draw ) {
			$draw = intval( $draw );
			if ( $draw < 1 ) {
				return $result;
			}

			$total = $this->functions->get_total_leads_from_to( $from, $to );

			$search_value = isset( $_GET['search']['value'] ) ? sanitize_text_field( wp_unslash( $_GET['search']['value'] ) ) : '';

			$start = isset( $_GET['start'] ) ? (int) $_GET['start'] : 0;

			$limit = isset( $_GET['length'] ) ? (int) $_GET['length'] : 25;

			$order_column = 'dt';
			$order_dir    = 'desc';

			$leads = $this->functions->get_filter_search_leads( $start, $limit, $order_column, $order_dir, $from, $to, $filters_arr, $search_value );

			$leads_no_limit = $this->functions->get_all_filtered_leads_without_page_limit( $from, $to, $filters_arr );

			$filter_total = count( $leads_no_limit );

			$data = array();
			if ( ! empty( $leads ) ) {
				foreach ( $leads as $key => $lead ) {
					$post_id         = (int) $leads[ $key ]->post_id;
					$site_name       = esc_html( get_bloginfo( 'name' ) );
					$post_name       = ( 0 === (int) $post_id ) ? $site_name : esc_html( get_the_title( $post_id ) );
					$source_category = sanitize_text_field( $leads[ $key ]->source_category );
					$lead_data       = $leads[ $key ];
					$data            = $this->mapData( $data, $key, $lead_data, $source_category, $post_name, $timezone );
				}
			}

			$result = array(
				'draw'            => $draw,
				'recordsTotal'    => $total,
				'recordsFiltered' => $filter_total,
				'data'            => $data,
			);
		}//end if

		return $result;
	}//end get_leads_data()


	/**
	 * Map lead data to a format suitable for output.
	 *
	 * @param array  $data Existing data array.
	 * @param string $key Key for the data.
	 * @param object $lead_data Lead data to be mapped.
	 * @param string $source_category Source category of the lead.
	 * @param string $post_name Name of the associated post.
	 * @param string $timezone Timezone information.
	 *
	 * @return mixed Mapped lead data.
	 * @throws Exception If there's an issue with date and time formatting.
	 */
	private function mapData( $data, $key, $lead_data, $source_category, $post_name, $timezone ) {
		// Ensure $key is sanitized and $lead_data is an object
		$key = sanitize_text_field( $key );
		if ( ! is_object( $lead_data ) ) {
			return $data;
		}

		// Ensure IDs are integers
		$data[ $key ]['id'] = intval( $lead_data->id );

		// Handle date time formatting securely
		$dt_from_db_in_utc0 = sanitize_text_field( $lead_data->dt );
		$dt                 = new DateTime( $dt_from_db_in_utc0, new DateTimeZone( 'UTC' ) );
		$formatted_dt_ymd   = $dt->format( 'Y-m-d' );
		$formatted_dt_his   = $dt->format( 'H:i:s' );
		$data[ $key ]['dt'] = esc_html( $formatted_dt_ymd ) . '<br>' . esc_html( $formatted_dt_his );

		// Encode JSON securely
		$fields_json            = wp_json_encode( $this->set_names_for_field_data( $lead_data->fields ) );
		$data[ $key ]['fields'] = $fields_json ? $fields_json : '';

		// Sanitize and escape other fields
		$data[ $key ]['source_category']        = sanitize_text_field( $source_category );
		$data[ $key ]['device_type']            = sanitize_text_field( $lead_data->device_type );
		$data[ $key ]['post_id']                = intval( $lead_data->post_id );
		$data[ $key ]['post_name']              = esc_html( $post_name );
		$data[ $key ]['device_os']              = sanitize_text_field( $lead_data->device_os );
		$data[ $key ]['form_type']              = sanitize_text_field( $lead_data->form_type );
		$data[ $key ]['form_id']                = intval( $lead_data->form_id );
		$data[ $key ]['source']                 = sanitize_text_field( $lead_data->source );
		$data[ $key ]['first_url_parameters']   = sanitize_text_field( $lead_data->first_url_parameters );
		$data[ $key ]['device_browser_name']    = sanitize_text_field( $lead_data->device_browser_name );
		$data[ $key ]['cost']                   = sanitize_text_field( $lead_data->cost );
		$data[ $key ]['form_name']              = esc_html( $this->get_form_name( $lead_data->form_id ) );
		$data[ $key ]['home_url']               = esc_url( get_home_url() );
		$data[ $key ]['device_os_version']      = sanitize_text_field( $lead_data->device_os_version );
		$data[ $key ]['device_browser_version'] = sanitize_text_field( $lead_data->device_browser_version );
		$data[ $key ]['device_height']          = sanitize_text_field( $lead_data->device_height );
		$data[ $key ]['device_width']           = sanitize_text_field( $lead_data->device_width );

		return $data;
	}//end mapData()


	/**
	 * Get leads target data within a date range.
	 *
	 * @param string $from Start date for the data.
	 * @param string $to End date for the data.
	 *
	 * @return array Leads target data within the specified date range.
	 */
	public function get_leads_target_data( $from, $to ) {
		// Sanitize input parameters
		$from = sanitize_text_field( $from );
		$to   = sanitize_text_field( $to );

		$forms = array_merge(
			$this->functions->get_all_forms_cf7(),
			$this->functions->get_all_forms_wpforms(),
			$this->functions->get_all_forms_ninja()
		);

		$leads_data = array();

		foreach ( $forms as $key => $form ) {
			// Assume that the form data is coming from a trusted source,
			// if not, you should sanitize/escape it accordingly.
			$leads_data[ $key ]['title'] = sanitize_text_field( $form['title'] );
			$leads_data[ $key ]['type']  = sanitize_text_field( $form['type'] );
			$leads_data[ $key ]['count'] = intval(
				$this->functions->get_count_leads_by_form_type(
					sanitize_text_field( $form['type'] ),
					intval( $form['id'] ),
					$from,
					$to
				)
			);
			$leads_data[ $key ]['sum']   = intval(
				$this->functions->get_sum_leads_by_form_type(
					sanitize_text_field( $form['type'] ),
					intval( $form['id'] ),
					$from,
					$to
				)
			);
		}//end foreach

		// Sanitize the 'draw' GET parameter and ensure it is a positive integer
		$draw = isset( $_GET['draw'] ) ? (int) $_GET['draw'] : 0;

		return $draw !== null ? array(
			'draw'            => $draw,
			'recordsTotal'    => 1,
			'recordsFiltered' => 1,
			'data'            => $leads_data,
		) : $leads_data;
	}


	/**
	 * Get leads target data settings.
	 *
	 * @return array Leads target data settings.
	 */
	public function get_leads_target_data_settings() {
		$draw = isset( $_GET['draw'] ) ? (int) $_GET['draw'] : 0;

		$result = $this->functions->scan_all_froms();

		return array(
			'draw'            => $draw,
			'recordsTotal'    => 1,
			'recordsFiltered' => 1,
			'data'            => $result,
		);
	}

	/**
	 * Save target setting data.
	 *
	 * @param string $type Type of target.
	 * @param string $identifier Identifier for the target.
	 * @param string $cost Cost associated with the target.
	 * @param string $status Status of the target.
	 */
	public function save_target_setting( $type, $identifier, $cost, $status ) {
		$this->functions->save_target_setting( $type, $identifier, $cost, $status );
	}//end save_target_setting()
}//end class
