<?php
/**
 * Fns Helpers class
 *
 * @package  TinySolutions\cptwooint
 */

namespace TinySolutions\cptwooint\Helpers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Fns class
 */
class Fns {

	/**
	 * @param $plugin_file_path
	 *
	 * @return bool
	 */
	public static function is_plugins_installed( $plugin_file_path = null ) {
		$installed_plugins_list = get_plugins();

		return isset( $installed_plugins_list[ $plugin_file_path ] );
	}

	/**
	 *  Verify nonce.
	 *
	 * @return bool
	 */
	public static function verify_nonce() {
		$nonce = isset( $_REQUEST[ cptwooint()->nonceId ] ) ? $_REQUEST[ cptwooint()->nonceId ] : null;
		if ( wp_verify_nonce( $nonce, cptwooint()->nonceId ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @return false|string
	 */
	public static function get_options() {
		$defaults = array(
			'selected_post_types' => []
		);
		$options  = get_option( 'cptwooint_settings' );
		return wp_parse_args( $options, $defaults );
	}

	/**
	 * @return array|int[]|string[]
	 */
	public static function supported_post_types() {
		$options = self::get_options();
		return ! empty( $options['selected_post_types'] ) ? array_keys( $options['selected_post_types'] ) : [];
	}

	/**
	 * @return array|int[]|string[]
	 */
	public static function meta_key( $current_post_type ) {
		$options = self::get_options();
		return ! empty( $options['selected_post_types'][$current_post_type] ) ? $options['selected_post_types'][$current_post_type] : '';
	}

	/**
	 * @return array|int[]|string[]
	 */
	public static function is_supported( $post_type ) {
		$supported_post_types = self::supported_post_types();
		return in_array( $post_type, $supported_post_types );
	}

	/**
	 * @return true
	 */
	public static function clear_data_cache() {
		$prefix = '_transient_cptwooint_';
		// Get all transients with the specified prefix
		global $wpdb;
		$query = $wpdb->prepare(
			"SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s",
			$wpdb->esc_like($prefix) . '%'
		);
		$transients = $wpdb->get_results($query);
		// Delete transients
		$deleted = [];
		foreach ($transients as $transient) {
			$trans = str_replace('_transient_', '', $transient->option_name);
			if( delete_transient( $trans ) ){
				$deleted[] = $trans;
			}
		}
		return count( $transients ) === count( $deleted );
	}
}
