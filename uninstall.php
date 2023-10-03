<?php
// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

function leadee_delete_plugin() {
	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}leadee_base_default_social" );
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}leadee_base_default_serp" );
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}leadee_base_default_advert" );
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}leadee_settings" );
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}leadee_targets" );
}

leadee_delete_plugin();
