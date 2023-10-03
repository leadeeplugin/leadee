<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * LeadeeExcelGenerator Class
 *
 * @package leadee
 * @since   1.0.0
 */

/**
 * Class LeadeeExcelGenerator
 */
class LEADEE_ExcelGenerator {

	/**
	 * @var LEADEE_ApiHelper API helper object.
	 */
	private $api;

	/**
	 * @var LEADEE_ExcelHelper Excel helper object.
	 */
	private $excelhelper;

	/**
	 * LeadeeExcelGenerator constructor.
	 */
	public function __construct() {
		$this->api         = new LEADEE_ApiHelper();
		$this->excelhelper = new LEADEE_ExcelHelper();
	}

	/**
	 * Create Excel document.
	 *
	 * @param string $from     From date.
	 * @param string $to       To date.
	 * @param string $timezone Timezone.
	 * @return string Generated Excel string.
	 * @throws Exception Throws exception if something goes wrong.
	 */
	public function create_excel_doc( $from, $to, $timezone ) {
		$filename = sprintf( 'leadee-%s_%s.xls', $from, $to );
		$exporter = new LEADEE_ExportDataExcel( 'string', $filename );
		$result   = $this->api->get_leads_data( $from, $to, '', $timezone );

		$exporter->initialize();
		$this->excelhelper->add_fiels_to_file( $exporter, $result );
		$exporter->finalize();

		return $exporter->getString();
	}
}
