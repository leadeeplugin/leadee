<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class LeadeeFunctions.
 */
class LEADEE_Functions {

	/**
	 * Code for SERP data.
	 *
	 * @var string
	 */
	private $code_serp = 'serp';

	/**
	 * Code for social data.
	 *
	 * @var string
	 */
	private $code_social = 'social';

	/**
	 * Code for advertising data.
	 *
	 * @var string
	 */
	private $code_advert = 'advert';

	/**
	 * Code for referral data.
	 *
	 * @var string
	 */
	private $code_referal = 'referal';

	/**
	 * Code for direct data.
	 *
	 * @var string
	 */
	private $code_direct = 'direct';

	/**
	 * Instance of Leadee_ColorGraf class.
	 *
	 * @var LEADEE_ColorGraf
	 */
	private $leadee_color_graf;

	/**
	 * Table name for leads.
	 *
	 * @var string
	 */
	const TABLE_NAME_LEADS_CONST = 'leadee_leads';

	/**
	 * Table name for leadee targets.
	 */
	const TABLE_NAME_LEADEE_TARGETS_CONST = 'leadee_targets';

	/**
	 * Table name for default SERP data.
	 */
	const TABLE_NAME_DEFAULT_SERP_CONST = 'leadee_base_default_serp';

	/**
	 * Table name for default social data.
	 */
	const TABLE_NAME_DEFAULT_SOCIAL_CONST = 'leadee_base_default_social';

	/**
	 * Table name for default advertising data.
	 */
	const TABLE_NAME_DEFAULT_ADVERT_CONST = 'leadee_base_default_advert';

	/**
	 * Table name for default settings.
	 */
	const TABLE_NAME_LEADEE_SETTINGS_CONST = 'leadee_settings';

	/**
	 * WordPress database object.
	 *
	 * @var wpdb
	 */
	private $glob_wpdb;


	/**
	 * Leadee_Functions constructor.
	 */
	public function __construct() {
		global $wpdb;
		$this->glob_wpdb         = $wpdb;
		$this->leadee_color_graf = new LEADEE_ColorGraf();
	}

	/**
	 *  Get table name.
	 *
	 * @param string $table_name table name.
	 *
	 * @return string
	 */
	public function get_table_name( $table_name ) {
		return $this->glob_wpdb->prefix . $table_name;
	}

	/**
	 * Retrieve filtered and searched leads.
	 *
	 * @param int $start Start index for pagination.
	 * @param int $limit Number of records to retrieve.
	 * @param string $order_by_column Column to order by.
	 * @param string $order_asc_desc Sorting order (ASC or DESC).
	 * @param string $from Start date.
	 * @param string $to End date.
	 * @param array $filters Array of filters.
	 * @param string $search_text Search text.
	 *
	 * @return array
	 */
	public function get_filter_search_leads( $start, $limit, $order_by_column, $order_asc_desc, $from, $to, $filters, $search_text ) {
		$query = $this->take_sql_query_for_filter_and_search( $from, $to, $order_by_column, $order_asc_desc, $filters, $start, $limit, $search_text );

		return $this->glob_wpdb->get_results( $query );
	}

	/**
	 * Retrieve all filtered leads without page limit.
	 *
	 * @param string $from Start date.
	 * @param string $to End date.
	 * @param array $filters Array of filters.
	 *
	 * @return array
	 */
	public function get_all_filtered_leads_without_page_limit( $from, $to, $filters ) {

		$table_name    = $this->get_table_name( self::TABLE_NAME_LEADS_CONST );
		$where_clauses = array();
		$prepare_args  = array();

		foreach ( $filters as $filter ) {
			$where_clauses[] = $this->glob_wpdb->prepare( "{$filter['key']} = %s", $filter['value'] );
		}

		$where_clauses[] = $this->glob_wpdb->prepare( 'dt BETWEEN %s AND %s', $from, $to );

		$filter_sql = implode( ' AND ', $where_clauses );

		$query = "SELECT * FROM {$table_name} WHERE {$filter_sql}";

		return $this->glob_wpdb->get_results( $query );
	}

	/**
	 * Retrieve the top 5 posts based on the number of leads between two dates.
	 *
	 * @param string $from Start date in 'YYYY-MM-DD' format.
	 * @param string $to End date in 'YYYY-MM-DD' format.
	 *
	 * @return array|object|null Database query result for top 5 posts, or null on failure.
	 */
	public function get_top_5_posts( $from, $to ) {
		$table = $this->get_table_name( self::TABLE_NAME_LEADS_CONST );
		$query = $this->glob_wpdb->prepare(
			"SELECT COUNT(id) as count, post_id FROM {$table} WHERE dt BETWEEN %s AND %s GROUP BY post_id ORDER BY count DESC LIMIT 5",
			$from,
			$to
		);

		return $this->glob_wpdb->get_results( $query );
	}

	/**
	 * Get the total number of leads.
	 *
	 * @return int Total number of leads; returns 0 if no leads are found.
	 */
	public function get_total_leads() {
		$table = $this->get_table_name( self::TABLE_NAME_LEADS_CONST );
		$count = $this->glob_wpdb->get_results( "SELECT COUNT(*) as count FROM {$table}" )[0]->count;

		return null === $count ? 0 : (int) $count;
	}

	/**
	 * Retrieve the total number of leads between two dates.
	 *
	 * @param string $from Start date in 'YYYY-MM-DD' format.
	 * @param string $to End date in 'YYYY-MM-DD' format.
	 *
	 * @return int|mixed Total number of leads for the given date range.
	 */
	public function get_total_leads_from_to( $from, $to ) {
		$table = $this->get_table_name( self::TABLE_NAME_LEADS_CONST );

		return $this->glob_wpdb->get_results(
			$this->glob_wpdb->prepare(
				"SELECT COUNT(*) as count FROM {$table} WHERE `dt` BETWEEN %s AND %s",
				$from,
				$to
			)
		)[0]->count;
	}

