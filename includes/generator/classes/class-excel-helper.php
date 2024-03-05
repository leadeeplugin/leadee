<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * ExcelHelper Class
 *
 * @package leadee
 */

/**
 * Class ExcelHelper
 */
class LEADEE_ExcelHelper {

	/**
	 * @var LEADEE_Functions Functions object.
	 */
	private $functions;

	/**
	 * ExcelHelper constructor.
	 */
	public function __construct() {
		$this->functions = new LEADEE_Functions();
	}

	/**
	 * Add fields to file.
	 *
	 * @param object $exporter Exporter object.
	 * @param array  $result   Data result.
	 */
	public function add_fiels_to_file( $exporter, $result ) {
		$columns = array(
			'num'                  => '№',
			'dt'                   => 'Date',
			'fields'               => 'Form fields',
			'source_category'      => 'Source category',
			'device_type'          => 'Device type',
			'post_name'            => 'Page',
			'device_os'            => 'Device OS',
			'form_type'            => 'Form type',
			'form_name'            => 'Form',
			'source'               => 'Source domain',
			'first_url_parameters' => 'First visit parameters',
			'device_browser_name'  => 'Browser',
			'device_screen_size'   => 'Screen size',
		);

		$columns = $this->remove_disable_columns( $columns );
		$exporter->addRow( array_values( $columns ) );

		if ( isset( $result['data'] ) && count( $result['data'] ) > 0 ) {
			$i = 1;
			foreach ( $result['data'] as $row ) {
				$row['num']  = $i;
				$columnsKeys = array_keys( $columns );
				$exporter->addRow( $this->get_array_data_from_keys( $row, $columnsKeys ) );
				++$i;
			}
		}
	}

	/**
	 * Get array data from keys.
	 *
	 * @param array $row  Row data.
	 * @param array $keys Array of keys.
	 * @return array Modified array.
	 */
	private function get_array_data_from_keys( $row, $keys ) {
		$result = array();

		foreach ( $keys as $i => $key ) {
			switch ( $key ) {
				case 'device_screen_size':
					$result[ $i ] = sprintf( '%sx%s', $row['device_width'], $row['device_height'] );
					break;
				case 'device_os':
					$result[ $i ] = sprintf( '%s %s', $row['device_os'], $row['device_os_version'] );
					break;
				case 'device_browser_name':
					$result[ $i ] = sprintf( '%s %s', $row['device_browser_name'], $row['device_browser_version'] );
					break;
				case 'fields':
					$result[ $i ] = $this->to_lead_data( $row['fields'] );
					break;
				default:
					$result[ $i ] = $row[ $key ];
			}
		}

		return $result;
	}

	/**
	 * Remove disabled columns.
	 *
	 * @param array $columns List of columns.
	 * @return array Modified array of columns.
	 */
	private function remove_disable_columns( $columns ) {
		$checkingColumns = array( 'dt', 'source', 'first_url_parameters', 'device_screen_size' );

		foreach ( $checkingColumns as $key ) {
			if ( ! $this->is_column_enable( $key ) ) {
				unset( $columns[ $key ] );
			}
		}
		if ( ! $this->is_column_enable( 'device_browser' ) ) {
			unset( $columns['device_browser_name'] );
		}
		return $columns;
	}

	/**
	 * Check if column is enabled.
	 *
	 * @param string $option Option name.
	 * @return bool True if enabled, false otherwise.
	 */
	private function is_column_enable( $option ) {
		return $this->functions->get_setting_option_value( 'leads-table-columns', $option ) === '1';
	}

	/**
	 * Convert fields to lead data.
	 *
	 * @param array $fields List of fields.
	 * @return string Lead data as a string.
	 */
	private function to_lead_data( $fields ) {
		$res = '';
		foreach ( json_decode( $fields, true ) as $field ) {
			$res .= ( ! empty( $field['field_name'] ) ? $field['field_name'] . ': ' : '' ) . $field['value'] . ' | ';
		}
		return $res;
	}
}
