<?php
/**
 * Main ActionHooks class.
 *
 * @package TinySolutions\cptwooint
 */

namespace TinySolutions\cptwooint\Controllers\Hooks;

use TinySolutions\cptwooint\Helpers\Fns;
use TinySolutions\cptwooint\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Main ActionHooks class.
 */
class ActionHooks {
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
		add_action( 'init', [ $this, 'the_shortcode' ] );
	}

	/**
	 * @return void
	 */
    public function the_shortcode() {
        add_shortcode( 'cptwooint_cart_button', [ $this, 'the_add_to_cart_form' ] );
        add_shortcode( 'cptwooint_price', [ $this, 'display_price' ] );
    }

    /***
     * @param $content
     *
     * @return mixed|string
     */
    public function display_price( $atts ) {
	    $attributes = shortcode_atts( array(
		    'title' => '',
	    ), $atts );
        $price = '';
	    $current_post_type = get_post_type( get_the_ID() );
	    if( ! Fns::is_supported( $current_post_type ) ){
		    return ;
	    }

	    $meta_key = Fns::meta_key( $current_post_type );
        if( $meta_key ){
            $price = absint( get_post_meta( get_the_ID(), $meta_key, true ) );
        }
	    ob_start();
            do_action('cptwooint_before_display_price');
            echo $price;
            do_action('cptwooint_after_display_price');
        return ob_get_clean();
    }
	/**
	 * @param $atts
	 *
	 * @return false|string|void
	 */
	public function the_add_to_cart_form( $atts ) {
		$attributes = shortcode_atts( array(
			'title' => false,
		), $atts );

		$current_post_type = get_post_type( get_the_ID() );

		if( ! Fns::is_supported( $current_post_type ) ){
			return ;
		}

		ob_start();
            do_action('cptwooint_before_display_add_tocart_form');
            ?>
                <form action="" method="post">
                    <input name="add-to-cart" type="hidden" value="<?php echo get_the_ID() ?>" />
                    <input name="quantity" type="number" value="1" min="1"  />
                    <input name="submit" type="submit" value="Add to cart" />
                </form>
            <?php
            do_action('cptwooint_after_display_add_tocart_form');
		return ob_get_clean();
	}


}
