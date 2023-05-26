<?php
/**
 * Main FilterHooks class.
 *
 * @package TinySolutions\WM
 */

namespace TinySolutions\cptwooint\Controllers\Hooks;

use TinySolutions\cptwooint\Helpers\Fns;
use TinySolutions\cptwooint\Controllers\Modal\CPTproductDataStore;
use TinySolutions\cptwooint\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Main FilterHooks class.
 */
class FilterHooks {
	/**
	 * Singleton
	 */
	use SingletonTrait;
	/**
	 * Init Hooks.
	 *
	 * @return void
	 */
	private function __construct() {
        // Plugins Setting Page.
        add_filter( 'plugin_action_links_' . CPTWI_BASENAME,  [ $this, 'plugins_setting_links' ] );
		add_filter( 'woocommerce_data_stores', [ $this, 'cptwoo_data_stores' ] );
		add_filter('woocommerce_product_get_price', [ $this, 'cptwoo_product_get_price' ] , 10, 2 );
		// Show meta value after post content THis will be shortcode
		add_filter( 'the_content', [ $this, 'display_price' ]  );
	}

	/***
	 * @param $content
	 *
	 * @return mixed|string
	 */
	public function display_price( $content ) {
		//TODO:: Post Type And Meta key Will Dynamic.
		if ( get_post_type( get_the_ID() ) === 'cptproduct' ) {
			$content .= get_post_meta( get_the_ID(), 'price', true );
		}
		return $content ;
	}
	/**
	 * @param $price
	 * @param $product
	 *
	 * @return mixed
	 */
	public function cptwoo_product_get_price( $price, $product ) {
		//TODO:: Post Type And Meta key Will Dynamic.
		if ( get_post_type( $product->get_id() ) === 'cptproduct' ) {
			$price = get_post_meta( $product->get_id(), 'price', true );
		}
		return $price;
	}

	/**
	 * @param $stores
	 *
	 * @return mixed
	 */
	public function cptwoo_data_stores( $stores ) {
		$stores['product'] = CPTproductDataStore::class;
		return $stores;
	}

    /**
     * @param array $links default plugin action link
     *
     * @return array [array] plugin action link
     */
    public function plugins_setting_links( $links ) {
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

