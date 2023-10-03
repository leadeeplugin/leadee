<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class leadee
 */
class LEADEE_MainInit {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @access      protected
	 * @var         leadee_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @access      protected
	 * @var         string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @access      protected
	 * @var         string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the core-facing side of the site.
	 */
	public function __construct() {
		$this->plugin_name = LEADEE_PLUGIN_NAME;
		$this->version     = LEADEE_VERSION;
		$this->load_dependencies();
	} // __construct()

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - leadee_Loader. Orchestrates the hooks of the plugin.
	 * - leadee_i18n. Defines internationalization functionality.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @access      private
	 */
	private function load_dependencies() {

		/**
		 * Leadee functions.
		 */
		require_once LEADEE_PLUGIN_DIR . '/includes/class-leadee-functions.php';

		/**
		 * Excel class
		 */
		require_once LEADEE_PLUGIN_DIR . '/includes/lib/PhpExportData.php';

		/**
		 * Excel class helper
		 */
		require_once LEADEE_PLUGIN_DIR . '/includes/generator/classes/class-excel-helper.php';

		/**
		 * Color graf class
		 */
		require_once LEADEE_PLUGIN_DIR . '/includes/color-graf.php';

		/**
		 * Csv
		 */
		require_once LEADEE_PLUGIN_DIR . '/includes/generator/csv/class-csv-generator.php';

		/**
		 * Excel
		 */
		require_once LEADEE_PLUGIN_DIR . '/includes/generator/excel/class-leadee-excel-generator.php';

		/**
		 * BrowserDetection
		 */
		require_once LEADEE_PLUGIN_DIR . '/includes/lib/BrowserDetection.php';

		/**
		 * Api class
		 */
		require_once LEADEE_PLUGIN_DIR . '/core/api/class-api-helper.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once LEADEE_PLUGIN_DIR . '/includes/class-leadee-loader.php';

		/**
		 * Page loader scripts
		 */
		require_once LEADEE_PLUGIN_DIR . '/includes/class-leadee-scripts-loader.php';

		/**
		 * Dashboard
		 */
		require_once LEADEE_PLUGIN_DIR . '/core/leadee-dashboard.php';

		$this->loader = new leadee_Loader();
	}

	/**
	 * Run plugin
	 */
	public function leadee_start() {
		$this->loader->leadee_run();
	}
}
