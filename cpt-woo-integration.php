<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Custom Post Type Woocommerce Integration
 * Plugin URI:        https://wordpress.org/support/plugin/cpt-woo-integration
 * Description:       Custom post type woocommerce integration
 * Version:           1.0.0
 * Author:            Tiny Solutions
 * Author URI:        https://wptinysolutions.com/
 * Text Domain:       cptwooint
 * Domain Path:       /languages
 *
 * @package TinySolutions\WM
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Define cptwooint Constant.
 */

define( 'CPTWI_VERSION', '1.0.0' );

define( 'CPTWI_FILE', __FILE__ );

define( 'CPTWI_BASENAME', plugin_basename( CPTWI_FILE ) );

define( 'CPTWI_URL', plugins_url( '', CPTWI_FILE ) );

define( 'CPTWI_ABSPATH', dirname( CPTWI_FILE ) );

define( 'CPTWI_TEXT_DOMAIN', 'cptwooint' );

/**
 * App Init.
 */
require_once 'app/cptwooint.php';
