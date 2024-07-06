<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class ScriptsLoader
 *
 * This class handles the loading of JavaScript and CSS scripts for your WordPress plugin.
 * It enqueues various scripts and styles required for different plugin pages and functionality.
 *
 * @package leadee
 * @since   1.0.0
 */
class LEADEE_Scripts_Loader {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Path to plugin assets.
	 *
	 * @var string
	 */
	private $assets_path;

	/**
	 * Prefix used for script and style handles.
	 *
	 * @var string
	 */
	private $prefix;

	/**
	 * ScriptsLoader constructor.
	 *
	 * Initializes the ScriptsLoader class and sets default values for properties.
	 */
	public function __construct() {
		$this->version     = LEADEE_VERSION;
		$this->assets_path = LEADEE_PLUGIN_URL . '/core/assets';
		$this->prefix      = 'leadee_';

		$this->load_constants();

		wp_enqueue_script( $this->prefix . 'tour_js_script', $this->assets_path . '/libs/driver/driver.min.js', array( 'jquery' ), $this->version, false, true );
		wp_enqueue_script( $this->prefix . 'framework7-bundle', $this->assets_path . '/libs/framework7/framework7-bundle.min.js', array( 'jquery' ), $this->version, false, true );
		wp_enqueue_script( $this->prefix . 'f7-scripts', $this->assets_path . '/js/f7-scripts.js', array( 'jquery' ), $this->version, false, true );

		wp_enqueue_script( $this->prefix . 'main', $this->assets_path . '/js/main.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->prefix . 'main',
			'outData',
			array(
				'siteUrl' => get_site_url(),
			)
		);

		wp_localize_script(
			$this->prefix . 'main',
			'localDataMain',
			array(
				'noDataText'          => __( 'No data', 'leadee' ),
				'newLead'             => __( 'New lead!', 'leadee' ),
				'emptyLeadsTableText' => wp_kses(
					__( 'Leadee running.<br>Possibly for a selected period of time<br>clients did not leave requests?<br>Please select a different date range.', 'leadee' ),
					array( 'br' => array() )
				),
			)
		);

		$this->load_datatable_js();

		wp_enqueue_script( $this->prefix . 'chart_js_script', $this->assets_path . '/libs/chartjs/chart.min.js', array( 'jquery' ), $this->version, false, true );

		$this->load_datapicker_local();

		wp_enqueue_script( $this->prefix . 'moment', $this->assets_path . '/js/moment-with-locales.js', array( 'jquery' ), $this->version, false, true );

		wp_enqueue_style( $this->prefix . 'montserrat', $this->assets_path . '/style/Montserrat/montserrat.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->prefix . 'framework7', $this->assets_path . '/libs/framework7/framework7.bundle.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->prefix . 'air-datepicker', $this->assets_path . '/libs/dpicker/air-datepicker.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->prefix . 'bootstrap-glyphicons', $this->assets_path . '/libs/bootstrap/bootstrap-glyphicons.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->prefix . 'driver', $this->assets_path . '/libs/driver/driver.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->prefix . 'driver', $this->assets_path . '/libs/driver/driver.min.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->prefix . 'main', $this->assets_path . '/css/main.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->prefix . 'datatables-custom', $this->assets_path . '/css/datatables-custom.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->prefix . 'datatables-responsive-custom', $this->assets_path . '/css/datatables-responsive-custom.css', array(), $this->version, 'all' );
	}

	/**
	 * Load scripts for the dashboard page.
	 *
	 * Enqueues scripts and sets localization data for the dashboard page.
	 */
	public function load_scripts_page_dashboard() {
		wp_enqueue_script( $this->prefix . 'page_dashboard_script', $this->assets_path . '/js/pages/dashboard/dashboard.js', array( 'jquery' ), $this->version, false, true );
		wp_localize_script(
			$this->prefix . 'page_dashboard_script',
			'outData',
			array(
				'siteUrl' => get_site_url(),
			)
		);

		wp_localize_script(
			$this->prefix . 'page_dashboard_script',
			'localDataDashboard',
			array(
				'noDataText'    => __( 'No data', 'leadee' ),
				'emptyNewLeads' => esc_html__(
					'We are waiting for leads.<br>Submit a test request<br>and you will see a nice notification here :)',
					'leadee'
				),
			)
		);

		$this->load_scripts_calend();
		$this->load_tour_common_scripts();
		wp_enqueue_script( $this->prefix . 'dashboard-tour', $this->assets_path . '/js/pages/dashboard/dashboard-tour.js', array( 'jquery' ), $this->version, false, true );
		wp_localize_script(
			$this->prefix . 'dashboard-tour',
			'localDataDashboardTour',
			array(
				'stepOneTitle'   => __( 'Chart with Statistics', 'leadee' ),
				'stepOneDesc'    => __( 'The columns are colored in 7 rainbow colors for ease of perception.', 'leadee' ),
				'stepTwoTitle'   => __( 'New Leads Widget', 'leadee' ),
				'stepTwoDesc'    => __( 'Here you can always see notifications about new leads received in real-time. The information is also duplicated in a pop-up window.', 'leadee' ),
				'stepThreeTitle' => __( 'You have reached customizable goals', 'leadee' ),
				'stepThreeDesc'  => __( 'What is it? - The uniqueness of our plugin. Set your own goals for the number of leads you want to receive per current month and achieve them!', 'leadee' ),
				'stepFourTitle'  => __( 'Leads Sources Chart', 'leadee' ),
				'stepFourDesc'   => __( 'In the "Leads Sources" chart, you will see which type of traffic is the most conversion-friendly on your website: search, advertising systems, social networks, and others.', 'leadee' ),
				'stepFiveTitle'  => __( '3 Blocks with Important Customer Information', 'leadee' ),
				'stepFiveDesc'   => __( 'Their screen sizes, the operating systems they use, and the pages from which they most often leave leads.', 'leadee' ),
			)
		);
		$this->load_last_scripts();
	}

