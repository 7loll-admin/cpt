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
		add_action( 'wp_head', [ $this, 'code_header' ] );

	}

	/**
	 * @return void
	 */
	public function code_header() {
		$options = Fns::get_options();
		$style   = $options['style'] ?? [];

		$field_gap             = $style['fieldGap'] ?? null;
		$field_width           = $style['fieldWidth'] ?? null;
		$field_height          = $style['fieldHeight'] ?? null;
		$button_width          = $style['buttonWidth'] ?? null;
		$button_color          = $style['buttonColor'] ?? null;
		$button_bg_color       = $style['buttonBgColor'] ?? null;
		$button_hover_color    = $style['buttonHoverColor'] ?? null;
		$button_hover_hg_color = $style['buttonHoverBgColor'] ?? null;

		ob_start();
		if ( $field_width ) { ?>
            width: <?php echo absint( $field_width ); ?>px;
		<?php }
		if ( $field_height ) { ?>
            height: <?php echo absint( $field_height ); ?>px;
		<?php }
		$field_style = ob_get_clean();

		ob_start(); ?>
		<?php if ( $button_width ) { ?>
            width: <?php echo absint( $button_width ); ?>px;
		<?php } ?>
		<?php if ( $field_height ) { ?>
            height: <?php echo absint( $field_height ); ?>px;
		<?php } ?>
		<?php if ( $button_color ) { ?>
            color: <?php echo esc_html( $button_color ); ?>;
		<?php }
		if ( $button_bg_color ) { ?>
            background-color: <?php echo esc_html( $button_bg_color ); ?>;
		<?php }
		$button_style = ob_get_clean();

		ob_start(); ?>
		<?php if ( $button_hover_color ) { ?>
            color: <?php echo esc_html( $button_hover_color ); ?>;
		<?php }
		if ( $button_hover_hg_color ) { ?>
            background-color: <?php echo esc_html( $button_hover_hg_color ); ?>;
		<?php }
		$button_hover_style = ob_get_clean();
		?>

        <style>
            .cptwooint-cart-form {
                display: flex;
            <?php if( $field_gap ) { ?> gap: <?php echo absint( $field_gap )?>px;
            <?php } ?>
            }

            <?php if( $field_style ){ ?>
            .cptwooint-cart-form input[type="number"],
            .cptwooint-cart-form input[type="number"] {
                box-sizing: border-box;
                padding: 5px 10px;
                border: 1px solid;
            <?php echo esc_html( $field_style ); ?>
            }

            <?php }?>

            <?php if( $button_style ){ ?>
            .cptwooint-cart-form input[type="submit"] {
                box-sizing: border-box;
                padding: 5px 10px;
                transition: 0.3s all;
                cursor: pointer;
                border: 1px solid;
            <?php echo esc_html( $button_style ) ; ?>
            }

            <?php }?>

            <?php if( $button_hover_style ){ ?>
            .cptwooint-cart-form input[type="submit"]:focus,
            .cptwooint-cart-form input[type="submit"]:hover {
            <?php echo esc_html( $button_hover_style  ); ?>
            }

            <?php } ?>

        </style>
		<?php
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
		$attributes        = shortcode_atts( array(
			'title' => '',
		), $atts );
		$price             = '';
		$current_post_type = get_post_type( get_the_ID() );
		if ( ! Fns::is_supported( $current_post_type ) ) {
			return;
		}
		$meta_key = Fns::meta_key( $current_post_type );
		if ( $meta_key ) {
			$price = floatval( get_post_meta( get_the_ID(), $meta_key, true ) );
		}
		ob_start();
		do_action( 'cptwooint_before_display_price' );
		echo wc_price( $price );
		do_action( 'cptwooint_after_display_price' );

		return ob_get_clean();
	}

	/**
	 * @param $atts
	 *
	 * @return false|string|void
	 */
	public function the_add_to_cart_form( $atts ) {
		$attributes        = shortcode_atts( array(
			'title' => false,
		), $atts );
		$current_post_type = get_post_type( get_the_ID() );
		if ( ! Fns::is_supported( $current_post_type ) ) {
			return;
		}
		$options  = Fns::get_options();
		$cart_url = $options['redirect_to_cart_page'] ? wc_get_cart_url() : '';
		ob_start();
		do_action( 'cptwooint_before_display_add_tocart_form' );
		?>
        <form class="cptwooint-cart-form" action="<?php echo esc_url( $cart_url ); ?>" method="post">
            <input name="add-to-cart" type="hidden" value="<?php echo get_the_ID() ?>"/>
            <input name="quantity" type="number" value="1" min="1"/>
            <input name="submit" type="submit" value="Add to cart"/>
        </form>
		<?php
		do_action( 'cptwooint_after_display_add_tocart_form' );

		return ob_get_clean();
	}


}
