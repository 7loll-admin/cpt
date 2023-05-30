<?php

namespace TinySolutions\cptwooint\Controllers\Admin;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use TinySolutions\cptwooint\Traits\SingletonTrait;

/**
 * Sub menu class
 *
 * @author Mostafa <mostafa.soufi@hotmail.com>
 */
class AdminMenu {
	/**
	 * Singleton
	 */
	use SingletonTrait;

	/**
	 * Autoload method
	 * @return void
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );
	}

	/**
	 * Register submenu
	 * @return void
	 */
	public function register_sub_menu() {
		add_menu_page(
			esc_html__( 'WC Integration', 'cptwooint' ),
			esc_html__( 'WC Integration', 'cptwooint' ),
			'manage_options',
			'cptwooint-admin',
			[ $this, 'page_callback' ],
			'dashicons-tickets',
			6
		);
	}

	/**
	 * Render submenu
	 * @return void
	 */
	public function page_callback() {
		echo '<div class="wrap"><div id="cptwooint_root"></div></div>';
	}

}