	/**
	 * Load scripts for the leads page.
	 *
	 * Enqueues scripts and sets localization data for the leads page.
	 */
	public function load_scripts_page_leads() {
		wp_enqueue_script( $this->prefix . 'page_leads_script', $this->assets_path . '/js/pages/leads/leads.js', array( 'jquery' ), $this->version, false, true );
		wp_localize_script(
			$this->prefix . 'page_leads_script',
			'outData',
			array(
				'siteUrl'                          => get_site_url(),
				'isEnableColumnDt'                 => $this->is_enable_column( 'dt' ),
				'isEnableColumnSource'             => $this->is_enable_column( 'source' ),
				'isEnableColumnFirstUrlParameters' => $this->is_enable_column( 'first_url_parameters' ),
				'isEnableColumnDeviceBrowser'      => $this->is_enable_column( 'device_browser' ),
				'isEnableColumnDeviceScreenSize'   => $this->is_enable_column( 'device_screen_size' ),
			)
		);
		wp_localize_script(
			$this->prefix . 'page_leads_script',
			'localDataLeads',
			array(
				'Entries'     => __( 'Entries', 'leadee' ),
				'Source'      => __( 'Source', 'leadee' ),
				'Device'      => __( 'Device', 'leadee' ),
				'ResetFilter' => __( 'Reset Filter', 'leadee' ),
				'Select'      => __( 'Select', 'leadee' ),
			)
		);
		$this->load_scripts_calend();
		$this->load_tour_common_scripts();
		wp_enqueue_script( $this->prefix . 'leads-tour', $this->assets_path . '/js/pages/leads/leads-tour.js', array( 'jquery' ), $this->version, false, true );
		wp_localize_script(
			$this->prefix . 'leads-tour',
			'localDataLeadsTour',
			array(
				'stepOneTitle' => __( 'Chart with Statistics', 'leadee' ),
				'stepOneDesc'  => __( 'The columns are colored in 7 rainbow colors for ease of perception.', 'leadee' ),
			)
		);
		$this->load_last_scripts();
	}

	/**
	 * Load scripts for the goals page.
	 *
	 * Enqueues scripts and sets localization data for the goals page.
	 */
	public function load_scripts_page_goals() {
		$this->load_target_graf();
		wp_enqueue_script( $this->prefix . 'page_targets_script', $this->assets_path . '/js/pages/goals/goals.js', array( 'jquery' ), $this->version, false );
		$site_url = get_site_url();
		wp_localize_script(
			$this->prefix . 'page_targets_script',
			'outData',
			array(
				'siteUrl' => $site_url,
			)
		);

		wp_localize_script(
			$this->prefix . 'page_targets_script',
			'localDataGoals',
			array(
				'conversionText' => __( 'conversions', 'leadee' ),
			)
		);

		$this->load_scripts_calend();
		$this->load_tour_common_scripts();
		wp_enqueue_script( $this->prefix . 'goals-tour', $this->assets_path . '/js/pages/goals/goals-tour.js', array( 'jquery' ), $this->version, false, true );
		wp_localize_script(
			$this->prefix . 'goals-tour',
			'localDataGoalsTour',
			array(
				'stepOneTitle'   => __( 'Statistics Chart', 'leadee' ),
				'stepOneDesc'    => __( 'The columns are colored with 7 rainbow colors for better perception', 'leadee' ),
				'stepTwoTitle'   => __( 'Status of Your Goal Progress', 'leadee' ),
				'stepTwoDesc'    => __( 'Track your progress with our fun indicator', 'leadee' ),
				'stepThreeTitle' => __( 'Sum of Goals by Forms', 'leadee' ),
				'stepThreeDesc'  => __( 'See how much you earned from each form', 'leadee' ),
			)
		);
		$this->load_last_scripts();
	}