	/**
	 * Retrieve the total number of leads for the current day.
	 *
	 * @return int|mixed Total number of leads for today.
	 */
	public function get_total_today_leads() {
		$table = $this->get_table_name( self::TABLE_NAME_LEADS_CONST );

		return $this->glob_wpdb->get_results( "SELECT COUNT(*) as count FROM {$table} WHERE dt >= CURDATE()" )[0]->count;
	}

	/**
	 * Retrieve the total number of leads for yesterday.
	 *
	 * @return int|mixed Total number of leads for yesterday.
	 */
	public function get_total_yesterday_leads() {
		$table = $this->get_table_name( self::TABLE_NAME_LEADS_CONST );

		return $this->glob_wpdb->get_results( "SELECT COUNT(*) as count FROM {$table} WHERE dt >= DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND dt < CURDATE()" )[0]->count;
	}

	/**
	 * Retrieve the total number of leads for the last 7 days.
	 *
	 * @return int|mixed Total number of leads for the past week.
	 */
	public function get_total_week_leads() {
		$table = $this->get_table_name( self::TABLE_NAME_LEADS_CONST );

		return $this->glob_wpdb->get_results( "SELECT COUNT(*) as count FROM {$table} WHERE dt >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)" )[0]->count;
	}

	/**
	 * Check if the given domain has a default social record.
	 *
	 * @param string $domain The domain name to check.
	 *
	 * @return int The count of default social records for the domain; returns 0 if none are found.
	 */
	public function check_domain_default_social( $domain ) {
		$table = $this->get_table_name( self::TABLE_NAME_DEFAULT_SOCIAL_CONST );

		return $this->glob_wpdb->get_results( $this->glob_wpdb->prepare( "SELECT COUNT(*) as count FROM {$table} WHERE `domain` = %s", $domain ) )[0]->count;
	}

	/**
	 * Check if the given domain has a default SERP (Search Engine Results Page) record.
	 *
	 * @param string $domain The domain name to check.
	 *
	 * @return int The count of default SERP records for the domain; returns 0 if none are found.
	 */
	public function check_domain_default_serp( $domain ) {
		$table = $this->get_table_name( self::TABLE_NAME_DEFAULT_SERP_CONST );

		return $this->glob_wpdb->get_results( $this->glob_wpdb->prepare( "SELECT COUNT(*) as count FROM {$table} WHERE `domain` = %s", $domain ) )[0]->count;
	}

	/**
	 * Check if any of the given parameters have a default advertisement record.
	 *
	 * @param array $parameters Array of query parameters to check against.
	 *
	 * @return int The count of matching default advertisement records; returns 0 if none are found.
	 */
	public function check_parameter_default_advert( $parameters ) {
		$count      = 0;
		$table_name = $this->get_table_name( self::TABLE_NAME_DEFAULT_ADVERT_CONST );
		foreach ( $parameters as $parameter ) {
			$parameter_explode = explode( '=', $parameter );
			$count             = $this->glob_wpdb->get_results( $this->glob_wpdb->prepare( "SELECT COUNT(*) as count FROM {$table_name} WHERE `parameter` = %s", $parameter_explode[0] ) )[0]->count;
			if ( $count > 0 ) {
				break;
			}
		}

		return $count;
	}

	/**
	 * Retrieve the last lead entry from the database.
	 *
	 * @return object|null An object representing the last lead entry, or null on failure.
	 */
	public function get_last_lead() {
		return $this->glob_wpdb->get_results( "SELECT * FROM {$this->get_table_name(self::TABLE_NAME_LEADS_CONST)} ORDER by id DESC limit 1" );
	}


	/**
	 * Retrieve all forms of a specific post type.
	 *
	 * @param string $post_type The WordPress post type to filter forms by.
	 *
	 * @return array|object|null An array or object containing the forms, or null on failure.
	 */
	private function get_all_forms_by_post_type( $post_type ) {
		return $this->glob_wpdb->get_results(
			$this->glob_wpdb->prepare(
				"SELECT ID, post_title FROM  {$this->get_table_name( 'posts')} WHERE post_type = %s AND post_status <> 'trash'",
				$post_type
			)
		);
	}


	/**
	 * Retrieve all WPForms as an associative array.
	 *
	 * @return array An array containing the WPForms data, including ID, type, and title.
	 */
	public function get_all_forms_wpforms() {
		$data      = array();
		$all_forms = $this->get_all_forms_by_post_type( 'wpforms' );
		foreach ( $all_forms as $key => $form ) {
			$data[ $key ]['id']    = $form->ID;
			$data[ $key ]['type']  = 'wpforms';
			$data[ $key ]['title'] = ( $form->post_title ) ? $form->post_title : '';
		}

		return $data;
	}

	/**
	 * Retrieve all Contact Form 7 forms as an associative array.
	 *
	 * @return array An array containing the Contact Form 7 data, including ID, type, and title.
	 */
	public function get_all_forms_cf7() {
		$data      = array();
		$all_forms = $this->get_all_forms_by_post_type( 'wpcf7_contact_form' );
		foreach ( $all_forms as $key => $form ) {
			$data[ $key ]['id']    = $form->ID;
			$data[ $key ]['type']  = 'cf7';
			$data[ $key ]['title'] = ( $form->post_title ) ? $form->post_title : '';
		}

		return $data;
	}


	/**
	 * Retrieve all Ninja Forms as an associative array.
	 *
	 * @return array An array containing the Ninja Forms data, including ID, type, and title.
	 */
	public function get_all_forms_ninja() {
		$data = array();

		$table_name   = $this->get_table_name( 'nf3_forms' );
		$table_exists = $this->glob_wpdb->get_var( "SHOW TABLES LIKE '$table_name'" );

		if ( $table_exists ) {
			$all_forms = $this->glob_wpdb->get_results( "SELECT id, title FROM {$table_name}" );
			foreach ( $all_forms as $key => $form ) {
				$data[ $key ]['id']    = $form->id;
				$data[ $key ]['type']  = 'ninja';
				$data[ $key ]['title'] = ( $form->title ) ? $form->title : '';
			}
		}

		return $data;
	}

