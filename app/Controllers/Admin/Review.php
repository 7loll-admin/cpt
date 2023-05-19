<?php

namespace TinySolutions\cptwooint\Controllers\Admin;

use TinySolutions\cptwooint\Traits\SingletonTrait;

/**
 * Review class
 */
class Review {
    /**
     * Singleton
     */
    use SingletonTrait;

    /**
	 * Init
	 *
	 * @return void
	 */
	private function __construct() {
        add_action( 'admin_init', [ __CLASS__, 'cptwooint_check_installation_time' ] );
        add_action( 'admin_init', [ __CLASS__, 'cptwooint_spare_me' ], 5 );
	}

	/**
     * Check if review notice should be shown or not
     *
     * @return void
     */
	public static function cptwooint_check_installation_time() {

		// Added Lines Start
		$nobug = get_option( 'cptwooint_spare_me' );

        $rated = get_option( 'cptwooint_rated' );

		if ( '1' == $nobug || 'yes' == $rated ) {
			return;
		}

		$now         = strtotime( 'now' );

		$install_date = get_option( 'cptwooint_plugin_activation_time' );

        $past_date    = strtotime( '+10 days', $install_date );

		$remind_time = get_option( 'cptwooint_remind_me' );

        $remind_time = $remind_time ? $remind_time : $past_date;

        $remind_due  = strtotime( '+15 days', $remind_time );

        if ( ! $now > $past_date || $now < $remind_due ) {
            return;
        }

        add_action( 'admin_notices', [ __CLASS__, 'cptwooint_display_admin_notice' ] );

	}
    /**
     * Remove the notice for the user if review already done or if the user does not want to
     *
     * @return void
     */
    public static function cptwooint_spare_me() {

        if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'cptwooint_notice_nonce' ) ) {
            return;
        }

        if ( isset( $_GET['cptwooint_spare_me'] ) && ! empty( $_GET['cptwooint_spare_me'] ) ) {
            $spare_me = absint( $_GET['cptwooint_spare_me'] );
            if ( 1 == $spare_me ) {
                update_option( 'cptwooint_spare_me', '1' );
            }
        }

        if ( isset( $_GET['cptwooint_remind_me'] ) && ! empty( $_GET['cptwooint_remind_me'] ) ) {
            $remind_me = absint( $_GET['cptwooint_remind_me'] );
            if ( 1 == $remind_me ) {
                $get_activation_time = strtotime( 'now' );
                update_option( 'cptwooint_remind_me', $get_activation_time );
            }
        }

        if ( isset( $_GET['cptwooint_rated'] ) && ! empty( $_GET['cptwooint_rated'] ) ) {
            $cptwooint_rated = absint(  $_GET['cptwooint_rated'] );
            if ( 1 == $cptwooint_rated ) {
                update_option( 'cptwooint_rated', 'yes' );
            }
        }
    }

	protected static function cptwooint_current_admin_url() {
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$uri = preg_replace( '|^.*/wp-admin/|i', '', $uri );

		if ( ! $uri ) {
			return '';
		}
		return remove_query_arg( [ '_wpnonce', '_wc_notice_nonce', 'wc_db_update', 'wc_db_update_nonce', 'wc-hide-notice', 'cptwooint_spare_me', 'cptwooint_remind_me', 'cptwooint_rated' ], admin_url( $uri ) );
	}


	/**
	 * Display Admin Notice, asking for a review
	 **/
	public static function cptwooint_display_admin_notice() {
		// WordPress global variable
		global $pagenow;
		$exclude = [
            'themes.php',
            'users.php',
            'tools.php',
            'options-general.php',
            'options-writing.php',
            'options-reading.php',
            'options-discussion.php',
            'options-media.php',
            'options-permalink.php',
            'options-privacy.php',
            'admin.php',
            'import.php',
            'export.php',
            'site-health.php',
            'export-personal-data.php',
            'erase-personal-data.php'
        ];

		if ( ! in_array( $pagenow, $exclude ) ) {

			$args = [ '_wpnonce' => wp_create_nonce( 'cptwooint_notice_nonce' ) ];

			$dont_disturb = add_query_arg( $args + [ 'cptwooint_spare_me' => '1' ], self::cptwooint_current_admin_url() );
			$remind_me    = add_query_arg( $args + [ 'cptwooint_remind_me' => '1' ], self::cptwooint_current_admin_url() );
			$rated        = add_query_arg( $args + [ 'cptwooint_rated' => '1' ], self::cptwooint_current_admin_url() );
			$reviewurl    = 'https://wordpress.org/support/plugin/media-library-tools/reviews/?filter=5#new-post';
			?>
			<div class="notice cptwooint-review-notice cptwooint-review-notice--extended">
				<div class="cptwooint-review-notice_content">
					<h3>Enjoying "Custom Post Type Woocommerce Integration"? </h3>
                    <p>Thank you for choosing "<string>Custom Post Type Woocommerce Integration</string>". If you found our plugin useful, please consider giving us a 5-star rating on WordPress.org. Your feedback  will motivate us to grow. </p>
					<div class="cptwooint-review-notice_actions">
						<a href="<?php echo esc_url( $reviewurl ); ?>" class="cptwooint-review-button cptwooint-review-button--cta" target="_blank"><span>⭐ Yes, You Deserve It!</span></a>
						<a href="<?php echo esc_url( $rated ); ?>" class="cptwooint-review-button cptwooint-review-button--cta cptwooint-review-button--outline"><span>😀 Already Rated!</span></a>
						<a href="<?php echo esc_url( $remind_me ); ?>" class="cptwooint-review-button cptwooint-review-button--cta cptwooint-review-button--outline"><span>🔔 Remind Me Later</span></a>
						<a href="<?php echo esc_url( $dont_disturb ); ?>" class="cptwooint-review-button cptwooint-review-button--cta cptwooint-review-button--error cptwooint-review-button--outline"><span>😐 No Thanks </span></a>
					</div>
				</div> 
			</div>
			<style> 
			.cptwooint-review-button--cta {
				--e-button-context-color: #5d3dfd;
				--e-button-context-color-dark: #5d3dfd;
				--e-button-context-tint: rgb(75 47 157/4%);
				--e-focus-color: rgb(75 47 157/40%);
			} 
			.cptwooint-review-notice {
				position: relative;
				margin: 5px 20px 5px 2px;
				border: 1px solid #ccd0d4;
				background: #fff;
				box-shadow: 0 1px 4px rgba(0,0,0,0.15);
				font-family: Roboto, Arial, Helvetica, Verdana, sans-serif;
				border-inline-start-width: 4px;
			}
			.cptwooint-review-notice.notice {
				padding: 0;
			}
			.cptwooint-review-notice:before {
				position: absolute;
				top: -1px;
				bottom: -1px;
				left: -4px;
				display: block;
				width: 4px;
				background: -webkit-linear-gradient(bottom, #5d3dfd 0%, #6939c6 100%);
				background: linear-gradient(0deg, #5d3dfd 0%, #6939c6 100%);
				content: "";
			} 
			.cptwooint-review-notice_content {
				padding: 20px;
			} 
			.cptwooint-review-notice_actions > * + * {
				margin-inline-start: 8px;
				-webkit-margin-start: 8px;
				-moz-margin-start: 8px;
			} 
			.cptwooint-review-notice p {
				margin: 0;
				padding: 0;
				line-height: 1.5;
			}
			p + .cptwooint-review-notice_actions {
				margin-top: 1rem;
			}
			.cptwooint-review-notice h3 {
				margin: 0;
				font-size: 1.0625rem;
				line-height: 1.2;
			}
			.cptwooint-review-notice h3 + p {
				margin-top: 8px;
			} 
			.cptwooint-review-button {
				display: inline-block;
				padding: 0.4375rem 0.75rem;
				border: 0;
				border-radius: 3px;;
				background: var(--e-button-context-color);
				color: #fff;
				vertical-align: middle;
				text-align: center;
				text-decoration: none;
				white-space: nowrap; 
			}
			.cptwooint-review-button:active {
				background: var(--e-button-context-color-dark);
				color: #fff;
				text-decoration: none;
			}
			.cptwooint-review-button:focus {
				outline: 0;
				background: var(--e-button-context-color-dark);
				box-shadow: 0 0 0 2px var(--e-focus-color);
				color: #fff;
				text-decoration: none;
			}
			.cptwooint-review-button:hover {
				background: var(--e-button-context-color-dark);
				color: #fff;
				text-decoration: none;
			} 
			.cptwooint-review-button.focus {
				outline: 0;
				box-shadow: 0 0 0 2px var(--e-focus-color);
			} 
			.cptwooint-review-button--error {
				--e-button-context-color: #d72b3f;
				--e-button-context-color-dark: #ae2131;
				--e-button-context-tint: rgba(215,43,63,0.04);
				--e-focus-color: rgba(215,43,63,0.4);
			}
			.cptwooint-review-button.cptwooint-review-button--outline {
				border: 1px solid;
				background: 0 0;
				color: var(--e-button-context-color);
			}
			.cptwooint-review-button.cptwooint-review-button--outline:focus {
				background: var(--e-button-context-tint);
				color: var(--e-button-context-color-dark);
			}
			.cptwooint-review-button.cptwooint-review-button--outline:hover {
				background: var(--e-button-context-tint);
				color: var(--e-button-context-color-dark);
			} 
			</style>
			<?php
		}
	}




}