	/**
	 * Load scripts for the leads table settings page.
	 *
	 * Enqueues scripts and sets localization data for the leads table settings page.
	 */
	public function load_scripts_page_leads_table_settings() {
		wp_enqueue_script( $this->prefix . 'leads_table_settings_script', $this->assets_path . '/js/pages/leads-table-settings/leads-table-settings.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->prefix . 'leads_table_settings_script',
			'outData',
			array(
				'siteUrl'   => get_site_url(),
				'savedText' => __( 'Saved!', 'leadee' ),
			)
		);

		wp_localize_script(
			$this->prefix . 'leads_table_settings_script',
			'localDataLeadsTableSettings',
			array(
				'noDataText' => __( 'No data', 'leadee' ),
				'yesText'    => __( 'Yes', 'leadee' ),
				'notText'    => __( 'Not', 'leadee' ),
				'savedText'  => __( 'Saved!', 'leadee' ),
			)
		);

		$this->load_tour_common_scripts();
		wp_enqueue_script( $this->prefix . 'leads-table-settings-tour', $this->assets_path . '/js/pages/leads-table-settings/leads-table-settings-tour.js', array( 'jquery' ), $this->version, false, true );

		wp_localize_script(
			$this->prefix . 'leads-table-settings-tour',
			'localDataLeadsTableSettingsTour',
			array(
				'stepOneTitle' => __( 'Lead Table Column Management', 'leadee' ),
				'stepOneDesc'  => __( 'For your convenience, you can disable or enable columns in the lead table.', 'leadee' ),
			)
		);

		$this->load_last_scripts();
	}

	/**
	 * Load scripts for the goals settings page.
	 *
	 * Enqueues scripts and sets localization data for the goals settings page.
	 */
	public function load_scripts_page_goals_settings() {
		$this->load_target_graf();
		$this->load_datatable_js();
		wp_enqueue_script( $this->prefix . 'goals-settings', $this->assets_path . '/js/pages/goals-settings/goals-settings.js', array( 'jquery' ), $this->version, false, true );
		wp_localize_script(
			$this->prefix . 'goals-settings',
			'outData',
			array(
				'siteUrl' => get_site_url(),
			)
		);

		wp_localize_script(
			$this->prefix . 'goals-settings',
			'localDataGoalsSettings',
			array(
				'noDataText' => __( 'No data', 'leadee' ),
				'yesText'    => __( 'Yes', 'leadee' ),
				'notText'    => __( 'Not', 'leadee' ),
				'savedText'  => __( 'Saved!', 'leadee' ),
			)
		);
		$this->load_tour_common_scripts();
		wp_enqueue_script( $this->prefix . 'goals-settings-tour', $this->assets_path . '/js/pages/goals-settings/goals-settings-tour.js', array( 'jquery' ), $this->version, false, true );

		wp_localize_script(
			$this->prefix . 'goals-settings-tour',
			'localDataGoalsSettingsTour',
			array(
				'stepOneTitle' => __( 'Form Cost Settings Table', 'leadee' ),
				'stepOneDesc'  => __( 'In this table, you can set the cost of leads for each form.', 'leadee' ),
				'stepTwoTitle' => __( 'Want to earn a lot in a month? Set a monthly goal!', 'leadee' ),
				'stepTwoDesc'  => __( 'This setting will help you track your monthly progress.', 'leadee' ),
			)
		);
		$this->load_last_scripts();
	}


	/* Another scripts  */

	/**
	 * Load scripts for the calendar functionality.
	 *
	 * Enqueues scripts and sets localization data for calendar-related functionality.
	 */
	public function load_scripts_calend() {
		wp_enqueue_script( $this->prefix . 'page_calend_script', $this->assets_path . '/js/calend.js', array( 'jquery' ), $this->version, false, true );
		wp_localize_script(
			$this->prefix . 'page_calend_script',
			'outData',
			array(
				'siteUrl' => get_site_url(),
			)
		);

		wp_localize_script(
			$this->prefix . 'page_calend_script',
			'localDataCalend',
			array(
				'From'       => __( 'From', 'leadee' ),
				'To'         => __( 'To', 'leadee' ),
				'Today'      => __( 'Today', 'leadee' ),
				'text7days'  => __( '7 days', 'leadee' ),
				'text31days' => __( '31 days', 'leadee' ),
			)
		);
	}

	/**
	 * Load tour common scripts
	 */
	public function load_tour_common_scripts() {
		wp_enqueue_script( $this->prefix . 'tour-common', $this->assets_path . '/js/pages/tour-common.js', array( 'jquery' ), $this->version, false, true );

		wp_localize_script(
			$this->prefix . 'tour-common',
			'localDataCommonTour',
			array(
				'Endtour'   => __( 'End tour', 'leadee' ),
				'CloseText' => __( 'Close', 'leadee' ),
				'Next'      => __( 'Next', 'leadee' ),
				'Previous'  => __( 'Previous', 'leadee' ),
			)
		);
	}