	/**
	 * Get the count of leads for a specific form type and form ID within a date range.
	 *
	 * @param string $form_type The type of the form (e.g., 'cf7', 'ninja', etc.).
	 * @param int $form_id The ID of the form.
	 * @param string $from Start date in 'YYYY-MM-DD' format.
	 * @param string $to End date in 'YYYY-MM-DD' format.
	 *
	 * @return int The count of leads for the specified form type, form ID, and date range.
	 */
	public function get_count_leads_by_form_type( $form_type, $form_id, $from, $to ) {
		$leads_count = $this->glob_wpdb->prepare(
			"SELECT count(*) as count FROM {$this->get_table_name( self::TABLE_NAME_LEADS_CONST)} WHERE `dt` BETWEEN %s AND %s AND form_type = %s AND form_id = %s",
			$from,
			$to,
			$form_type,
			$form_id
		);

		return $this->glob_wpdb->get_results( $leads_count )[0]->count;
	}

	/**
	 * Get the sum of the 'cost' field for leads of a specific form type and form ID within a date range.
	 *
	 * @param string $form_type The type of the form (e.g., 'cf7', 'ninja', etc.).
	 * @param int $form_id The ID of the form.
	 * @param string $from Start date in 'YYYY-MM-DD' format.
	 * @param string $to End date in 'YYYY-MM-DD' format.
	 *
	 * @return float The sum of the 'cost' field for the specified form type, form ID, and date range.
	 */
	public function get_sum_leads_by_form_type( $form_type, $form_id, $from, $to ) {
		$leads_sum = $this->glob_wpdb->prepare(
			"SELECT sum(cost) as sum FROM {$this->get_table_name(self::TABLE_NAME_LEADS_CONST)} WHERE dt BETWEEN %s AND %s AND form_type = %s AND form_id = %s",
			$from,
			$to,
			$form_type,
			$form_id
		);

		return $this->glob_wpdb->get_results( $leads_sum )[0]->sum;
	}

	/**
	 * Get the count of leads within a specified date range.
	 *
	 * @param string $from Start date in 'YYYY-MM-DD' format.
	 * @param string $to End date in 'YYYY-MM-DD' format.
	 *
	 * @return int The count of leads within the specified date range.
	 */
	public function get_count_leads_by_range( $from, $to ) {
		$leads_count = $this->glob_wpdb->prepare(
			"SELECT count(*) as count FROM {$this->get_table_name(self::TABLE_NAME_LEADS_CONST)} WHERE dt BETWEEN %s AND %s",
			$from,
			$to
		);

		return $this->glob_wpdb->get_results( $leads_count )[0]->count;
	}

	/**
	 * Get the sum of the 'cost' field for leads within a specified date range.
	 *
	 * @param string $from Start date in 'YYYY-MM-DD' format.
	 * @param string $to End date in 'YYYY-MM-DD' format.
	 *
	 * @return float The sum of the 'cost' field within the specified date range.
	 */
	public function get_sum_leads_by_range( $from, $to ) {
		$leads_count = $this->glob_wpdb->prepare(
			"SELECT sum(cost) as sum FROM  {$this->get_table_name(self::TABLE_NAME_LEADS_CONST)} WHERE dt BETWEEN %s AND %s",
			$from,
			$to
		);

		return $this->glob_wpdb->get_results( $leads_count )[0]->sum;
	}

	/**
	 * Retrieve the 'status' and 'cost' for a specific target type and identifier.
	 *
	 * @param string $target_type The type of target (e.g., 'email', 'URL', etc.).
	 * @param string $identifier The identifier for the target (e.g., email address, URL slug, etc.).
	 *
	 * @return array|mixed An associative array containing 'status' and 'cost', or an empty array if not found.
	 */
	public function get_status_and_cost_by_target_type( $target_type, $identifier ) {
		$leads_count = $this->glob_wpdb->prepare(
			"SELECT status, cost FROM {$this->get_table_name(self::TABLE_NAME_LEADEE_TARGETS_CONST)} WHERE type = %s AND identifier = %s",
			$target_type,
			$identifier
		);

		$res = $this->glob_wpdb->get_results( $leads_count );
		if ( 0 !== (int) count( $res ) ) {
			return $res[0];
		}

		return array();
	}

	/**
	 * Retrieve the post name by its ID.
	 *
	 * @param int $post_id The ID of the WordPress post.
	 *
	 * @return string The name of the post or the blog name if the ID is zero.
	 */
	public function get_post_name_by_id( $post_id ) {
		if ( intval( $post_id ) === 0 ) {
			return get_bloginfo( 'name' );
		}
		$post_id = intval( $post_id );
		$query   = $this->glob_wpdb->prepare( "SELECT post_title FROM {$this->get_table_name('posts')} WHERE id = %d LIMIT 1", $post_id );
		$res     = $this->glob_wpdb->get_results( $query );
		if ( ! empty( $res[0]->post_title ) ) {
			return $res[0]->post_title;
		} else {
			return __( 'Removed form =', 'leadee' ) . ' ' . $post_id;
		}
	}

	/**
	 * Retrieve the value of a specific option in a setting type.
	 *
	 * @param string $setting_type Type of the setting.
	 * @param string $option The specific option name.
	 *
	 * @return mixed|null Value of the option or null if it doesn't exist.
	 */
	public function get_setting_option_value( $setting_type, $option ) {
		$query  = $this->glob_wpdb->prepare(
			"SELECT * FROM {$this->get_table_name(self::TABLE_NAME_LEADEE_SETTINGS_CONST)} WHERE setting_type = %s AND `option` = %s",
			$setting_type,
			$option
		);
		$result = $this->glob_wpdb->get_results( $query );
		if ( empty( $result ) ) {
			return null;
		}

		return $result[0]->value;
	}

	/**
	 * Check if a table column is enabled.
	 *
	 * @param string $column The column name.
	 *
	 * @return bool True if enabled, false otherwise.
	 */
	public function is_enable_column( $column ) {
		return $this->get_setting_option_value( 'leads-table-columns', $column ) === '1';
	}

