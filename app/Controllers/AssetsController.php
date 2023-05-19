<?php

namespace TinySolutions\cptwooint\Controllers;

use TinySolutions\cptwooint\Controllers\Admin\Settings;
use TinySolutions\cptwooint\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('This script cannot be accessed directly.');
}

/**
 * AssetsController
 */
class AssetsController
{
    /**
     * Singleton
     */
    use SingletonTrait;

    /**
     * Plugin version
     *
     * @var string
     */
    private $version;

    /**
     * Ajax URL
     *
     * @var string
     */
    private $ajaxurl;

    /**
     * Class Constructor
     */
    public function __construct() {
        $this->version = (defined('WP_DEBUG') && WP_DEBUG) ? time() : CPTWI_VERSION;
        /**
         * Admin scripts.
         */
        add_action('admin_enqueue_scripts', [$this, 'backend_assets'], 1);
    }


    /**
     * Registers Admin scripts.
     *
     * @return void
     */
    public function backend_assets( $hook ) {

        $scripts = [
            [
                'handle' => 'cptwooint-settings',
                'src' => cptwooint()->get_assets_uri('js/backend/admin-settings.js'),
                'deps' => [],
                'footer' => true,
            ]
        ];

        // Register public scripts.
        foreach ($scripts as $script) {
            wp_register_script($script['handle'], $script['src'], $script['deps'], $this->version, $script['footer']);
        }

        $current_screen =  get_current_screen() ;

        if ( isset( $current_screen->id ) && 'media_page_cptwooint-settings' === $current_screen->id ){

            wp_enqueue_style('cptwooint-settings');
            wp_enqueue_script('cptwooint-settings');

            wp_localize_script(
                'cptwooint-settings',
                'cptwoointParams',
                [
                    'ajaxUrl' => esc_url(admin_url('admin-ajax.php')),
                    'adminUrl' => esc_url(admin_url()),
                    'includesUrl' => esc_url(includes_url()),
                    'settings' => Settings::instance()->get_sections(),
                    'restApiUrl' => esc_url_raw(rest_url()), // site_url(rest_get_url_prefix()),
                    'rest_nonce' => wp_create_nonce( 'wp_rest' ),
                    cptwooint()->nonceId => wp_create_nonce(cptwooint()->nonceId),
                ]
            );

        }

    }



}