	/**
	 * Load the last scripts required for various functionality.
	 */
	public function load_last_scripts() {
		wp_enqueue_script( $this->prefix . 'load_last_scripts', $this->assets_path . '/js/common/last-scripts.js', array( 'jquery' ), $this->version, false, true );
		wp_localize_script(
			$this->prefix . 'load_last_scripts',
			'outData',
			array(
				'siteUrl' => get_site_url(),
			)
		);
	}

	/**
	 * Load target graph related scripts and styles.
	 */
	private function load_target_graf() {
		wp_enqueue_style( $this->prefix . 'page_targets_graf_target_style', $this->assets_path . '/libs/graf-target/css/graf-target.css', array(), $this->version, false );
		wp_enqueue_script( $this->prefix . 'graftarget_script', $this->assets_path . '/libs/graf-target/graf-target.js', array( 'jquery' ), $this->version, false, true );
		wp_localize_script(
			$this->prefix . 'graftarget_script',
			'dataOut',
			array(
				'assetsPath' => $this->assets_path,
			)
		);
	}

	/**
	 * Check if a specific column is enabled.
	 *
	 * @param string $column The column name to check.
	 *
	 * @return bool Whether the column is enabled or not.
	 */
	private function is_enable_column( $column ) {
		$functions = new LEADEE_Functions();

		return $functions->get_setting_option_value( 'leads-table-columns', $column ) === '1';
	}

	/**
	 * Load plugin constants.
	 */
	private function load_constants() {
		wp_enqueue_script( $this->prefix . 'constants_api_constants', $this->assets_path . '/js/common/constants/api-constants.js', array( 'jquery' ), $this->version, false, true );
		wp_enqueue_script( $this->prefix . 'constants_api_constants', $this->assets_path . '/js/common/constants/selector-constants.js', array( 'jquery' ), $this->version, false, true );
	}

	/**
	 * Load DataTables related scripts.
	 */
	private function load_datatable_js() {
		wp_enqueue_script( $this->prefix . 'data_tables', $this->assets_path . '/js/jquery.dataTables.min.js', array( 'jquery' ), $this->version, false, true );

		wp_localize_script(
			$this->prefix . 'data_tables',
			'localDataDataTables',
			array(
				'noDataInTable' => __( 'No data available in table', 'leadee' ),
				'showing'       => __( 'Showing', 'leadee' ),
				'entries'       => __( 'entries', 'leadee' ),
				'to'            => __( 'to', 'leadee' ),
				'of'            => __( 'of', 'leadee' ),
				'loading'       => __( 'Loading...', 'leadee' ),
				'processing'    => __( 'Processing...', 'leadee' ),
				'search'        => __( 'Search:', 'leadee' ),
				'searchNoData'  => __( 'No matching records found', 'leadee' ),
			)
		);

		wp_enqueue_script( $this->prefix . 'data_tables_select', $this->assets_path . '/libs/datatables/dataTables.select.js', array( 'jquery' ), $this->version, false, true );
		wp_enqueue_script( $this->prefix . 'data_tables_responsive', $this->assets_path . '/libs/datatables/dataTables.responsive.min.js', array( 'jquery' ), $this->version, false, true );
		wp_enqueue_script( $this->prefix . 'data_tables_editor', $this->assets_path . '/libs/datatables/dataTables.altEditor.free.js', array( 'jquery' ), $this->version, false, true );
	}

	/**
	 * Load the localization for the Air Datepicker plugin.
	 */
	private function load_datapicker_local() {
		wp_enqueue_script( $this->prefix . 'air_datepicker', $this->assets_path . '/libs/dpicker/air-datepicker.js', array( 'jquery' ), $this->version, false, true );

		$current_user = wp_get_current_user();
		$user_locale  = get_user_locale( $current_user->ID );
		$language     = substr( strstr( $user_locale, '_', true ), 0, 2 );

		$language_file = $this->assets_path . '/libs/dpicker/locale/' . $language . '.js';
		$absolute_path = LEADEE_PLUGIN_DIR . '/core/assets/libs/dpicker/locale/' . $language . '.js';
		if ( file_exists( $absolute_path ) ) {
			wp_enqueue_script( $this->prefix . 'air-datepicker_lang', $language_file, array( 'jquery' ), $this->version, false, true );
		} else {
			wp_enqueue_script( $this->prefix . 'air-datepicker_lang', $this->assets_path . '/libs/dpicker/locale/en.js', array( 'jquery' ), $this->version, false, true );
		}
	}
}