	/**
	 * Set the value of a specific option in a setting type.
	 *
	 * @param string $type The setting type.
	 * @param string $option The option name.
	 * @param mixed $value The value to set.
	 *
	 * @return void
	 */
	public function set_setting_option_value( $type, $option, $value ) {
		$this->glob_wpdb->update(
			$this->get_table_name( self::TABLE_NAME_LEADEE_SETTINGS_CONST ),
			array( 'value' => $value ),
			array(
				'setting_type' => $type,
				'option'       => $option,
			)
		);
	}

	/**
	 * Retrieve lead data by source category within a specific date range.
	 *
	 * @param string $source_category The source category.
	 * @param string $day_from Start date in 'YYYY-MM-DD' format.
	 * @param string $day_to End date in 'YYYY-MM-DD' format.
	 *
	 * @return array An array containing lead count information.
	 */
	public function get_data_by_source_category( $source_category, $day_from, $day_to ) {
		$table_name = $this->get_table_name( self::TABLE_NAME_LEADS_CONST );

		$query = $this->glob_wpdb->prepare(
			"SELECT COUNT(*) as count FROM {$table_name} WHERE source_category = %s AND dt BETWEEN %s AND %s",
			$source_category,
			$day_from,
			$day_to
		);

		$leads = $this->glob_wpdb->get_results( $query );

		$result = array();

		foreach ( $leads as $key => $day_res ) {
			$result[] = array( 'count' => $day_res->count );
		}

		return $result;
	}

	/**
	 * Retrieve all lead data for the main chart within a specific date range.
	 *
	 * @param string $from Start date in 'YYYY-MM-DD' format.
	 * @param string $to End date in 'YYYY-MM-DD' format.
	 *
	 * @return array An array containing lead count information.
	 */
	public function get_main_chart_all_data( $from, $to ) {
		$leads  = $this->glob_wpdb->get_results(
			$this->glob_wpdb->prepare(
				"SELECT COUNT(*) as count FROM {$this->get_table_name(self::TABLE_NAME_LEADS_CONST)} WHERE dt BETWEEN %s AND %s",
				$from,
				$to
			)
		);
		$result = array();
		foreach ( $leads as $key => $day_res ) {
			$result[] = array( 'count' => $day_res->count );
		}

		return $result;
	}

	/**
	 * Retrieve the top device operating systems for leads within a specific date range.
	 *
	 * @param string $from Start date in 'YYYY-MM-DD' format.
	 * @param string $to End date in 'YYYY-MM-DD' format.
	 * @param int $top_limit The maximum number of items to return.
	 *
	 * @return array An array containing device operating system information.
	 */
	public function get_device_os_top( $from, $to, $top_limit ) {
		return $this->glob_wpdb->get_results(
			$this->glob_wpdb->prepare(
				"SELECT device_os, count(*) AS count FROM {$this->get_table_name(self::TABLE_NAME_LEADS_CONST)} WHERE dt BETWEEN %s AND %s GROUP BY device_os ORDER BY count DESC LIMIT %d",
				$from,
				$to,
				$top_limit
			)
		);
	}

	/**
	 * Retrieve the top device screen sizes for leads within a specific date range.
	 *
	 * @param string $from Start date in 'YYYY-MM-DD' format.
	 * @param string $to End date in 'YYYY-MM-DD' format.
	 * @param int $top_limit The maximum number of items to return.
	 *
	 * @return array An array containing device screen size information.
	 */
	public function get_device_screen_size_top( $from, $to, $top_limit ) {
		$leads  = $this->glob_wpdb->get_results(
			$this->glob_wpdb->prepare(
				"SELECT device_width, COUNT(*) AS 'count' FROM {$this->get_table_name(self::TABLE_NAME_LEADS_CONST)} WHERE dt BETWEEN %s AND %s GROUP BY device_width ORDER BY count DESC LIMIT %d",
				$from,
				$to,
				$top_limit
			)
		);
		$result = array();
		$i      = 0;
		foreach ( $leads as $key => $day_res ) {
			if ( $day_res->count > 0 ) {
				$result[ $i ]['width'] = $day_res->device_width;
				$result[ $i ]['count'] = $day_res->count;
			}
			++ $i;
		}

		return $result;
	}

	/**
	 * Retrieve the latest leads up to the given limit.
	 *
	 * @param int $limit The maximum number of leads to retrieve.
	 *
	 * @return array An array of the latest leads.
	 */
	public function get_last_leads( $limit ) {
		return $this->glob_wpdb->get_results(
			$this->glob_wpdb->prepare(
				"SELECT * FROM {$this->get_table_name(self::TABLE_NAME_LEADS_CONST)} ORDER BY id DESC LIMIT %d",
				$limit
			)
		);
	}

	/**
	 * Determine the source category based on the source domain and parameters.
	 *
	 * @param string $source_domain The domain where the lead originated.
	 * @param string $parameters Any additional URL parameters.
	 *
	 * @return string The source category ('direct', 'serp', 'social', 'referal', 'advert').
	 */
	public function get_source_category( $source_domain, $parameters ) {
		$source_category = 'direct';

		if ( ! empty( $source_domain ) ) {
			if ( $this->check_domain_default_serp( $source_domain ) ) {
				$source_category = 'serp';
			} elseif ( $this->check_domain_default_social( $source_domain ) > 0 ) {
				$source_category = 'social';
			} else {
				$source_category = 'referal';
			}
		}
		if ( $this->check_parameter_default_advert( $parameters ) > 0 ) {
			$source_category = 'advert';
		}

		return $source_category;
	}

