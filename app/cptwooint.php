<?php
/**
 * Main initialization class.
 *
 * @package TinySolutions\cptwooint
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
require_once __DIR__ . './../vendor/autoload.php';

use TinySolutions\cptwooint\Traits\SingletonTrait;
use TinySolutions\cptwooint\Controllers\Installation;
use TinySolutions\cptwooint\Controllers\Dependencies;
use TinySolutions\cptwooint\Controllers\AssetsController;
use TinySolutions\cptwooint\Controllers\Hooks\FilterHooks;
use TinySolutions\cptwooint\Controllers\Hooks\ActionHooks;
use TinySolutions\cptwooint\Controllers\Admin\AdminMenu;
use TinySolutions\cptwooint\Controllers\Admin\Api;


if ( ! class_exists( CptWooInt::class ) ) {
	/**
	 * Main initialization class.
	 */
	final class CptWooInt {

		/**
		 * Nonce id
		 *
		 * @var string
		 */
		public $nonceId = 'cptwooint_wpnonce';

        /**
         * Post Type.
         *
         * @var string
         */
        public $category = 'cptwooint_category';
		/**
		 * Singleton
		 */
		use SingletonTrait;

		/**
		 * Class Constructor
		 */
		private function __construct() {

			add_action( 'init', [ $this, 'language' ] );
			add_action( 'plugins_loaded', [ $this, 'init' ], 100 );
			// Register Plugin Active Hook.
			register_activation_hook( CPTWI_FILE, [ Installation::class, 'activate' ] );
			// Register Plugin Deactivate Hook.
			register_deactivation_hook( CPTWI_FILE, [ Installation::class, 'deactivation' ] );

        }

		/**
		 * Assets url generate with given assets file
		 *
		 * @param string $file File.
		 *
		 * @return string
		 */
		public function get_assets_uri( $file ) {
			$file = ltrim( $file, '/' );
			return trailingslashit( CPTWI_URL . '/assets' ) . $file;
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function get_template_path() {
			return apply_filters( 'cptwooint_template_path', 'templates/' );
		}

		/**
		 * Get the plugin path.
		 *
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( CPTWI_FILE ) );
		}

		/**
		 * Load Text Domain
		 */
		public function language() {
			load_plugin_textdomain( 'cptwooint', false, CPTWI_ABSPATH . '/languages/' );
		}

		/**
		 * Init
		 *
		 * @return void
		 */
		public function init() {
			if ( ! Dependencies::instance()->check() ) {
				return;
			}

			do_action( 'cptwooint/before_loaded' );


			// Include File.
            AssetsController::instance();
            AdminMenu::instance();
            FilterHooks::instance();
			ActionHooks::instance();
            Api::instance();

			do_action( 'cptwooint/after_loaded' );
		}

		/**
		 * Checks if Pro version installed
		 *
		 * @return boolean
		 */
		public function has_pro() {
			return function_exists( 'cptwoointp' );
		}

		/**
		 * PRO Version URL.
		 *
		 * @return string
		 */
		public function pro_version_link() {
			return '#';
		}
	}

	/**
	 * @return CptWooInt
	 */
	function cptwooint() {
		return CptWooInt::instance();
	}

	cptwooint();
}
