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

		$field_gap             = $style['fieldGap'] ?? '';
		$field_width           = $style['fieldWidth'] ?? '';
		$field_height          = $style['fieldHeight'] ?? '';
		$button_width          = $style['buttonWidth'] ?? '';
		$button_color          = $style['buttonColor'] ?? '';
		$button_bg_color       = $style['buttonBgColor'] ?? '';
		$button_hover_color    = $style['buttonHoverColor'] ?? '';
		$button_hover_hg_color = $style['buttonHoverBgColor'] ?? '';

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
            color: <?php echo sanitize_text_field( $button_color ); ?>;
		<?php }
		if ( $button_bg_color ) { ?>
            background-color: <?php echo sanitize_text_field( $button_bg_color ); ?>;
		<?php }
		$button_style = ob_get_clean();

		ob_start(); ?>
		<?php if ( $button_hover_color ) { ?>
            color: <?php echo sanitize_text_field( $button_hover_color ); ?>;
		<?php }
		if ( $button_hover_hg_color ) { ?>
            background-color: <?php echo sanitize_text_field( $button_hover_hg_color ); ?>;
		<?php }
		$button_hover_style = ob_get_clean();
		?>

        <style>
            .cptwooint-cart-form {
                display: flex;
                <?php if( $field_gap ) { ?>
                    gap: <?php echo absint( $field_gap )?>px;
                <?php } ?>
            }

            <?php if( $field_style ){ ?>
                .cptwooint-cart-form input[type="number"],
                .cptwooint-cart-form input[type="number"] {
                    box-sizing: border-box;
                    padding: 5px 10px;
                    border: 1px solid;
                    <?php echo $field_style; ?>
                }
            <?php }?>

            <?php if( $button_style ){ ?>
                .cptwooint-cart-form input[type="submit"] {
                    box-sizing: border-box;
                    padding: 5px 10px;
                    transition: 0.3s all;
                    cursor: pointer;
                    border: 1px solid;
                    <?php echo $button_style ; ?>
                }
            <?php }?>

            <?php if( $button_hover_style ){ ?>
                .cptwooint-cart-form input[type="submit"]:focus ,
                .cptwooint-cart-form input[type="submit"]:hover {
                    <?php echo $button_hover_style ; ?>
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
			$price = absint( get_post_meta( get_the_ID(), $meta_key, true ) );
		}
		ob_start();
		do_action( 'cptwooint_before_display_price' );
		echo $price;
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
		ob_start();
		do_action( 'cptwooint_before_display_add_tocart_form' );
		?>
        <form class="cptwooint-cart-form" action="<?php echo wc_get_cart_url(); ?>" method="post">
            <input name="add-to-cart" type="hidden" value="<?php echo get_the_ID() ?>"/>
            <input name="quantity" type="number" value="1" min="1"/>
            <input name="submit" type="submit" value="Add to cart"/>
        </form>
		<?php
		do_action( 'cptwooint_after_display_add_tocart_form' );

		return ob_get_clean();
	}


}