	/**
	 * Save or update the target setting based on type and identifier.
	 *
	 * @param string $type The type of the target.
	 * @param string $identifier The identifier of the target.
	 * @param float $cost The cost associated with the target.
	 * @param string $status The status of the target.
	 *
	 * @return void
	 */
	public function save_target_setting( $type, $identifier, $cost, $status ) {
		$query_count       = $this->glob_wpdb->prepare(
			"SELECT COUNT(*) as count FROM {$this->get_table_name(self::TABLE_NAME_LEADEE_TARGETS_CONST)} WHERE type = %s AND identifier = %s",
			$type,
			$identifier
		);
		$count_find_target = $this->glob_wpdb->get_var( $query_count );

		if ( 0 === (int) $count_find_target ) {
			$this->create_target_settings( $type, $identifier, $cost, $status );
		} else {
			$this->save_target_settings( $type, $identifier, $cost, $status );
		}
	}

	/**
	 * Write a new lead entry.
	 *
	 * @param int $post_id The ID of the associated WordPress post.
	 * @param int $form_id The ID of the form used to capture the lead.
	 * @param array $fields The form fields.
	 * @param array $data_arr The submitted data.
	 * @param string $form_type The type of form used.
	 * @param float $lead_cost The cost of the lead.
	 *
	 * @return void
	 */
	public function write_lead( $post_id, $form_id, $fields, $data_arr, $form_type, $lead_cost ) {
		$post_id   = intval( $post_id );
		$form_id   = intval( $form_id );
		$form_type = sanitize_text_field( $form_type );
		$lead_cost = floatval( $lead_cost );

		$domain = isset( $data_arr['domain'] ) ? sanitize_text_field( wp_unslash( $data_arr['domain'] ) ) : '';

		$source_category        = sanitize_text_field( wp_unslash( $data_arr['source_category'] ) );
		$first_url_parameters   = sanitize_text_field( wp_unslash( $data_arr['first_url_parameters'] ) );
		$device_type            = sanitize_text_field( wp_unslash( $data_arr['device_type'] ) );
		$device_os              = sanitize_text_field( wp_unslash( $data_arr['device_os'] ) );
		$device_os_version      = sanitize_text_field( wp_unslash( $data_arr['device_os_version'] ) );
		$device_browser_name    = sanitize_text_field( wp_unslash( $data_arr['device_browser_name'] ) );
		$device_browser_version = sanitize_text_field( wp_unslash( $data_arr['device_browser_version'] ) );
		$user_agent             = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : null;

		$screen_height = filter_input( INPUT_COOKIE, 'device_height', FILTER_VALIDATE_INT );
		$screen_height = false !== $screen_height ? $screen_height : 0;

		$screen_width = filter_input( INPUT_COOKIE, 'device_width', FILTER_VALIDATE_INT );
		$screen_width = false !== $screen_width ? $screen_width : 0;

		$current_time = gmdate( 'Y-m-d H:i:s' );

		$this->glob_wpdb->insert(
			$this->get_table_name( self::TABLE_NAME_LEADS_CONST ),
			array(
				'post_id'                => $post_id,
				'form_type'              => $form_type,
				'form_id'                => $form_id,
				'cost'                   => $lead_cost,
				'source'                 => $domain,
				'source_category'        => $source_category,
				'fields'                 => wp_json_encode( $fields, JSON_UNESCAPED_UNICODE ),
				'first_url_parameters'   => $first_url_parameters,
				'device_type'            => $device_type,
				'device_os'              => $device_os,
				'device_os_version'      => $device_os_version,
				'device_browser_name'    => $device_browser_name,
				'device_browser_version' => $device_browser_version,
				'device_height'          => $screen_height,
				'device_width'           => $screen_width,
				'user_agent'             => $user_agent,
				'dt'                     => $current_time,
			)
		);
	}

	/**
	 * Generate data for the main chart based on the given period and colors.
	 *
	 * @param array $period An array containing the periods for which data should be generated.
	 *
	 * @return array An associative array containing 'labels', 'data', and 'colors' for the main chart.
	 */
	public function get_data_main_chart( $period ) {
		$colors = array();
		foreach ( $period as $p ) {
			$colors[] = $p['color'];
		}
		$data_main_chart = array(
			'labels' => $this->get_labels( $period ),
			'data'   => $this->get_main_chart_data( $period ),
			'colors' => $colors,
		);

		return $data_main_chart;
	}

	/**
	 * Retrieve data related to screen sizes within a given time range and up to a specified limit.
	 *
	 * @param string $from Start date of the range.
	 * @param string $to End date of the range.
	 * @param int $top_limit Maximum number of entries to retrieve.
	 *
	 * @return array An associative array containing 'labels', 'data', and 'colors' for screen sizes.
	 */
	public function get_data_screen_size( $from, $to, $top_limit ) {
		$screens = $this->get_device_screen_size_top( $from, $to, $top_limit );

		$labels              = array();
		$colors_all          = array( '#36a2eb', '#8AC44B', '#FCC02A', '#6e62ef', '#263238', '#ddd', '#ggg' );
		$colors              = array();
		$screens_count_array = array();
		$i                   = 0;
		foreach ( $screens as $screen ) {
			$labels[]                  = $screen['width'];
			$colors[]                  = $colors_all[ $i ];
			$screens_count_array[ $i ] = $screen['count'];
			++ $i;
		}

		$data_screen_size = array(
			'labels' => $labels,
			'data'   => $screens_count_array,
			'colors' => $colors,
		);

		return $data_screen_size;
	}

	/**
	 * Generate data for the source-specific chart based on a given period.
	 *
	 * @param array $period An array containing the periods for which data should be generated.
	 *
	 * @return array An associative array containing 'labels' and 'datasets' for the source-specific chart.
	 */
	public function get_data_chart_source( $period ) {
		$data_chart_source_datasets = array(
			0 => array(
				'data'        => $this->get_values_for_type_source( $period, $this->code_serp ),
				'label'       => 'Search engines',
				'borderColor' => '#2a74b9',
				'fill'        => false,
			),
			1 => array(
				'data'        => $this->get_values_for_type_source( $period, $this->code_advert ),
				'label'       => 'Advertising',
				'borderColor' => '#8ac44c',
				'fill'        => false,
			),
			2 => array(
				'data'        => $this->get_values_for_type_source( $period, $this->code_social ),
				'label'       => 'Social networks',
				'borderColor' => '#fbc02a',
				'fill'        => false,
			),
			3 => array(
				'data'        => $this->get_values_for_type_source( $period, $this->code_direct ),
				'label'       => 'Direct visits',
				'borderColor' => '#635e6f',
				'fill'        => false,
			),
			4 => array(
				'data'        => $this->get_values_for_type_source( $period, $this->code_referal ),
				'label'       => 'Website referrals',
				'borderColor' => '#d62d30',
				'fill'        => false,
			),
		);
		$data_chart_source          = array(
			'labels'   => $this->get_labels( $period ),
			'datasets' => $data_chart_source_datasets,
		);

		return $data_chart_source;
	}

