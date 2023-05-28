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

	}


	/**
	 * @param $price
	 * @param $product
	 *
	 * @return mixed
	 */
	public function cptwoo_product_get_price( $price, $product ) {
		$current_post_type = get_post_type( $product->get_id() );
		if( ! Fns::is_supported( $current_post_type ) ){
			return ;
		}
		$meta_key = Fns::meta_key( $current_post_type );
		if( $meta_key ){
			$price = get_post_meta( $product->get_id(), $meta_key, true );
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

