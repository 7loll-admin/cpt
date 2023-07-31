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
		add_filter( 'the_content', [ $this, 'display_price_and_cart_button' ]  );

	}

	/***
	 * @param $content
	 *
	 * @return mixed|string
	 */
	public function display_price_and_cart_button( $content ) {
		$current_post_type = get_post_type( get_the_ID() );
		if( ! Fns::is_supported( $current_post_type ) ){
			return $content;
		}
		$options = Fns::get_options();
		$content .= '<div class="cpt-price-and-cart-button">';
		if(
			! empty( $options['price_position'] ) &&
		    ! empty( $options['price_after_content_post_types'] ) &&
		    'price_after_content' === $options['price_position'] &&
			is_array( $options['price_after_content_post_types'] ) &&
			in_array( $current_post_type, $options['price_after_content_post_types'] )
		){
			$content .=  do_shortcode( '[cptwooint_price/]');
		}

		if(
			! empty( $options['cart_button_position'] ) &&
			! empty( $options['cart_button_after_content_post_types'] ) &&
			'cart_button_after_content' === $options['cart_button_position'] &&
			is_array( $options['cart_button_after_content_post_types'] ) &&
			in_array( $current_post_type, $options['cart_button_after_content_post_types'] )
		){
			$content .=  do_shortcode( '[cptwooint_cart_button/]');
		}
		$content .= '</div>';
		return $content;
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
			return $price;
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
        $links['cptwooint_settings'] = '<a href="' . admin_url( 'admin.php?page=cptwooint-admin' ) . '">' . esc_html__( 'Settings', 'cptwooint' ) . '</a>';
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