	/**
	 * Retrieve data values for a specific type of source for each day within a given period.
	 *
	 * @param array $days An array containing the days within the period.
	 * @param string $type_source The type of source for which to retrieve data.
	 *
	 * @return array An array containing data values for each day within the given period.
	 */
	private function get_values_for_type_source( $days, $type_source ) {

		$type_trafic_data = array();
		foreach ( $days as $day ) {
			$from  = $day['range']['from'];
			$to    = $day['range']['to'];
			$data  = $this->get_data_by_source_category( $type_source, $from, $to );
			$count = array();
			foreach ( $data as $key => $c ) {
				$count[ $key ] = $c['count'];
			}
			$type_trafic_data[] = $count[0];
		}

		return $type_trafic_data;
	}

	/**
	 * Retrieve main chart data for each day within a given period.
	 *
	 * @param array $days An array containing the days within the period.
	 *
	 * @return array An array containing data values for each day within the given period.
	 */
	private function get_main_chart_data( $days ) {
		$res_data = array();
		foreach ( $days as $day ) {
			$from  = $day['range']['from'];
			$to    = $day['range']['to'];
			$data  = $this->get_main_chart_all_data( $from, $to );
			$count = array();
			foreach ( $data as $key => $c ) {
				$count[ $key ] = $c['count'];
			}
			$res_data[] = $count[0];
		}

		return $res_data;
	}

	/**
	 * Generate an array of labels based on a given set of days.
	 *
	 * @param array $days An array of days, each containing a 'range' with a 'from' date.
	 *
	 * @return array An array of date labels formatted as 'm/d/y'.
	 *
	 * @throws Exception If DateTime creation fails.
	 */
	private function get_labels( $days ) {
		$labels = array();
		foreach ( $days as $day ) {
			$from     = new DateTime( $day['range']['from'] );
			$labels[] = $from->format( 'm/d/y' );
		}

		return $labels;
	}

	/**
	 * Generate data for new leads within a specified timezone and limit.
	 *
	 * @param string $timezone The timezone to be used for lead dates.
	 * @param int $limit The maximum number of leads to fetch.
	 *
	 * @return array An array of new leads, each with 'id', 'dt', and 'text' keys.
	 *
	 * @throws Exception If DateTime creation fails.
	 */
	public function get_data_new_leads( $timezone, $limit ) {
		$data_new_leads = array();
		foreach ( $this->get_last_leads( $limit ) as $lead ) {
			$date_of_lead = new DateTime( $lead->dt );
			$text         = array();
			$fields       = json_decode( $lead->fields );

			foreach ( $fields as $field ) {
				$value  = is_string( $field->value ) ? $field->value : wp_json_encode( $field->value );
				$text[] = $value;
			}
			$data_new_leads[] = array(
				'id'   => $lead->post_id,
				'dt'   => $this->ago( $date_of_lead ),
				'text' => implode( ' ', $text ),
			);
		}

		return $data_new_leads;
	}

	/**
	 * Convert the DateTime of a lead to a 'time ago' format based on a given timezone.
	 *
	 * @param DateTime $date_of_lead The date and time when the lead was generated.
	 *
	 * @return string The 'time ago' representation of the lead's DateTime.
	 *
	 * @throws Exception If DateTime manipulation fails.
	 */
	public function ago( $date_of_lead ) {
		$now_with_timezone = new DateTime( 'now', new DateTimeZone( 'UTC' ) );
		$now_time          = new DateTime( $now_with_timezone->format( 'Y-m-d H:i:s' ) );
		$lead_time         = new DateTime( $date_of_lead->format( 'Y-m-d H:i:s' ) );
		$diff              = $now_time->getTimestamp() - $lead_time->getTimestamp();
		if ( $diff < 60 && $diff > 0 ) {
			return esc_html__( 'just now', 'leadee' );
		}

		if ( $diff < 86400 ) {
			if ( $diff <= 3600 ) {
				// Mins
				$min = floor( $diff / 60 );

				$str = sprintf(
					'%s%s',
					( 0 !== (int) $min ) ? $min . ' m ' : '',
					'ago'
				);
			} else {
				// Hours
				$hrs = floor( $diff / 3600 );
				$str = sprintf(
					'%s%s',
					( 0 !== (int) $hrs ) ? $hrs . ' h ' : '',
					'ago'
				);
			}

			return $str;
		} else {
			return $date_of_lead->format( 'm/d/Y' );
		}
	}

	/**
	 * Retrieve various lead-related counters and target data for the current month.
	 *
	 * @return array An associative array containing information about the current target,
	 * today's lead count, yesterday's lead count, this week's lead count, and the
	 * difference between today's and yesterday's lead counts.
	 */
	public function get_counters_data() {
		$counter_today        = $this->get_total_today_leads();
		$counter_yesterday    = $this->get_total_yesterday_leads();
		$counter_week         = $this->get_total_week_leads();
		$counter_today_diff   = $counter_today - $counter_yesterday;
		$current_targets_data = $this->read_current_month_targets_data();

		$data = array(
			'isSet'    => true,
			'target'   => array(
				'targetUser'    => $current_targets_data['month-target'],
				'targetCurrent' => $current_targets_data['leads-month-sum'],
			),
			'counters' => array(
				'counterToday'     => $counter_today,
				'counterTodayDiff' => $counter_today_diff,
				'counterYesterday' => $counter_yesterday,
				'counterWeek'      => $counter_week,
			),
		);

		return $data;
	}

