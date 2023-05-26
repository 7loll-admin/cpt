<?php
/**
 * Main ActionHooks class.
 *
 * @package TinySolutions\cptwooint
 */

namespace TinySolutions\cptwooint\Controllers\Hooks;

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
        add_shortcode( 'wpdocs_the_shortcode', [ $this, 'the_add_to_cart_form' ] );
    }

	/**
	 * @param $atts
	 *
	 * @return false|string|void
	 */
	public function the_add_to_cart_form( $atts ) {
		$attributes = shortcode_atts( array(
			'title' => false,
			'limit' => 4,
		), $atts );
		global $post;
		if ($post->post_type !== 'cptproduct') {
            return;
        }
		ob_start();
		?>
		<form action="" method="post">
			<input name="add-to-cart" type="hidden" value="<?php echo $post->ID ?>" />
			<input name="quantity" type="number" value="1" min="1"  />
			<input name="submit" type="submit" value="Add to cart" />
		</form>
		<?php
		return ob_get_clean();
	}


}
