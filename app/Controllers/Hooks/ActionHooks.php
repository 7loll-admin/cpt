<?php
/**
 * Main ActionHooks class.
 *
 * @package TinySolutions\cptwooint
 */

namespace TinySolutions\cptwooint\Controllers\Hooks;

defined( 'ABSPATH' ) || exit();

/**
 * Main ActionHooks class.
 */
class ActionHooks {
	/**
	 * Init Hooks.
	 *
	 * @return void
	 */
	public static function init_hooks() {
		add_action( 'wp_ajax_cptwooint_plugin_activation', [ __CLASS__, 'activate_plugin'] );
	}

	public static function activate_plugin() {
		$return = [
			'success' => false,
		];
		error_log( print_r( $_POST, true ) . "\n\n", 3, __DIR__ . '/the_log.txt' );
		if ( ! Fns::verify_nonce() ) {
			wp_send_json_error($return);
		}
		wp_die();
	}

}