	/**
	 * Retrieve the operating systems used by the top clients within a specified date range.
	 *
	 * @param string $from The start date for the range in 'YYYY-MM-DD' format.
	 * @param string $to The end date for the range in 'YYYY-MM-DD' format.
	 * @param int $top_limit The maximum number of top clients to include.
	 *
	 * @return array An associative array containing the top operating systems and
	 * the total count of all items.
	 */
	public function get_os_clients_data_by_top( $from, $to, $top_limit ) {
		$getOs   = $this->get_device_os_top( $from, $to, $top_limit );
		$osCount = 0;
		foreach ( $getOs as $os ) {
			$osCount = $osCount + (int) $os->count;
		}

		return array(
			'items'    => $getOs,
			'allItems' => $osCount,
		);
	}

	/**
	 * Retrieve data about the most popular pages within a given date range.
	 *
	 * @param string $from The start date for the range in 'YYYY-MM-DD' format.
	 * @param string $to The end date for the range in 'YYYY-MM-DD' format.
	 *
	 * @return array An array containing data about the popular pages.
	 */
	public function get_popular_pages_data( $from, $to ) {
		$data_popular_pages = array();
		$top_posts          = $this->get_top_5_posts( $from, $to );
		$all_posts          = $this->get_total_leads_from_to( $from, $to );

		foreach ( $top_posts as $post ) {
			if ( 0 === (int) $post->post_id ) {
				$title        = get_bloginfo( 'name' );
				$url_relative = '';
			} else {
				$title        = get_the_title( $post->post_id );
				$url_relative = '';
			}

			$url = get_site_url() . '/?post_type=undefined&p=' . $post->post_id;
			if ( 0 === (int) $post->post_id ) {
				$url = get_site_url();
			}

			if ( 0 !== (int) $all_posts ) {
				$percentage = round( ( (int) $post->count / (int) $all_posts * 100 ), 2 );
			} else {
				$percentage = 0;
			}

			$data_popular_pages[] = array(
				'title'        => $title,
				'url'          => $url,
				'url_relative' => $url_relative,
				'count'        => $post->count,
				'all'          => $all_posts,
				'percent'      => $percentage,
			);
		}

		return $data_popular_pages;
	}

	/**
	 * Retrieve period-related data using a given calendar range and timezone.
	 *
	 * @param string $from Start date in 'YYYY-MM-DD' format.
	 * @param string $to End date in 'YYYY-MM-DD' format.
	 * @param string $timezone Timezone identifier.
	 *
	 * @return array An associative array containing the processed calendar data.
	 */
	public function get_period_data_from_calend( $from, $to, $timezone ) {
		$param                    = array();
		$param['from']            = $from;
		$param['to']              = $to;
		$param['out_date_format'] = 'Y-m-d H:i:s';
		$param['timezone']        = $timezone;

		return $this->leadee_color_graf->leadee_color_date( $param );
	}

	/**
	 * Retrieve the most recent lead data based on the specified timezone.
	 *
	 * @param string $timezone Timezone identifier.
	 *
	 * @return array An associative array containing information about the most recent lead.
	 * @throws Exception If DateTime encounters an error.
	 */
	public function get_last_lead_data( $timezone ) {
		$data_new_leads = array();
		$leads          = $this->get_last_lead();
		if ( ! empty( $leads ) ) {
			$last_lead    = $leads[0];
			$date_of_lead = new DateTime( $last_lead->dt );
			$text         = array();
			foreach ( json_decode( $last_lead->fields ) as $field ) {
				$text[] = $field->value;
			}

			$data_new_leads = array(
				'dt'   => $this->ago( $date_of_lead ),
				'text' => implode( ' ', $text ),
			);
		}

		return $data_new_leads;
	}

	/**
	 * Retrieve the current month's target data for leads.
	 *
	 * @return array An associative array containing the current month's target,
	 * the count of leads for the current month, and the sum of leads for the current month.
	 */
	public function read_current_month_targets_data() {
		$month_target              = $this->get_setting_option_value( 'setting-target', 'month-target' );
		$format_for_db             = 'Y-m-d';
		$first_day_month           = new DateTime( 'first day of this month', new DateTimeZone( 'UTC' ) );
		$first_day_month_formatted = $first_day_month->format( $format_for_db ) . ' 00:00:00';
		$today_formatted           = gmdate( $format_for_db ) . ' 23:59:59';

		$leads_month_count = $this->get_count_leads_by_range( $first_day_month_formatted, $today_formatted );
		$leads_month_sum   = $this->get_sum_leads_by_range( $first_day_month_formatted, $today_formatted );

		return array(
			'month-target'      => $month_target,
			'leads-month-count' => $leads_month_count,
			'leads-month-sum'   => $leads_month_sum,
		);
	}

	/**
	 * Scans all forms and saves them. Optionally saves even if forms are empty.
	 *
	 * @param bool $is_need_save_empty_forms Whether or not to save empty forms.
	 *
	 * @return array An array containing the scanned form data.
	 */
	public function scan_all_forms( $is_need_save_empty_forms = false ) {
		$forms  = array_merge( $this->get_all_forms_cf7(), $this->get_all_forms_wpforms(), $this->get_all_forms_ninja() );
		$result = array();
		foreach ( $forms as $key => $form ) {
			$status_and_cost = $this->get_status_and_cost_by_target_type( $form['type'], $form['id'] );

			$form_id    = isset( $form['id'] ) ? $form['id'] : null;
			$form_title = isset( $form['title'] ) ? $form['title'] : null;
			$form_type  = isset( $form['type'] ) ? $form['type'] : null;
			$status     = isset( $status_and_cost->status ) ? $status_and_cost->status : null;
			$cost       = isset( $status_and_cost->cost ) ? $status_and_cost->cost : null;

			$result[] = array(
				'id'     => $form_id,
				'title'  => $form_title,
				'type'   => $form_type,
				'status' => $status,
				'sum'    => $cost,
			);

			if ( $is_need_save_empty_forms ) {
				$this->create_target_settings( $form['type'], $form['id'], 1, 1 );
			}
		}

		return $result;
	}

