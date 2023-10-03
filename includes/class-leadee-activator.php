<?php
// disabling phpcs in this is safe since there is no external users data
// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
class LEADEE_Activator {

	private $wpdb;

	public function __construct() {
		global $wpdb;
		$this->wpdb      = $wpdb;
		$this->functions = new LEADEE_Functions();
	}

	public function activate() {
		$this->leadee_install_plugin();
	} // activate()


	private function leadee_install_plugin() {
		// leads table
		$this->wpdb->query(
			sprintf(
				'CREATE TABLE IF NOT EXISTS %s (`id` INT NOT NULL AUTO_INCREMENT,
                                                                 `post_id` INT NOT NULL,
                                                                 `form_type` VARCHAR(200) NOT NULL,
                                                                 `form_id` INT NOT NULL,
                                                                 `cost` DECIMAL(15,2),
                                                                 `source` VARCHAR(700) NOT NULL,
                                                                 `source_category` VARCHAR(50) NOT NULL,
                                                                 `fields` TEXT NOT NULL,
                                                                 `first_url_parameters`  VARCHAR(700) NOT NULL,
                                                                 `device_type` VARCHAR(30) NOT NULL,
                                                                 `device_os` VARCHAR(30) NOT NULL,
                                                                 `device_os_version` VARCHAR(300) NOT NULL,
                                                                 `device_browser_name` VARCHAR(300) NOT NULL,
                                                                 `device_browser_version` VARCHAR(300) NOT NULL,
                                                                 `device_height` INT NOT NULL,      
                                                                 `device_width` INT NOT NULL,
                                                                 `user_agent` VARCHAR(700) NOT NULL,
                                                                 `dt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                                                  PRIMARY KEY (`id`)) ENGINE = InnoDB;',
				$this->wpdb->prefix . 'leadee_leads'
			)
		);

		// default base for source classification
		$this->wpdb->query(
			sprintf(
				'CREATE TABLE IF NOT EXISTS %s (`id` INT NOT NULL AUTO_INCREMENT,
                                                                 `domain` VARCHAR(600) NOT NULL,
                                                                  PRIMARY KEY (`id`), 
                                                                  UNIQUE KEY (`domain`)) ENGINE = InnoDB;',
				$this->wpdb->prefix . 'leadee_base_default_social'
			)
		);

		$this->wpdb->query(
			sprintf(
				'CREATE TABLE IF NOT EXISTS %s (`id` INT NOT NULL AUTO_INCREMENT,
                                                                 `domain` VARCHAR(600) NOT NULL,
                                                                  PRIMARY KEY (`id`), 
                                                                  UNIQUE KEY (`domain`)) ENGINE = InnoDB;',
				$this->wpdb->prefix . 'leadee_base_default_serp'
			)
		);

		$this->wpdb->query(
			sprintf(
				'CREATE TABLE IF NOT EXISTS %s (`id` INT NOT NULL AUTO_INCREMENT,
                                                                 `parameter` VARCHAR(600) NOT NULL,
                                                                  PRIMARY KEY (`id`), 
                                                                  UNIQUE KEY (`parameter`)) ENGINE = InnoDB;',
				$this->wpdb->prefix . 'leadee_base_default_advert'
			)
		);

		// settings
		$this->wpdb->query(
			sprintf(
				'CREATE TABLE IF NOT EXISTS %s (`id` INT NOT NULL AUTO_INCREMENT,
                                                                 `setting_type` VARCHAR(700) NOT NULL,
                                                                 `option` VARCHAR(700) NOT NULL,
                                                                 `value` VARCHAR(700) NOT NULL,
                                                                  PRIMARY KEY (`id`), 
                                                                  UNIQUE KEY (`option`)) ENGINE = InnoDB;',
				$this->wpdb->prefix . 'leadee_settings'
			)
		);

		// goal settings
		$this->wpdb->query(
			sprintf(
				'CREATE TABLE  IF NOT EXISTS %s ( `id` INT NOT NULL AUTO_INCREMENT,
                                                                    `type` VARCHAR(50) NOT NULL,
                                                                    `identifier` VARCHAR(100) NOT NULL,
                                                                    `status` INT NOT NULL, 
                                                                    `cost` DECIMAL(15,2), 
                                                                     PRIMARY KEY (`id`),
                                                                     UNIQUE KEY `type_identifier_unique` (`type`, `identifier`)) ENGINE = InnoDB;',
				$this->wpdb->prefix . 'leadee_targets'
			)
		);

		// insert default lines

		$settings = array(
			array( 'leads-table-columns', 'source', 1 ),
			array( 'leads-table-columns', 'first_url_parameters', 1 ),
			array( 'leads-table-columns', 'device_browser', 1 ),
			array( 'leads-table-columns', 'device_screen_size', 1 ),
			array( 'leads-table-columns', 'dt', 1 ),
			array( 'setting-target', 'month-target', 10 ),
		);

		foreach ( $settings as $setting ) {
			$this->wpdb->query(
				$this->wpdb->prepare(
					'INSERT IGNORE INTO '
										. $this->wpdb->prefix . 'leadee_settings (`setting_type`, `option`, `value`) VALUES (%s, %s, %d)',
					$setting[0],
					$setting[1],
					$setting[2]
				)
			);
		}

		// add dictionaries for detect traffic
		$this->insert_base( 'advert' );
		$this->insert_base( 'serp' );
		$this->insert_base( 'social' );

		// scan and save all detect forms
		$this->functions->scan_all_froms( true );
	}

	// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_fopen
	// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_fclose
	function insert_base( $type ) {
		$allowed_types = array( 'advert', 'serp', 'social' );
		if ( ! in_array( $type, $allowed_types ) ) {
			return;
		}

		$file_path = plugin_dir_path( __FILE__ ) . 'default_base/' . $type . '.txt';
		$file      = fopen( $file_path, 'r' );
		if ( ! $file ) {
			return;
		}

		$column = ( $type === 'advert' ) ? 'parameter' : 'domain';
		$query  = "INSERT IGNORE INTO {$this->wpdb->prefix}leadee_base_default_{$type} ($column) VALUES";

		while ( ( $line = fgets( $file ) ) !== false ) {
			$line   = trim( $line );
			$query .= "('$line'),";
		}

		fclose( $file );
		$query = rtrim( $query, ',' );

		$this->wpdb->query( $query );
	}
	// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_fopen
	// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_fclose
} // class
// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared
