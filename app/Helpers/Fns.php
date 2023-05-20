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
		$nonce     = isset( $_REQUEST[ cptwooint()->nonceId ] ) ? $_REQUEST[ cptwooint()->nonceId ] : null;
		$nonceText = cptwooint()->nonceText;
		if ( wp_verify_nonce( $nonce, $nonceText ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @return false|string
	 */
	public static function get_options() {
		$defaults = array(

		);
		$options  = get_option( 'cptwooint_settings' );
		return wp_parse_args( $options, $defaults );
	}


}
