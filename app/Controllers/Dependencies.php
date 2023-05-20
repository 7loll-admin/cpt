<?php

namespace TinySolutions\cptwooint\Controllers;

use TinySolutions\cptwooint\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Dependencies
 */
class Dependencies {
	/**
	 * Singleton
	 */
	use SingletonTrait;

	const PLUGIN_NAME = 'Custom Post Type Woocommerce Integration';

	const MINIMUM_PHP_VERSION = '7.4';

	private $missing = [];
	/**
	 * @var bool
	 */
	private $allOk = true;

	/**
	 * @return bool
	 */
	public function check() {

		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'minimum_php_version' ] );
			$this->allOk = false;
		}

		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		if ( ! function_exists( 'wp_create_nonce' ) ) {
			require_once ABSPATH . 'wp-includes/pluggable.php';
		}

		// Check WooCommerce
		$woocommerce = 'woocommerce/woocommerce.php';

		if ( ! is_plugin_active( $woocommerce ) ) {
			if ( $this->is_plugins_installed( $woocommerce ) ) {
				$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $woocommerce . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $woocommerce );
				$message        = sprintf(
					'<strong>%s</strong> %s <strong>%s</strong> %s',
					esc_html( self::PLUGIN_NAME ),
					esc_html__( 'requires', 'cptwooint' ),
					esc_html__( 'WooCommerce', 'cptwooint' ),
					esc_html__( 'plugin to be active. Please activate WooCommerce to continue.', 'cptwooint' )
				);
				$button_text    = esc_html__( 'Activate WooCommerce', 'cptwooint' );

			} else {
				$activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ), 'install-plugin_woocommerce' );
				$message        = sprintf(
					'<strong>%s</strong> %s <strong>%s</strong> %s',
					esc_html( self::PLUGIN_NAME ),
					esc_html__( 'requires', 'cptwooint' ),
					esc_html__( 'WooCommerce', 'cptwooint' ),
					esc_html__( 'plugin to be installed and activated. Please install WooCommerce to continue.', 'cptwooint' )
				);
				$button_text    = esc_html__( 'Install WooCommerce', 'cptwooint' );
			}
			$this->missing['woocommerce'] = [
				'name'       => 'WooCommerce',
				'slug'       => 'woocommerce',
				'file_name'  => $woocommerce,
				'url'        => $activation_url,
				'message'    => $message,
				'button_txt' => $button_text,
				'active_path' => $woocommerce,
			];
		}

		if ( ! empty( $this->missing ) ) {
			add_action( 'admin_notices', [ $this, '_missing_plugins_warning' ] );

			$this->allOk = false;
		}

		return $this->allOk;
	}

	/**
	 * Admin Notice For Required PHP Version
	 */
	public function minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$message = sprintf(
		/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'cptwooint' ),
			'<strong>' . esc_html__( 'Custom Post Type Woocommerce Integration', 'cptwooint' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'cptwooint' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}


	/**
	 * Adds admin notice.
	 */
	public function _missing_plugins_warning() {
		$missingPlugins = '';
		$counter        = 0;
		foreach ( $this->missing as $plugin ) {
			$counter ++;
			if ( $counter == sizeof( $this->missing ) ) {
				$sep = '';
			} elseif ( $counter == sizeof( $this->missing ) - 1 ) {
				$sep = ' ' . esc_html__( 'and', 'cptwooint' ) . ' ';
			} else {
				$sep = ', ';
			}
			if ( current_user_can( 'activate_plugins' ) ) {
				$button = '<p><a data-slug="' . esc_attr( $plugin['slug'] ) . '" href="' . esc_url( $plugin['url'] ) . '" class="button-primary plugin-install-by-ajax">' . esc_html( $plugin['button_txt'] ) . '</a></p>';
				// $plugin['message'] Already used escaping function
				printf( '<div class="error notice_error"><p>%1$s</p>%2$s</div>', $plugin['message'], $button ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				$missingPlugins .= '<strong>' . esc_html( $plugin['name'] ) . '</strong>' . $sep;
			}
		}
		// TODO:: AJax plugin installation will do later.
		self::notice();
	}

	/**
	 * @param $plugin_file_path
	 *
	 * @return bool
	 */
	public function is_plugins_installed( $plugin_file_path = null ) {
		$installed_plugins_list = get_plugins();

		return isset( $installed_plugins_list[ $plugin_file_path ] );
	}


	/**
	 * Undocumented function.
	 *
	 * @return void
	 */
	public static function notice() {
		add_action( 'admin_enqueue_scripts', function () {
			wp_enqueue_script( 'jquery' );
		} );

		add_action( 'admin_footer', function () { ?>
            <style>
                .wp-core-ui .plugin-install-by-ajax {
                    display: inline-flex;
                    align-items: center;
                    gap: 20px;
                }

                .cptwint-loader {
                    border: 4px solid #f3f3f3;
                    border-radius: 50%;
                    border-top: 4px solid #3498db;
                    width: 10px;
                    height: 10px;
                    -webkit-animation: spin 2s linear infinite;
                    animation: spin 2s linear infinite;
                    margin-left: 5px;
                }

                /* Safari */
                @-webkit-keyframes spin {
                    0% {
                        -webkit-transform: rotate(0deg);
                    }
                    100% {
                        -webkit-transform: rotate(360deg);
                    }
                }

                @keyframes spin {
                    0% {
                        transform: rotate(0deg);
                    }
                    100% {
                        transform: rotate(360deg);
                    }
                }
            </style>
            <script type="text/javascript">
                (function ($) {
                    setTimeout(function () {
                        $('.plugin-install-by-ajax')
                            .on('click', function (e) {
                                e.preventDefault();
                                var that = $(this);
                                var plugin_slug = $(this).data('slug');
                                if ( plugin_slug ) {
                                    wp.updates.installPlugin({
                                        slug: plugin_slug,
                                        success: function (pluginData) {
                                            console.log( pluginData, 'Plugin installed successfully!' );
                                            if ( pluginData.activateUrl ) {
                                                that.html( 'Activation Prosses Running... <div class="cptwint-loader"></div>' );
                                                $.ajax({
                                                    url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                                                    data: {
                                                        action: 'cptwooint_plugin_activation',
                                                        plugin_slug: plugin_slug ? plugin_slug : null,
                                                        activation_url: pluginData.activateUrl.,
                                                        cptwooint_wpnonce: '<?php echo wp_create_nonce(cptwooint()->nonceId); ?>',
                                                    },
                                                    type: 'POST',
                                                    beforeSend() {

                                                    },
                                                    success(response) {
                                                        that.html( 'Activation Prosses Done' );
                                                        //that.parents('.notice_error').remove();
                                                    },
                                                    error(e) {},
                                                });
                                            }
                                        },
                                        error: function (error) {
                                            console.log( 'An error occurred: ' + error.statusText );
                                        },
                                        installing: function () {
                                            that.html( 'Installing plugin... <div class="cptwint-loader"></div>' );
                                            console.log( 'Installing plugin...!' );
                                        }
                                    });

                                }
                            });
                    }, 1000);

                })(jQuery);
            </script>
			<?php
		} );


	}


}