	/**
	 * Creates a new target setting record in the database.
	 *
	 * @param string $type Type of the target setting, e.g., "monthly", "quarterly", etc.
	 * @param string $identifier Unique identifier for the target setting.
	 * @param int $cost Cost associated with the target.
	 * @param int $status Status code for the target setting; generally 0 for inactive, 1 for active.
	 */
	public function create_target_settings( $type, $identifier, $cost, $status ) {
		$this->glob_wpdb->query(
			$this->glob_wpdb->prepare(
				"INSERT IGNORE INTO {$this->get_table_name(self::TABLE_NAME_LEADEE_TARGETS_CONST)} (`type`, `identifier`, `cost`, `status`) VALUES (%s, %s, %d, %d)",
				$type,
				$identifier,
				$cost,
				$status
			)
		);
	}

	/**
	 * Updates an existing target setting in the database.
	 *
	 * @param string $type Type of the target setting, e.g., "monthly", "quarterly", etc.
	 * @param string $identifier Unique identifier for the target setting.
	 * @param int $cost Cost associated with the target.
	 * @param int $status Status code for the target setting; generally 0 for inactive, 1 for active.
	 */
	private function save_target_settings( $type, $identifier, $cost, $status ) {
		$this->glob_wpdb->update(
			$this->get_table_name( self::TABLE_NAME_LEADEE_TARGETS_CONST ),
			array(
				'cost'   => $cost,
				'status' => $status,
			),
			array(
				'type'       => $type,
				'identifier' => $identifier,
			),
			array(
				'%d',
				'%d',
			),
			array(
				'%s',
				'%s',
			)
		);
	}

	/**
	 * Constructs an SQL query based on various filter and search criteria.
	 *
	 * @param string $from The start date for filtering records.
	 * @param string $to The end date for filtering records.
	 * @param string $order_by_column The column name by which to sort the results.
	 * @param string $order_asc_desc The sort order ("ASC" for ascending, "DESC" for descending).
	 * @param array $filters An associative array of additional filter criteria.
	 * @param int $start The starting index for the result set, used for pagination.
	 * @param int $limit The maximum number of records to return, used for pagination.
	 * @param string $search_text Text to be used for search queries within the result set.
	 *
	 * @return mixed The constructed SQL query string.
	 */
	private function take_sql_query_for_filter_and_search( $from, $to, $order_by_column, $order_asc_desc, $filters, $start, $limit, $search_text ) {
		$allowed_columns  = array(
			'form_type',
			'form_id',
			'source',
			'source_category',
			'fields',
			'first_url_parameters',
			'device_type',
			'device_os',
			'device_os_version',
			'device_browser_name',
			'device_browser_version',
			'device_height',
			'device_width',
			'user_agent',
			'dt',
		);
		$allowed_order_by = array( 'ASC', 'DESC' );

		$search_text = esc_sql( $search_text );

		if ( ! in_array( $order_by_column, $allowed_columns, true ) ) {
			$order_by_column = 'dt';
		}

		if ( ! in_array( strtoupper( $order_asc_desc ), $allowed_order_by, true ) ) {
			$order_asc_desc = 'DESC';
		}

		$search_sql    = '';
		$search_values = array();
		if ( strlen( $search_text ) > 0 ) {
			$search_terms = array();
			foreach ( $allowed_columns as $column ) {
				if ( 'dt' !== $column ) {
					$search_terms[]  = "$column LIKE %s";
					$search_values[] = '%' . $search_text . '%';
				}
			}
			if ( ! empty( $search_terms ) ) {
				$search_sql = '(' . implode( ' OR ', $search_terms ) . ') AND ';
			}
		}

		$filter_sql = '';
		$values     = array();

		foreach ( $filters as $filter ) {
			if ( in_array( $filter['key'], $allowed_columns, true ) ) {
				$filter_sql .= $filter['key'] . ' = %s AND ';
				$values[]   = $filter['value'];
			}
		}

		$prepare_values = array_merge(
			$search_values,
			$values,
			array( $from, $to, (int) $start, (int) $limit )
		);

		$query_for_prepare =
			"SELECT * FROM {$this->get_table_name( self::TABLE_NAME_LEADS_CONST )} WHERE {$search_sql} {$filter_sql} dt BETWEEN %s AND %s ORDER BY {$order_by_column} {$order_asc_desc} LIMIT %d, %d";

		$query = $this->glob_wpdb->prepare( $query_for_prepare, $prepare_values );

		return $query;
	}

	/**
	 * Scan all user forms and save it.
	 *
	 * @param boolean $is_need_save_empty_forms is_need_save_empty_forms.
	 *
	 * @return array
	 */
	public function scan_all_froms( $is_need_save_empty_forms = false ) {
		$forms  = array_merge( $this->get_all_forms_cf7(), $this->get_all_forms_wpforms(), $this->get_all_forms_ninja() );
		$result = array();
		foreach ( $forms as $key => $form ) {
			$status_and_cost = $this->get_status_and_cost_by_target_type( $form['type'], $form['id'] );

			$form_id    = isset( $form['id'] ) ? $form['id'] : null;
			$form_title = isset( $form['title'] ) ? $form['title'] : null;
			$form_type  = isset( $form['type'] ) ? $form['type'] : null;
			$status     = isset( $status_and_cost->status ) ? $status_and_cost->status : null;
			$cost       = isset( $status_and_cost->cost ) ? $status_and_cost->cost : null;

			$result[] = array(
				'id'     => $form_id,
				'title'  => $form_title,
				'type'   => $form_type,
				'status' => $status,
				'sum'    => $cost,
			);

			if ( $is_need_save_empty_forms ) {
				$this->create_target_settings( $form['type'], $form['id'], 1, 1 );
			}
		}

		return $result;
	}
}
