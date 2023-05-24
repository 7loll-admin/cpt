<?php
/**
 * Main FilterHooks class.
 *
 * @package TinySolutions\WM
 */

namespace TinySolutions\cptwooint\Controllers\Hooks;

use TinySolutions\cptwooint\Helpers\Fns;
use TinySolutions\cptwooint\Controllers\Modal\CPTproductDataStore;

defined( 'ABSPATH' ) || exit();

/**
 * Main FilterHooks class.
 */
class FilterHooks {
	/**
	 * Init Hooks.
	 *
	 * @return void
	 */
	public static function init_hooks() {
        // Plugins Setting Page.
        add_filter( 'plugin_action_links_' . CPTWI_BASENAME,  [ __CLASS__, 'plugins_setting_links' ] );
		add_filter( 'woocommerce_data_stores', [ __CLASS__, 'cptwoo_data_stores' ] );
		add_filter('woocommerce_product_get_price', [ __CLASS__, 'cptwoo_product_get_price' ] , 10, 2 );
		// Show meta value after post content THis will be shortcode
		add_filter( 'the_content', [ __CLASS__, 'display_price' ]  );
	}

	public static  function display_price( $content ) {
		//TODO:: Post Type And Meta key Will Dynamic.
		if ( get_post_type( get_the_ID() ) === 'book' ) {
			$content .= get_post_meta( get_the_ID(), '_book_price', true );
		}
		return $content ;
	}
	/**
	 * @param $price
	 * @param $product
	 *
	 * @return mixed
	 */
	public static  function cptwoo_product_get_price( $price, $product ) {
		//TODO:: Post Type And Meta key Will Dynamic.
		if ( get_post_type( $product->get_id() ) === 'book' ) {
			$price = get_post_meta( $product->get_id(), '_book_price', true );
		}
		return $price;
	}

	/**
	 * @param $stores
	 *
	 * @return mixed
	 */
	public static function cptwoo_data_stores( $stores ) {
		$stores['product'] = CPTproductDataStore::class;
		return $stores;
	}

    /**
     * @param array $links default plugin action link
     *
     * @return array [array] plugin action link
     */
    public static function plugins_setting_links( $links ) {
        $links['cptwooint_settings'] = '<a href="' . admin_url( 'upload.php?page=cptwooint-settings' ) . '">' . esc_html__( 'Start Editing', 'cptwooint' ) . '</a>';
        /*
         * TODO:: Next Version
         *
         */
        if( ! Fns::is_plugins_installed('cpt-woo-integration-pro/cpt-woo-integration-pro.php') ){
            // $links['cptwooint_pro'] = sprintf( '<a href="#" target="_blank" style="color: #39b54a; font-weight: bold;">' . esc_html__( 'Go Pro', 'wp-media' ) . '</a>' );
        }
        return $links;
    }


}

